@extends($theme.'layouts.user')
@section('title',trans('Projects'))
@section('content')
    <div class="pagetitle">
        <h3 class="mb-1">@lang('Projects')</h3>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('user.dashboard')}}">@lang('Home')</a></li>
                <li class="breadcrumb-item active">@lang('Projects')</li>
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
                            <th scope="col">@lang('Title')</th>
                            <th scope="col">@lang('Unit Price')</th>
                            <th scope="col">@lang('ROI')
                                <i class="fa-sharp fa-thin fa-circle-info ms-1"
                                data-bs-toggle="tooltip" data-bs-placement="top"
                                aria-label="Per Return"
                                data-bs-original-title="Per Return"></i>
                            </th>
                            <th scope="col">@lang('Project Duration')</th>
                            <th scope="col">@lang('Action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($projects as $key => $project)
                            <tr>
                                <td data-label="@lang('Title')">{{optional($project->details)->title}}</td>
                                <td data-label="@lang('Unit Price')">{!! $project->investAmount() !!}</td>
                                <td data-label="@lang('ROI')">{{ $project->getReturn() }}</td>
                                <td data-label="@lang('Project Duration')">{!! $project->getUserProjectDuration() !!}</td>

                                <td data-label="@lang('Action')">
                                    <a href="{{route('project.details',[slug(optional($project->details)->title)??'project-details',$project->id])}}" class="btn-1"><i class="fal fa-eye"></i> @lang('View') <span></span></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
                @if(count($projects??[]) == 0)
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
            {{ $projects->appends($_GET)->links($theme.'partials.user-pagination') }}
        </nav>
    </div>


@endsection

