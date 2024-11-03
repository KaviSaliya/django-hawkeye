@extends($theme.'layouts.user')
@section('title',trans('Verification Center'))
@section('content')
    <div class="pagetitle">
        <h3 class="mb-1">@lang('Verification Center')</h3>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('user.dashboard')}}">@lang('Home')</a></li>
                <li class="breadcrumb-item active">@lang('Verification Center')</li>
            </ol>
        </nav>
    </div>


    <div class="card mt-50">
        <div class="card-body">
            <div class="cmn-table">
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                        <tr>
                            <th scope="col">@lang('SL')</th>
                            <th scope="col">@lang('Type')</th>
                            <th scope="col">@lang('Status')</th>
                            <th scope="col">@lang('Submitted At')</th>
                            <th scope="col">@lang('Action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($userKycs as $key => $item)
                            <tr>
                                <td data-label="@lang('SL')"><span>{{++$key}}</span></td>
                                <td data-label="@lang('Type')">
                                    <span>{{$item->kyc_type}}</span>
                                </td>
                                <td data-label="@lang('Status')">
                                    {!! $item->getStatusBadge() !!}
                                </td>
                                <td data-label="@lang('Submitted At')">
                                    <span>{{dateTime($item->created_at)}}</span>
                                </td>
                                <td data-label="@lang('Action')">
                                    <div class="dropdown">
                                        <button class="action-btn2" type="button" id="verificationAction" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                            <i class="fa-regular fa-ellipsis-stroke-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item showVerificationDetails" data-bs-toggle="modal"
                                                   data-bs-target="#exampleModalCenter"
                                                   data-res="{{json_encode($item->kyc)}}"
                                                   data-type="{{$item->kyc_type}}"
                                                   href="javascript:void(0)">@lang('View')</a>
                                            </li>
                                            @if($item->status == 2)
                                                <li><a class="dropdown-item showRejectedReason"
                                                       data-bs-target="#RejectedReasonModal"
                                                       data-bs-toggle="modal" data-reason="{{$item->reason}}"
                                                       href="javascript:void(0)">@lang('Reason')</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
                @if(count($userKycs??[]) == 0)
                    <div class="row d-flex text-center justify-content-center">
                        <div class="col-4">
                            <img src="{{ asset('assets/admin/img/oc-error.svg') }}" id="no-data-image" class="no-data-image" alt="" srcset="">
                            <p>@lang('No data to show')</p>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
    <!-- Modal -->

    <div id="exampleModalCenter" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalCenterTitle"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group list-group-flush list-group-no-gutters verificationlistShow">

                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button"class="delete-btn" data-bs-dismiss="modal">@lang('Close') <span></span></button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->
    <!-- Modal -->
    <div id="RejectedReasonModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="exampleModalCenterTitle">@lang('Rejected Reason')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="rejectedReason"></p>
                </div>
                <div class="modal-footer">
                    <button type="button"class="delete-btn" data-bs-dismiss="modal">@lang('Close')<span></span></button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->

@endsection

@push('script')
    <script>
        $(document).ready(function (){
            $('.showVerificationDetails').on('click',function(){
                let kycInfo =  $(this).data('res');
                console.log(kycInfo);
                let details = '';
                $('#exampleModalCenterTitle').text($(this).data('type'))
                const verificationShow = $('.verificationlistShow');
                verificationShow.empty();
                $.each(kycInfo, function(key, value) {
                    console.log(value);
                    if(value.type === 'file'){
                        details += `
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5>${value.field_label}</h5>
                                    <ul class="list-unstyled list-py-2 text-body">
                                        <li class="d-flex justify-content-between"><a href="${value.field_value}" target="_blank"><img src="${value.field_value}" alt="" class="kycImage" srcset=""></a></li>
                                    </ul>
                                </div>
                            </li>`
                    }else {
                        details += `
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5>${value.field_label}</h5>
                                    <ul class="list-unstyled list-py-2 text-body">
                                        <li>${value.field_value}</li>
                                    </ul>
                                </div>
                            </li>`
                    }


                });
                verificationShow.append(details);
            })
            $('.showRejectedReason').on('click',function(){
                let reason = $(this).data('reason');
                console.log(reason)
                $('#rejectedReason').text(reason);
            })
        })
    </script>
@endpush
@push('style')
    <style>
        #verificationAction{
            padding: 5px 15px;
            border: 1px solid gray;
            border-radius: 5px;
        }
        #rejectedReason{
            font-size: 18px;
        }
        .kycImage{
            height: 100px;
            width: 100px;
        }
    </style>
@endpush
