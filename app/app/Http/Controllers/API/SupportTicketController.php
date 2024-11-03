<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportTicketAttachment;
use App\Models\SupportTicketMessage;
use App\Traits\ApiResponse;
use App\Traits\Notify;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SupportTicketController extends Controller
{
    use Upload, Notify ,ApiResponse;


    public function index()
    {
        $user = Auth::user();

        $tickets = SupportTicket::where('user_id', $user->id)
            ->select('id','user_id','ticket','subject','status','last_reply')
            ->latest()->paginate(12);
        return $this->jsonSuccess($tickets);
    }

    public function store(Request $request)
    {
        $validate =$this->newTicketValidation($request);
        if ($validate !== true){
            return $this->jsonError($validate);
        }
        $random = rand(100000, 999999);
        $ticket = $this->saveTicket($request, $random);

        $message = $this->saveMsgTicket($request, $ticket);

        if ( isset($request->attachments) && !empty($request->attachments)) {
            $numberOfAttachments = count($request->attachments);
            for ($i = 0; $i < $numberOfAttachments; $i++) {
                if ($request->hasFile('attachments.' . $i)) {
                    $file = $request->file('attachments.' . $i);
                    $supportFile = $this->fileUpload($file, config('filelocation.ticket.path'), null,null, 'webp',60);
                    if (empty($supportFile['path'])) {
                        throw new \Exception('File could not be uploaded.');
                    }
                   $attach =  $this->saveAttachment($message, $supportFile['path'], $supportFile['driver']);
                    if ($attach !== true){
                        return $this->jsonError($attach);
                    }
                }
            }
        }

        $msg = [
            'username' => optional($ticket->user)->username,
            'ticket_id' => $ticket->ticket
        ];
        $action = [
            "name" => optional($ticket->user)->firstname . ' ' . optional($ticket->user)->lastname,
            "image" => getFile(optional($ticket->user)->image_driver, optional($ticket->user)->image),
            "link" => route('admin.ticket.view',$ticket->id),
            "icon" => "fas fa-ticket-alt text-white"
        ];
        $this->adminPushNotification('SUPPORT_TICKET_CREATE', $msg, $action);
        $this->adminMail('SUPPORT_TICKET_CREATE', $msg);

        return $this->jsonSuccess([],'Your Ticket has been pending');
    }

    public function reply(Request $request, $id)
    {

        try {
            $ticket = SupportTicket::findOrFail($id);
            $message = new SupportTicketMessage();

                $images = $request->file('attachments');
                $allowedExtensions = array('jpg', 'png', 'jpeg', 'pdf');
                $rules = [
                    'attachments' => [
                        'max:4096',
                        function ($fail) use ($images, $allowedExtensions) {
                            foreach ($images as $img) {
                                $ext = strtolower($img->getClientOriginalExtension());
                                if (($img->getSize() / 1000000) > 2) {
                                    throw ValidationException::withMessages(['attachments'=>"Images MAX  2MB ALLOW!"]);
                                }
                                if (!in_array($ext, $allowedExtensions)) {
                                    throw ValidationException::withMessages(['attachments'=>"Only png, jpg, jpeg, pdf images are allowed"]);
                                }
                            }
                            if (count($images) > 5) {
                                throw ValidationException::withMessages(['attachments'=>"Maximum 5 images can be uploaded"]);
                            }
                        },
                    ],
                    'message' => 'required',
                ];

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return $this->jsonError(collect($validator->errors())->collapse());
                }

                $ticket->status = 2;
                $ticket->last_reply = Carbon::now();
                $ticket->save();

                $message->support_ticket_id = $ticket->id;
                $message->message = $request->message;
                $message->save();

                if (isset($request->attachments) && !empty($request->attachments)) {
                    $numberOfAttachments = count($request->attachments);
                    for ($i = 0; $i < $numberOfAttachments; $i++) {
                        if ($request->hasFile('attachments.' . $i)) {
                            $file = $request->file('attachments.' . $i);
                            $supportFile = $this->fileUpload($file, config('filelocation.ticket.path'), null,null,'webp',60);
                            if (empty($supportFile['path'])) {
                                throw new \Exception('File could not be uploaded.');
                            }
                           $attach = $this->saveAttachment($message, $supportFile['path'], $supportFile['driver']);
                            if ($attach !== true){
                                return $this->jsonError($attach);
                            }
                        }
                    }
                }

                $msg = [
                    'username' => optional($ticket->user)->username,
                    'ticket_id' => $ticket->ticket
                ];
                $action = [
                    "name" => optional($ticket->user)->firstname . ' ' . optional($ticket->user)->lastname,
                    "image" => getFile(optional($ticket->user)->image_driver, optional($ticket->user)->image),
                    "link" => route('admin.ticket.view',$ticket->id),
                    "icon" => "fas fa-ticket-alt text-white"
                ];
                $this->adminPushNotification('SUPPORT_TICKET_CREATE', $msg, $action);

                return $this->jsonSuccess([],'Ticket has been replied');

        }catch (\Exception $exception){
            return $this->jsonError($exception->getMessage());
        }

    }

    public function close(SupportTicket $ticket)
    {
        $ticket->status = 3;
        $ticket->save();
        return $this->jsonSuccess([],'Ticket has been closed');
    }

    public function saveMsgTicket(Request $request, $ticket): SupportTicketMessage
    {
        $message = new SupportTicketMessage();
        $message->support_ticket_id = $ticket->id;
        $message->message = $request->message;
        $message->save();
        return $message;
    }

    public function saveTicket(Request $request, $random): SupportTicket
    {
        $user = Auth::user();
        $ticket = new SupportTicket();
        $ticket->user_id = $user->id;
        $ticket->ticket = $random;
        $ticket->subject = $request->subject;
        $ticket->status = 0;
        $ticket->last_reply = Carbon::now();
        $ticket->save();
        return $ticket;
    }

    public function newTicketValidation(Request $request)
    {
        $images = $request->file('attachments');
        $allowedExtension = array('jpg', 'png', 'jpeg', 'pdf');

        $rule = [
            'attachments' => [
                'max:4096',
                function ($attribute, $value, $fail) use ($images, $allowedExtension) {
                    foreach ($images as $img) {
                        $ext = strtolower($img->getClientOriginalExtension());
                        if (($img->getSize() / 1000000) > 2) {
                            throw ValidationException::withMessages(['attachments'=>"Images MAX  2MB ALLOW!"]);
                        }
                        if (!in_array($ext, $allowedExtension)) {
                            throw ValidationException::withMessages(['attachments'=>"Only png, jpg, jpeg, pdf images are allowed"]);
                        }
                    }
                    if (count($images) > 5) {
                        throw ValidationException::withMessages(['attachments'=>"Maximum 5 images can be uploaded"]);
                    }
                },
            ],
            'subject' => 'required|max:100',
            'message' => 'required'
        ];

        $validator = Validator::make($request->all(), $rule);
        if ($validator->fails()) {
            return $this->jsonError(collect($validator->validate())->collapse());
        }

        return true;
    }


    public function saveAttachment($message, $path, $driver)
    {
        try {
            $attachment = SupportTicketAttachment::create([
                'support_ticket_message_id' => $message->id,
                'file' => $path ?? null,
                'driver' => $driver ?? 'local',
            ]);

            if (!$attachment){
                throw new \Exception('Something went wrong');
            }
            return true;
        }catch (\Exception $exception){
            return $exception->getMessage();
        }
    }

}
