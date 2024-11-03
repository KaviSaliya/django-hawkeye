@extends('admin.layouts.app')
@section('page_title',__('Project Invest History'))
@section('content')
    <div class="content container-fluid">
        <div class="row justify-content-lg-center">
            <div class="col-lg-10">

                @include('admin.user_management.components.header_user_profile')

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header card-header-content-md-between">
                                <div class="mb-2 mb-md-0">
                                    <div class="input-group input-group-merge navbar-input-group">
                                        <div class="input-group-prepend input-group-text">
                                            <i class="bi-search"></i>
                                        </div>
                                        <input type="search" id="datatableSearch"
                                               class="search form-control form-control-sm"
                                               placeholder="@lang('Search project invest history')"
                                               aria-label="@lang('Search project invest history')"
                                               autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class=" table-responsive datatable-custom  ">
                                <table id="datatable"
                                       class="js-datatable table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                                       data-hs-datatables-options='{
                                       "columnDefs": [{
                                          "targets": [0, 4],
                                          "orderable": false
                                        }],
                                        "ordering": false,
                                       "order": [],
                                       "info": {
                                         "totalQty": "#datatableWithPaginationInfoTotalQty"
                                       },
                                       "search": "#datatableSearch",
                                       "entries": "#datatableEntries",
                                       "pageLength": 15,
                                       "isResponsive": false,
                                       "isShowPaging": false,
                                       "pagination": "datatablePagination"
                                     }'>
                                    <thead class="thead-light">
                                    <tr>
                                        <th scope="col">@lang('Project')</th>
                                        <th scope="col">@lang('No. Of Unit')</th>
                                        <th scope="col">@lang('Price ')
                                            <i class="bi bi-info-circle text-body ms-1"
                                               data-bs-toggle="tooltip" data-bs-placement="top"
                                               aria-label="Per Unit"
                                               data-bs-original-title="Per Unit"></i>
                                        </th>
                                        <th scope="col">@lang('Profit')</th>
                                        <th scope="col">@lang('Return Period')</th>
                                        <th scope="col">@lang('Received Amount')</th>
                                        <th scope="col">@lang('Last Payment')</th>
                                        <th scope="col">@lang('Upcoming Payment')</th>
                                    </tr>
                                    </thead>

                                    <tbody>

                                    </tbody>
                                </table>
                            </div>

                            <div class="card-footer">
                                <div
                                    class="row justify-content-center justify-content-sm-between align-items-sm-center">
                                    <div class="col-sm mb-2 mb-sm-0">
                                        <div
                                            class="d-flex justify-content-center justify-content-sm-start align-items-center">
                                            <span class="me-2">@lang('Showing:')</span>
                                            <div class="tom-select-custom">
                                                <select id="datatableEntries"
                                                        class="js-select form-select form-select-borderless w-auto"
                                                        autocomplete="off"
                                                        data-hs-tom-select-options='{
                                                        "searchInDropdown": false,
                                                        "hideSearch": true
                                                      }'>
                                                    <option value="5">5</option>
                                                    <option value="10">10</option>
                                                    <option value="15" selected>15</option>
                                                    <option value="20">20</option>
                                                </select>
                                            </div>
                                            <span class="text-secondary me-2">@lang('of')</span>
                                            <span id="datatableWithPaginationInfoTotalQty"></span>
                                        </div>
                                    </div>

                                    <div class="col-sm-auto">
                                        <div class="d-flex  justify-content-center justify-content-sm-end">
                                            <nav id="datatablePagination" aria-label="Activity pagination"></nav>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.user_management.components.update_balance_modal')

@endsection


@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/flatpickr.min.css') }}">
@endpush


@push('js-lib')
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/select.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/flatpickr.min.js') }}"></script>
@endpush


@push('script')
    <script>
        'use strict';
        $(document).on('ready', function () {

            // Initial tooltip initialization
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Reinitialize tooltips after every AJAX call
            $(document).ajaxComplete(function() {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            });

            HSCore.components.HSFlatpickr.init('.js-flatpickr')
            HSCore.components.HSTomSelect.init('.js-select', {
                maxOptions: 250,
            })

            HSCore.components.HSDatatables.init($('#datatable'), {
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: "{{ route("admin.user.project.invest.history.search", $user->id) }}",
                },

                columns: [
                    {data: 'project', name: 'project'},
                    {data: 'unit', name: 'unit'},
                    {data: 'invest_per_unit', name: 'invest_per_unit'},
                    {data: 'return', name: 'return'},
                    {data: 'return_period', name: 'return_period'},
                    {data: 'received_amount', name: 'received_amount'},
                    {data: 'last_payment', name: 'last_payment'},
                    {data: 'next_payment', name: 'next_payment'},
                ],


                language: {
                    zeroRecords: `<div class="text-center p-4">
                    <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error.svg') }}" alt="Image Description" data-hs-theme-appearance="default">
                    <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error-light.svg') }}" alt="Image Description" data-hs-theme-appearance="dark">
                    <p class="mb-0">No data to show</p>
                    </div>`,
                    processing: `<div><div></div><div></div><div></div><div></div></div>`
                },

            })

            $.fn.dataTable.ext.errMode = 'throw';

            function updateTime() {
                $('.next-payment').each(function() {
                    const nextPaymentTime = new Date($(this).data('payment'));

                    if (nextPaymentTime){
                        const now = new Date();
                        let timeDifference = nextPaymentTime - now;

                        if (timeDifference > 0) {
                            const days = Math.floor(timeDifference / (1000 * 60 * 60 * 24));
                            timeDifference -= days * 1000 * 60 * 60 * 24;
                            const hours = Math.floor(timeDifference / (1000 * 60 * 60));
                            timeDifference -= hours * 1000 * 60 * 60;
                            const minutes = Math.floor(timeDifference / (1000 * 60));
                            timeDifference -= minutes * 1000 * 60;
                            const seconds = Math.floor(timeDifference / 1000);

                            $(this).text(`${days}d ${hours}h ${minutes}m ${seconds}s`);
                        } else {
                            $(this).text('Time has passed');
                        }
                    }
                });
            }

            // Initial call to set up the time
            updateTime();
            // Update every second
            setInterval(updateTime, 1000);
        });

    </script>
@endpush








