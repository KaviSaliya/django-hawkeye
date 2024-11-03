@extends($theme.'layouts.user')
@section('title',trans('Support Ticket'))
@section('content')
    <div class="pagetitle">
        <h3 class="mb-1">@lang('Support Ticket')</h3>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('user.dashboard')}}">@lang('Home')</a></li>
                <li class="breadcrumb-item active">@lang('Support Ticket')</li>
            </ol>
        </nav>
    </div>

    <div class="card mt-50">
        <div class="card-header d-flex justify-content-end border-0">
            <div class="btn-area">
                <a href="{{route('user.ticket.create')}}" class="btn-1 mb-1"><i class="fa-regular fa-plus-circle"></i> @lang('New Ticket') <span></span></a>
            </div>
        </div>
        <div class="card-body">
            <div class="cmn-table">
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                        <tr>
                            <th scope="col">@lang('Subject')</th>
                            <th scope="col">@lang('Status')</th>
                            <th scope="col">@lang('Last Reply')</th>
                            <th scope="col">@lang('Action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($tickets as $key => $ticket)
                            <tr>
                                <td data-label="@lang('Subject')">
                                    <span class="font-weight-bold">
                                        [{{ trans('Ticket#').$ticket->ticket }}] {{ $ticket->subject }}
                                    </span>
                                </td>
                                <td data-label="@lang('Status')">
                                    {!! $ticket->getTicketStatusBadge() !!}
                                </td>
                                <td data-label="@lang('Last Reply')">
                                    {{diffForHumans($ticket->last_reply) }}
                                </td>

                                <td data-label="@lang('Action')">
                                    <a href="{{ route('user.ticket.view', $ticket->ticket) }}" class="btn-1"><i class="fal fa-eye"></i> @lang('View') <span></span></a>
                                </td>
                            </tr>
                        @empty

                        @endforelse
                        </tbody>
                    </table>

                </div>
                @if(count($tickets??[]) == 0)
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
    <div class="pagination-section">
        <nav aria-label="...">
            {{ $tickets->appends($_GET)->links($theme.'partials.user-pagination') }}
        </nav>
    </div>
@endsection
