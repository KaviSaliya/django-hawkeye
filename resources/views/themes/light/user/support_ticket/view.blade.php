@extends($theme.'layouts.user')
@section('title',trans('Ticket') .'# '.$ticket->ticket)
@section('content')
    <div class="pagetitle">
        <h3 class="mb-1">  {{ trans('Ticket').'# '.$ticket->ticket }}</h3>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('user.dashboard')}}">@lang('Home')</a></li>
                <li class="breadcrumb-item active">  {{ trans('Ticket').'# '.$ticket->ticket }}</li>
            </ol>
        </nav>
    </div>

    <div class="message-container">
        <div class="row g-0">
            <div class="col-md-12">
                <div class="chat-box">
                    <div class="header-section">
                        <div class="profile-info">
                            <div class="thumbs-area">
                                <img src="{{getFile($admin->image_driver,$admin->image)}}" alt="admin">
                            </div>
                            <div class="content-area">
                                <div class="title">{{$admin->name}}</div>
                                <div class="description"> {!! $ticket->getTicketStatusBadge() !!}</div>
                            </div>
                        </div>
                        <div class="single-btn-box d-sm-flex d-flex justify-content-sm-end ">
                            <button type="button" id="infoBtn" data-bs-toggle="modal" data-bs-target="#closeTicketModal" class="btn-1"><i class="fa-sharp fa-thin fa-xmark"></i> @lang('Close') <span></span></button>
                        </div>
                    </div>
                    <div class="chat-box-inner">
                        @foreach( $ticket->messages->reverse() as $item)
                            @if($item->admin_id != null)
                                <div class="message-bubble message-bubble-left">
                                    <div class="message-thumbs-inner">
                                        <div class="message-thumbs">
                                            <img src="{{getFile($item->admin->image_driver, $item->admin->image)}}" alt="...">
                                        </div>
                                        <div class="message-text">{{$item->message}}</div>
                                    </div>

                                    <div class="message-time">
                                        <p>{{dateTime($item->created_at, 'd M, y h:i A')}}</p>
                                    </div>

                                    <div class="file-time">
                                        @foreach($item->attachments as $k=> $file)
                                            <div class="file">
                                                <a href="{{route('user.ticket.download',encrypt($file->id))}}">
                                                    <i class="fal fa-file"></i>
                                                    <span>@lang('File') {{++$k}}</span>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            @else

                                <div class="message-bubble message-bubble-right">
                                    <div class="message-thumbs-inner">
                                        <div class="message-thumbs">
                                            <img src="{{getFile($user->image_driver, $user->image)}}" alt="...">
                                        </div>
                                        <div class="message-text"> {{$item->message}}</div>
                                    </div>
                                    <div class="message-time">
                                        <p>{{dateTime($item->created_at, 'd M, y h:i A')}}</p>
                                    </div>
                                    <div class="file-time">
                                        @foreach($item->attachments as $k=> $file)
                                            <div class="file">
                                                <a href="{{route('user.ticket.download',encrypt($file->id))}}">
                                                    <i class="fal fa-file"></i>
                                                    <span>@lang('File') {{++$k}}</span>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>

                                </div>
                            @endif
                        @endforeach

                    </div>
                    <form action="{{ route('user.ticket.reply', $ticket->id)}}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="img-preview">
                            <button class="delete" type="button" id="deleteImage">
                                <i class="fal fa-times" aria-hidden="true"></i>
                            </button>
                            <img id="attachment" src="" alt="..." class="img-fluid insert">
                        </div>
                        <div class="chat-box-bottom">
                            <div class="input-group">
                                <button class="upload-img send-file-btn">
                                    <i class="fal fa-paperclip" aria-hidden="true"></i>
                                    <input class="form-control" accept="image/*" type="file" name="attachments[]" onchange="previewTicketImage('attachment')">
                                </button>
                                <textarea class="form-control" name="message" id="exampleFormControlTextarea1" rows="3"></textarea>
                                <button type="submit" name="replayTicket" value="1" class="message-send-btn"><i class="fa-thin fa-paper-plane"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="closeTicketModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">@lang('Confirmation') !</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('user.ticket.close',$ticket->id)}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <p>@lang('Are you want to close ticket')?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="delete-btn" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn-2">@lang('Confirm') <span></span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection


@push('script')
    <script>

        $(document).on('click','#deleteImage',function (){
            $('.img-preview').hide();
            $('#attachment').attr('src',false);
            $('#ticketImage').val('');
        })
        $(document).ready(function () {
            $('.img-preview').hide();
        })
        const previewTicketImage = (id) => {
            $('.img-preview').show();
            document.getElementById(id).src = URL.createObjectURL(event.target.files[0]);
        };
    </script>
@endpush

