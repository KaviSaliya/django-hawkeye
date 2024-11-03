<div class="card h-100" id="ReferralBonusCard">
    <!-- Body -->
    <div class=" card-body card-body-height" id="visitorsCard">
        <h4>@lang('Total Referral Bonus')</h4>

        <div class="row align-items-sm-center mt-4 mt-sm-0 mb-5">
            <div class="col-sm mb-3 mb-sm-0">
                <span class="display-5 text-dark me-2" id="totalBonus"></span>
            </div>
            <!-- End Col -->
            <div class="col-sm-auto">
                              <span class="">
                                @lang('Today Bonus')
                              </span>
                <span class="d-block">&mdash; <span id="todayBonus"></span></span>
            </div>
            <!-- End Col -->
        </div>
        <!-- End Row -->

        <!-- Bar Chart -->
        <div class="chartjs-custom mb-4">
            <canvas id="Referral" style="height: 13.5rem;">
            </canvas>
        </div>
        <!-- End Bar Chart -->

        <!-- Legend Indicators -->
        <div class="row justify-content-center">
            <div class="col-auto">
                <span class="legend-indicator"></span> @lang('Yesterday')
            </div>
            <!-- End Col -->

            <div class="col-auto">
                <span class="legend-indicator bg-primary"></span> @lang('Today')
            </div>
            <!-- End Col -->
        </div>
        <!-- End Legend Indicators -->
    </div>
    <!-- End Body -->
</div>



@push('script')
    <script>
        Notiflix.Block.standard('#ReferralBonusCard');
        HSCore.components.HSChartJS.init(document.querySelector('#Referral'),
            {
                type: "line",
                data: {
                    labels: [],
                    datasets: [{
                        data: [],
                        backgroundColor: "transparent",
                        borderColor: "#377dff",
                        borderWidth: 2,
                        pointRadius: 0,
                        hoverBorderColor: "#377dff",
                        pointBackgroundColor: "#377dff",
                        pointBorderColor: "#fff",
                        pointHoverRadius: 0,
                        tension: 0.4
                    },
                        {
                            data: [],
                            backgroundColor: "transparent",
                            borderColor: "#e7eaf3",
                            borderWidth: 2,
                            pointRadius: 0,
                            hoverBorderColor: "#e7eaf3",
                            pointBackgroundColor: "#e7eaf3",
                            pointBorderColor: "#fff",
                            pointHoverRadius: 0,
                            tension: 0.4
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
                                stepSize: 10,
                                color: "#97a4af",
                                font: {
                                    size: 12,
                                    family: "Open Sans, sans-serif"
                                },
                                padding: 10,
                                postfix: "k"
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
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            postfix: "",
                            hasIndicator: true,
                            mode: "index",
                            intersect: false,
                            lineMode: true,
                            lineWithLineColor: "rgba(19, 33, 68, 0.075)"
                        }
                    },
                    hover: {
                        mode: "nearest",
                        intersect: true
                    }
                }
            });
        const visitors = HSCore.components.HSChartJS.getItem('Referral');
        async function getBonusHistory() {
            let $url = "{{ route('admin.referral.bonus.history') }}"
            await axios.get($url)
                .then(function (res) {
                    visitors.data.labels = res.data.labels;
                    visitors.data.datasets[0].data = res.data.perHourBonus;
                    visitors.data.datasets[1].data = res.data.perHourYesterdayBonus;
                    visitors.update();
                    $('#todayBonus').text(res.data.todayBonus);
                    $('#totalBonus').text(res.data.totalBonus);
                    Notiflix.Block.remove('#ReferralBonusCard');
                })
                .catch(function (error) {

                });
        }
        getBonusHistory();
    </script>
@endpush
