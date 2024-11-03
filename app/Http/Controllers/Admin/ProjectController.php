<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Project;
use App\Models\ProjectInvestment;
use App\Rules\AlphaDashWithoutSlashes;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use App\Traits\Upload;

class ProjectController extends Controller
{
    use Upload;
    /**
     * Display a listing of the resource.
     */
    public function index():View
    {
        $languages = Language::get();
        return view('admin.project.index', compact('languages'));
    }

    public function list(Request $request)
    {
        $defaultLanguage = Language::where('default_status',1)->first();
        $languages = Language::get();
        $projects = Project::query()->with('details')
            ->whereHas('details', function ($query) use ($defaultLanguage) {
                $query->where('language_id', $defaultLanguage->id);
            })
            ->where('is_deleted',0)
            ->when(!empty($request->search['value']), function ($query) use ($request) {
                $query->whereHas('details', function ($q) use ($request) {
                    $q->where('title','LIKE','%'.$request->search['value'].'%');
                });
            })
            ->orderBy('created_at', "DESC");
        return DataTables::of($projects)
            ->addColumn('title',function($project){
                return optional($project->details)->title;
            })
            ->addColumn('invest_amount',function($project){
                return $project->investAmount();
            })
            ->addColumn('total_units',function($project){
               return $project->total_units ;
            })
            ->addColumn('project_cycle',function($project){
                return $project->getAdminProjectDuration();
            })
            ->addColumn('return',function($project){
                return $project->getReturn();
            })
            ->addColumn('return_period',function($project){
                return 'Every '. $project->returnPeriod();
            })
            ->addColumn('status',function($item){
               return $item->getStatus();
            })
            ->addColumn('capital_back',function($item){
               return $item->getCapitalBack();
            })
            ->addColumn('language',function ($item)use($languages){
                $lang = '';
                foreach($languages as $language){
                    $lang .= ' <a href="'. route('admin.project.edit', [$item->id, $language->id]) .'"
                                          class="btn btn-white btn-icon btn-sm flag-btn"
                                          >
                                           <i class="bi '.$item->getLanguageEditClass($item->id, $language->id).'"></i>
                                       </a>';
                }

                return $lang;
            })
            ->addColumn('action', function ($item) {
                return '<div class="btn-group" role="group">
                    <a class="btn btn-white btn-sm" href="'.route('admin.project.edit', [$item->id,optional($item->details)->language_id]).'">
                      <i class="bi-pencil-fill me-1"></i> Edit
                    </a>

                    <!-- Button Group -->
                    <div class="btn-group">
                      <button type="button" class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty" id="productsEditDropdown1" data-bs-toggle="dropdown" aria-expanded="false"></button>

                      <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="productsEditDropdown1" style="">
                        <a class="dropdown-item deleteBtn" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#deleteModal" data-route="'.route('admin.project.destroy',$item->id).'">
                          <i class="bi-trash dropdown-item-icon"></i> Delete
                        </a>
                      </div>
                    </div>
                    <!-- End Button Group -->
                  </div>';
            })
            ->rawColumns(['total_units','title','project_cycle','capital_back','status','language','action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create():View
    {
        $language = Language::where('default_status',1)->firstOrFail();
        return view('admin.project.create',compact('language'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $data = $request->validate([
            'title' => 'required | string',
            'slug' => ['required', 'min:1', 'max:200',
                new AlphaDashWithoutSlashes(),
                Rule::notIn(['login', 'register', 'signin', 'signup', 'sign-in', 'sign-up'])
            ],
            'location' => 'required | string',
            'project_duration' => 'required_if:project_duration_has_unlimited,0 | numeric',
            'project_duration_type' => 'required_if:project_duration_has_unlimited,0 | string | in:Month,Year,Day,',
            'return' => 'required | integer',
            'return_type' => 'required | in:Fixed,Percentage' ,
            'return_period' => 'required | numeric',
            'return_period_type' => 'required | string |  in:Month,Year,Day,Hour' ,
            'number_of_return' =>[ 'required_if:number_of_return_has_unlimited,0',function ($attribute, $value, $fail)use($request) {
                if ($request->number_of_return_has_unlimited == 0 && !is_numeric($value)){
                    $fail('Minimum invest filed must be a number');
                }
            }],
            'minimum_invest' => ['required_if:has_amount_fixed,0',function ($attribute, $value, $fail)use($request) {
                if ($request->has_amount_fixed == 0 && !is_numeric($value)){
                    $fail('Minimum invest filed must be a number');
                }
            }],
            'maximum_invest' => ['required_if:has_amount_fixed,0',function ($attribute, $value, $fail)use($request) {
                if ($request->has_amount_fixed == 0 && !is_numeric($value)){
                    $fail('Maximum invest filed must be a number');
                }
            }],
            'invest_amount' => ['required_if:has_amount_fixed,1',function ($attribute, $value, $fail)use($request) {
                if ($request->has_amount_fixed == 1 && !is_numeric($value)){
                    $fail('Invest amount filed must be a number');
                }
            }],
            'images' => 'required | array',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg|max:5000',
            'description' => 'required | string',
            'short_description' => 'required | string',
            'status' => 'required | integer | in:1,0',
            'capital_back' => ['required','integer','in:1,0',function ($attribute, $value, $fail)use($request) {
                if ($request->number_of_return_has_unlimited == 1 && $value == 1){
                    $fail('Capital back cannot on if return type is lifetime');
                }
            }],
            'has_amount_fixed' => 'required | integer |in:1,0',
            'project_duration_has_unlimited' => ['required','integer','in:1,0'],
            'number_of_return_has_unlimited' => 'required | integer |in:1,0',
            'start_date' => ['required','date',function ($attribute, $value, $fail){
                $todayDate = Carbon::today()->format('Y-m-d');
                $pickupDate = Carbon::parse($value)->format('Y-m-d');
                if ($todayDate >$pickupDate){
                    $fail('The Selected date is invalid.');
                }
            }],
            'invest_last_date' => ['required','date',function ($attribute, $value, $fail)use($request) {
                try {
                    $todayDate = Carbon::today()->format('Y-m-d');
                    $pickupDate = Carbon::parse($value)->format('Y-m-d');
                    $start_date = Carbon::parse($request->start_date)->format('Y-m-d');
                    if ($todayDate >=$pickupDate){
                        $fail('The Selected date is invalid.');
                    }
                    if ($start_date >= $pickupDate){
                        $fail('The Selected date is invalid.');
                    }
                }catch (\Exception $e){
                    $fail($e->getMessage());
                }
            }],
            'total_units' => 'required | numeric',
            'maturity' => 'required|numeric'

        ]);

        try {

            if (request()->hasFile('images')) {
                $images = [];
                $imagesDriver = null;
                    foreach ($request->images as $image){
                        $upload = $this->fileUpload($image,config('filelocation.project.path'),null,null,'webp',60);
                        $images[] = $upload['path'];
                        $imagesDriver = $upload['driver'];
                    }
            }

            if (request()->hasFile('thumbnail')) {
                $thumbnailUpload = $this->fileUpload($request->thumbnail,config('filelocation.project.path'),null,null,'webp',60);
                $thumbnail = $thumbnailUpload['path'];
                $thumbnailDriver = $thumbnailUpload['driver'];
            }
            DB::beginTransaction();

                $project = new Project();
                $project->total_units = $request->total_units;
                $project->available_units = $request->total_units;
                $project->location = $data['location'];
                if ($data['project_duration_has_unlimited'] == 0){
                    $project->project_duration = $data['project_duration'];
                    $project->project_duration_type = $data['project_duration_type'];
                }
                $project->return = $data['return'];
                $project->return_type = $data['return_type'];
                $project->return_period = $data['return_period'];
                $project->return_period_type = $data['return_period_type'];
                if ($data['number_of_return_has_unlimited'] == 0 ){

                    $project->number_of_return = $data['number_of_return'];
                }

                if ($data['has_amount_fixed'] == 0 && !$data['invest_amount']){
                    $project->minimum_invest = $data['minimum_invest'];
                    $project->maximum_invest = $data['maximum_invest'];
                }
                if ($data['has_amount_fixed'] == 1 && $data['invest_amount']){
                    $project->fixed_invest = $data['invest_amount'];
                }

                $project->start_date = $data['start_date'];
                $project->invest_last_date = $data['invest_last_date'];

            if ($data['project_duration_has_unlimited'] == 0){
                // Get today's date
                $today = Carbon::create($data['start_date']);
                if ($data['project_duration_type'] == 'Month'){
                    $expireDate = $today->copy()->addMonths($data['project_duration']);
                }elseif ($data['project_duration_type'] == 'Year'){
                    $expireDate = $today->copy()->addYears($data['project_duration']);
                }elseif ($data['project_duration_type'] == 'Day'){
                    $expireDate = $today->copy()->addDays($data['project_duration']);
                }

                $project->expiry_date = $expireDate->format('Y-m-d');
            }

                $project->capital_back = $data['capital_back'];
                $project->amount_has_fixed = $data['has_amount_fixed'];
                $project->project_duration_has_unlimited = $data['project_duration_has_unlimited'];
                $project->number_of_return_has_unlimited = $data['number_of_return_has_unlimited'];
                $project->images = $images??[];
                $project->images_driver = $imagesDriver??null;
                $project->thumbnail_image = $thumbnail??null;
                $project->thumbnail_image_driver = $thumbnailDriver??null;
                $project->status = $data['status'];
                $project->maturity =  $data['maturity'];
                $project->save();
                $project->details()->create([
                    'language_id' => $request->language_id,
                    'title' => $data['title'],
                    'slug' => $data['slug'],
                    'description' => $data['description'],
                    'short_description' => $data['short_description'],
                ]);

                DB::commit();
            return redirect()->route('admin.project.index')->with('success', 'Project created successfully');
        }catch (\Exception $e){
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }


    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id,Language $language)
    {
        $project = Project::with(['details' => function ($query) use ($language) {
            $query->where('language_id',$language->id);
        }])->findOrFail($id);
        $projectImages = [];
        foreach ($project->images as $image){
            $projectImages[] = getFile($project->images_driver ,$image);
        }
        return view('admin.project.edit', compact('project','language','projectImages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id ,$language)
    {
        $project = Project::with(['details' => function ($query) use ($language) {
            $query->where('language_id',$language);
        }])->findOrFail($id);
        $data = $request->validate([
            'title' => 'required | string',
            'slug' =>  ['required', 'min:1', 'max:200',
                new AlphaDashWithoutSlashes(),
                Rule::unique('project_details', 'slug'),
                Rule::notIn(['login', 'register', 'signin', 'signup', 'sign-in', 'sign-up'])
            ],
            'location' => 'required | string',
            'project_duration' => 'required_if:project_duration_has_unlimited,0 | numeric',
            'project_duration_type' => 'required_if:project_duration_has_unlimited,0 | string | in:Month,Year,Day,',
            'return' => 'required | numeric',
            'return_type' => 'required | in:Fixed,Percentage' ,
            'return_period' => 'required | numeric',
            'return_period_type' => 'required | string |  in:Month,Year,Day,Hour' ,
            'number_of_return' =>[ 'required_if:number_of_return_has_unlimited,0','numeric'],
            'minimum_invest' => ['required_if:has_amount_fixed,0',function ($attribute, $value, $fail)use($request) {
                if ($request->has_amount_fixed == 0 && !is_numeric($value)){
                    $fail('Minimum invest filed must be a number');
                }
            }],
            'maximum_invest' => ['required_if:has_amount_fixed,0',function ($attribute, $value, $fail)use($request) {
                if ($request->has_amount_fixed == 0 && !is_numeric($value)){
                    $fail('Maximum invest filed must be a number');
                }
            }],
            'invest_amount' => ['required_if:has_amount_fixed,1',function ($attribute, $value, $fail)use($request) {
                if ($request->has_amount_fixed == 1 && !is_numeric($value)){
                    $fail('Invest amount filed must be a number');
                }
            }],
            'images' => 'nullable | array',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg|max:5000',
            'description' => 'required | string',
            'short_description' => 'required | string',
            'status' => 'required | integer | in:1,0',
            'capital_back' => ['required','integer','in:1,0',function ($attribute, $value, $fail)use($request) {
                if ($request->number_of_return_has_unlimited == 1 && $value == 1){
                    $fail('Capital back cannot on if return type is lifetime');
                }
            }],
            'has_amount_fixed' => 'required | integer |in:1,0',
            'project_duration_has_unlimited' => ['required','integer','in:1,0'],
            'number_of_return_has_unlimited' => 'required | integer |in:1,0',
            'start_date' => ['required','date',function ($attribute, $value, $fail){
                $todayDate = Carbon::today()->format('Y-m-d');
                $pickupDate = Carbon::parse($value)->format('Y-m-d');
                if ($todayDate >$pickupDate){
                    $fail('The Selected date is invalid.');
                }
            }],
            'invest_last_date' => ['required','date',function ($attribute, $value, $fail)use($request) {
                try {
                    $todayDate = Carbon::today()->format('Y-m-d');
                    $pickupDate = Carbon::parse($value)->format('Y-m-d');
                    if ($todayDate >=$pickupDate){
                        $fail('The Selected date is invalid.');
                    }
                }catch (\Exception $e){
                    $fail($e->getMessage());
                }
            }],
            'total_units' => 'required | numeric',
            'maturity' => 'required|numeric'

        ]);


        try {

            $projectImages = $project->images;
            $images = [];
            $imagesDriver = $project->images_driver;
            if ($request->old){
                foreach ($request->old as  $oldImage){
                    $images[] = $projectImages[$oldImage];
                }
                foreach ($projectImages as $image){
                    if (!in_array($image, $images)){
                        $this->fileDelete($project->images_driver,$image);
                    }
                }
            }else{
                if (!$request->hasFile('images')) {
                    return  back()->with('error', 'Images is required');
                }else{
                    foreach ($projectImages as $image){
                        $this->fileDelete($project->images_driver,$image);
                    }
                }
            }
            if (request()->hasFile('images')) {
                foreach ($request->images as $image){
                    $upload = $this->fileUpload($image,config('filelocation.project.path'),null,null,'webp',60);
                    $images[] = $upload['path'];
                    $imagesDriver = $upload['driver'];
                }
            }

            if (request()->hasFile('thumbnail')) {
                $thumbnailUpload = $this->fileUpload($request->thumbnail,config('filelocation.project.path'),null,null,'webp',60);
                $thumbnail = $thumbnailUpload['path'];
                $thumbnailDriver = $thumbnailUpload['driver'];
                $this->fileDelete($project->thumbnail_image_driver,$project->thumbnail_image);
            }
            DB::beginTransaction();

            $project->location = $data['location'];
            if ($project->total_units == $data['total_units']){
                $project->available_units = $project->available_units;
            }else{
                if ($project->total_units > $data['total_units']){
                    $update_units = $project->total_units -  $data['total_units'];
                    if ($update_units <= $project->available_units){
                        $project->available_units = $project->available_units - $update_units;
                    }else{
                        return back()->with('error','You cannot downgrade project units');
                    }
                }else{
                    $update_units = $data['total_units'] - $project->total_units;
                    $project->available_units = $project->available_units + $update_units;
                }
            }
            $project->total_units = $data['total_units'];

            if ($data['project_duration_has_unlimited'] == 0){
                $project->project_duration = $data['project_duration'];
                $project->project_duration_type = $data['project_duration_type'];
            }
            $project->return = $data['return'];
            $project->return_type = $data['return_type'];
            $project->return_period = $data['return_period'];
            $project->return_period_type = $data['return_period_type'];
            if ($data['number_of_return_has_unlimited'] == 0 ){
                $project->number_of_return = $data['number_of_return'];
            }

            if ($data['has_amount_fixed'] == 0 && !$data['invest_amount']){
                $project->minimum_invest = $data['minimum_invest'];
                $project->maximum_invest = $data['maximum_invest'];
            }
            if ($data['has_amount_fixed'] == 1 && $data['invest_amount']){
                $project->fixed_invest = $data['invest_amount'];
            }

            $project->start_date =  Carbon::parse($data['start_date'])->format('Y-m-d');
            $project->invest_last_date = $data['invest_last_date'];
            if ($data['project_duration_has_unlimited'] == 0){
                // Get today's date
                $today = Carbon::create($data['start_date']);
                if ($data['project_duration_type'] == 'Month'){
                    $expireDate = $today->copy()->addMonths($data['project_duration']);
                }elseif ($data['project_duration_type'] == 'Year'){
                    $expireDate = $today->copy()->addYears($data['project_duration']);
                }elseif ($data['project_duration_type'] == 'Day'){
                    $expireDate = $today->copy()->addDays($data['project_duration']);
                }

                $project->expiry_date = $expireDate->format('Y-m-d');
            }

            $project->capital_back = $data['capital_back'];
            $project->amount_has_fixed = $data['has_amount_fixed'];
            $project->project_duration_has_unlimited = $data['project_duration_has_unlimited'];
            $project->number_of_return_has_unlimited = $data['number_of_return_has_unlimited'];
            $project->images =   $images??$project->images;
            $project->images_driver = $imagesDriver??$project->images_driver;
            $project->thumbnail_image = $thumbnail??$project->thumbnail_image;
            $project->thumbnail_image_driver = $thumbnailDriver??$project->thumbnail_image_driver;
            $project->status = $data['status'];
            $project->maturity =  $data['maturity'];
            $project->save();
            $project->details()->updateOrCreate(['language_id' => $request->language_id],[
                'title' => $data['title'],
                'slug' => strtolower($data['slug']),
                'description' => $data['description'],
                'short_description' => $data['short_description'],
            ]);

            DB::commit();
            return back()->with('success', 'Project Updated successfully');
        }catch (\Exception $e){
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        try {
            if ($project->investment->first()){
                $project->is_deleted = 1;
                $project->save();
            }else{
                DB::beginTransaction();
                $project->details()->delete();
                $project->delete();
                DB::commit();
            }

            return back()->with('success', 'Project Deleted successfully');
        }catch (\Exception $e){
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }

    }

    public function projectInvestment()
    {
        return view('admin.project_invest_history.index');
    }

    public function getProjectInvestList(Request $request)
    {
        $invest = ProjectInvestment::query()->with(['project.details','user'])
            ->where('payment_status',1)
                    ->when(!empty($request->search['value']),function($q) use ($request){
                        $q->whereHas('project.details', function($q2) use ($request){
                            $q2->where('title', 'LIKE', '%'.$request->search['value'].'%');
                        })
                            ->orWhereHas('user', function($q3) use ($request){
                                $q3->where('firstname', 'LIKE', '%'.$request->search['value'].'%')
                                    ->orWhere('lastname', 'LIKE', '%'.$request->search['value'].'%')
                                    ->orWhereRaw("CONCAT(firstname, ' ', lastname) LIKE ?", [$request->search['value']])
                                    ->orWhere('username','LIKE','%'.$request->search['value'].'%');
                            })
                            ->orWhere('trx',$request->search['value']);
                    })
                ->orderBy('created_at','DESC');

        return DataTables::of($invest)
            ->addColumn('investor', function ($item) {
                    return $item->investor();
            })
            ->addColumn('project', function ($item) {
                return $item->getProject();
            })
            ->addColumn('unit', function ($item) {
               return "<span class='badge bg-secondary'>".$item->unit."</span>";
            })
            ->addColumn('invest_per_unit', function ($item) {
                return currencyPosition($item->per_unit_price);
            })
            ->addColumn('return',function($item){
               return currencyPosition($item->return);
            })
            ->addColumn('return_period',function($item){
                return 'Every '. $item->return_period.' '.$item->return_period_type;
            })
            ->addColumn('received_amount',function($item){
               return $item->receivedAmount();
            })
            ->addColumn('last_payment',function ($item){
                return $item->lastPayment();
            })
            ->addColumn('next_payment',function ($item){
                return $item->nextPayment();
            })
            ->rawColumns(['investor','project','unit','invest_per_unit','return','return_period','received_amount','last_payment','next_payment',])
            ->make(true);
    }
}
