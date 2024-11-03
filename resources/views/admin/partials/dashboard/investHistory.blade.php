<div class="card mb-3 mb-lg-5" id="salesChart">
    <!-- Header -->
    <div class="card-header card-header-content-sm-between">
        <h4 class="card-header-title mb-2 mb-sm-0">@lang('This Month Investment')
        </h4>
    </div>
    <!-- End Header -->

    <!-- Body -->
    <div class="card-body">
        <div class="row col-lg-divider">
            <div class="col-lg-9 mb-5 mb-lg-0">
                <!-- Bar Chart -->
                <div class="chartjs-custom mb-4">
                    <canvas id="investHistory" class="sales-chart-height"></canvas>
                </div>
                <!-- End Bar Chart -->
                <div class="row justify-content-center">
                    <div class="col-auto">
                        <span class="legend-indicator bg-custom"></span> @lang('Project Invest')
                    </div>
                    <!-- End Col -->
                    <div class="col-auto">
                        <span class="legend-indicator bg-primary"></span> @lang('Plan Invest')
                    </div>
                    <!-- End Col -->
                </div>
                <!-- End Row -->
            </div>

            <div class="col-lg-3">
                <div class="row">
                    <div class="col-sm-6 col-lg-12">
                        <!-- Stats -->
                        <div class="d-flex justify-content-center flex-column" style="min-height: 9rem;">
                            <h6 class="card-subtitle">@lang('Plan Invest')</h6>
                            <span class="d-block display-4 text-dark mb-1 me-3 plan-invest">0</span>
                            <span class="d-block text-success">
                    </span>
                        </div>
                        <!-- End Stats -->

                        <hr class="d-none d-lg-block my-0">
                    </div>
                    <!-- End Col -->

                    <div class="col-sm-6 col-lg-12">
                        <!-- Stats -->
                        <div class="d-flex justify-content-center flex-column" style="min-height: 9rem;">
                            <h6 class="card-subtitle">@lang('Project Invest')</h6>
                            <span class="d-block display-4 text-dark mb-1 me-3 project-invest">0</span>
                            <span class="d-block text-danger">

                    </span>
                        </div>
                        <!-- End Stats -->
                    </div>
                    <!-- End Col -->
                </div>
                <!-- End Row -->
            </div>
        </div>
        <!-- End Row -->
    </div>
    <!-- End Body -->
</div>

@push('css')
    <style>
        .bg-custom{
            --bs-bg-opacity: 1;
            background-color: #FF9500 !important;
        }
    </style>
@endpush

@push('script')
    <script>
        Notiflix.Block.standard('#salesChart');
        HSCore.components.HSChartJS.init(document.querySelector('#investHistory'), {
            type: "bar",
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    backgroundColor: "#377dff",
                    hoverBackgroundColor: "#377dff",
                    borderColor: "#377dff",
                    maxBarThickness: "10"
                },
                    {
                        data: [],
                        backgroundColor: "#FF9500",
                        borderColor: "#e7eaf3",
                        maxBarThickness: "10"
                    }]
            },
            options: {
                scales: {
                    y: {
                        grid: {
                            color: "#e7eaf3",
                            drawBorder: false,
                            zeroLineColor: "#e7eaf3"
                        },
                        ticks: {
                            beginAtZero: true,
                            stepSize: 100,
                            color: "#97a4af",
                            font: {
                                size: 12,
                                family: "Open Sans, sans-serif"
                            },
                            padding: 10
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            color: "#97a4af",
                            font: {
                                size: 12,
                                family: "Open Sans, sans-serif"
                            },
                            padding: 5
                        },
                        categoryPercentage: 0.5,
                        maxBarThickness: "10"
                    }
                },
                cornerRadius: 2,
                plugins: {
                    tooltip: {
                        prefix: "{{ basicControl()->currency_symbol }} ",
                        hasIndicator: true,
                        mode: "index",
                        intersect: false
                    }
                },
                hover: {
                    mode: "nearest",
                    intersect: true
                }
            }
        });
        const SalesChart = HSCore.components.HSChartJS.getItem('investHistory');


        updateInvestHistory();

        async function updateInvestHistory() {
            let $url = "{{ route('admin.investHistory') }}"
            await axios.get($url)
                .then(function (res) {
                    SalesChart.data.labels = res.data.schedule
                    SalesChart.data.datasets[0].data = res.data.planInvest
                    SalesChart.data.datasets[1].data = res.data.projectInvest
                    SalesChart.update();
                    Notiflix.Block.remove('#salesChart');
                    $('.project-invest').text(res.data.totalProjectInvest);
                    $('.plan-invest').text(res.data.totalPlanInvest)

                })
                .catch(function (error) {

                });
        }
    </script>
@endpush

