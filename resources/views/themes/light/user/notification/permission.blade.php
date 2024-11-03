@extends($theme.'layouts.user')
@section('title',trans('Notification Permission'))
@section('content')
    <div class="pagetitle">
        <h3 class="mb-1">@lang('Notification Permission')</h3>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('user.dashboard')}}">@lang('Home')</a></li>
                <li class="breadcrumb-item active">@lang('Notification Permission')</li>
            </ol>
        </nav>
    </div>
    <div class="container-fluid">
        <div class="main row">
            <div class="col-12">
                <form action="{{route('user.notification.permission.update')}}" method="post">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="account-settings-profile-section">
                                <div class="cmn-table mt-20">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th  style="width: 45%;" scope="col">@lang('Type')</th>
                                                <th  scope="col">✉️ @lang('Email')</th>
                                                <th  scope="col">✉️ @lang('SMS')</th>
                                                <th  scope="col">🖥 @lang('Push')</th>
                                                <th  class="text-start" scope="col">@lang('👩🏻‍💻 In App')</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($allTemplates as $item)
                                                <tr>
                                                    <td data-label="Type">
                                                        <div class="d-flex align-items-center">
                                                            <span>{{$item->name}}</span>
                                                        </div>

                                                    </td>
                                                    <td data-label="✉️ Email">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox"
                                                                   role="switch" name="email_key[]"
                                                                   value="{{$item->template_key ?? ""}}"
                                                                   {{ !$item->status['mail'] ? 'disabled':'' }}
                                                                   id="emailSwitch"
                                                                {{ in_array($item->template_key, optional($user->notifypermission)->template_email_key ?? []) ? 'checked' : '' }}>
                                                        </div>
                                                    </td>
                                                    <td data-label="✉️ SMS">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox"
                                                                   role="switch" name="sms_key[]"
                                                                   value="{{$item->template_key ?? ""}}"
                                                                   {{ !$item->status['sms'] ? 'disabled':'' }}
                                                                   id="smsSwitch"
                                                                {{ in_array($item->template_key, optional($user->notifypermission)->template_sms_key ?? []) ? 'checked' : '' }}>
                                                        </div>
                                                    </td>
                                                    <td data-label="🖥 Browser">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox"
                                                                   role="switch"
                                                                   name="push_key[]"
                                                                   value="{{ $item->template_key ?? "" }}"
                                                                   {{ !$item->status['push'] ? 'disabled' : '' }}
                                                                   id="pushSwitch"
                                                                {{ in_array($item->template_key, optional($user->notifypermission)->template_push_key ?? []) ? 'checked' : '' }}>
                                                        </div>
                                                    </td>

                                                    <td data-label="👩🏻‍💻 App">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox"
                                                                   role="switch"
                                                                   name="in_app_key[]"
                                                                   value="{{$item->template_key ?? ""}}"
                                                                   id="appSwitch"
                                                                {{!$item->status['in_app'] ? 'disabled':''}}
                                                                {{ in_array($item->template_key, optional($user->notifypermission)->template_in_app_key ?? []) ? 'checked' : '' }}>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="text-center text-danger" colspan="100%">@lang('No Data Found.')</td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end bg-white">
                            <button type="submit" class="btn-2">@lang('Save changes')<span></span></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


