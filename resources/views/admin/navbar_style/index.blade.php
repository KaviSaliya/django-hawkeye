@extends('admin.layouts.app')
@section('page_title',__('Navbar Style'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0);">@lang('Dashboard')</a></li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Navbar Style')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Navbar Style')</h1>
                </div>
            </div>
        </div>
        <div class="row d-flex justify-content-center">
                <div class="col-8">
                    <div class="row gx-3">
                        <!-- Check -->
                        <div class="col-12">
                            <div class="form-check form-check-label-highlighter text-center">
                                <input type="radio" class="form-check-input navbar_style" name="navbar_style" id="labelHighlighterRadio1" @checked(basicControl()->navbar_style ==  'navbar_1') value="navbar_1">
                                <label class="form-check-label mb-2" for="labelHighlighterRadio1">
                                    <img class="form-check-img" src="{{asset('assets/admin/img/nav_1.png')}}" alt="Image Description">
                                </label>
                                <span class="form-check-text">Navbar 1</span>
                            </div>
                        </div>
                        <!-- End Check -->

                        <!-- Check -->
                        <div class="col-12">
                            <div class="form-check form-check-label-highlighter text-center">
                                <input type="radio" class="form-check-input navbar_style" name="navbar_style" id="labelHighlighterRadio2" @checked(basicControl()->navbar_style ==  'navbar_2') value="navbar_2">
                                <label class="form-check-label mb-2" for="labelHighlighterRadio2">
                                    <img class="form-check-img" src="{{asset('assets/admin/img/nav_2.png')}}" alt="Image Description">
                                </label>
                                <span class="form-check-text">Navbar 2</span>
                            </div>
                        </div>
                        <!-- End Check -->
                    </div>
                    <!-- End Row -->
                </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).on('click', '.navbar_style', function () {
            let value = $(this).val();
            axios.get('{{ route('admin.navbar.style.change') }}', {
                params: {
                    'style': value
                }
            })
                .then(response => {

                    console.log(response.data);
                })
                .catch(error => {

                    console.error(error);
                });
        });

    </script>
@endpush






