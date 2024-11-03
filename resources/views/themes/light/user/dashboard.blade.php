@extends($theme.'layouts.user')
@section('title',trans('Dashboard'))
@section('content')
    <div class="pagetitle">
        <h3 class="mb-1">@lang('Dashboard')</h3>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('user.dashboard')}}">@lang('Home')</a></li>
                <li class="breadcrumb-item active">@lang('Dashboard')</li>
            </ol>
        </nav>
    </div>
    <div class="section dashboard">
        <div class="row" id="firebase-app">
            <div v-if="user_foreground == '1' || user_background == '1'" class="card-box">
                <div v-if="notificationPermission == 'default' && !is_notification_skipped" v-cloak>
                    <div class="media align-items-center d-flex justify-content-between alert alert-warning mb-4">
                        <div>
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            @lang('Do not miss any single important notification! Allow your browser to get instant push notification')<button class="btn-2 ms-2" id="allow-notification"> @lang('Allow me') <span></span></button>
                        </div>
                        <button class="close-btn pt-1" @click.prevent="skipNotification"><i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-box" v-if="notificationPermission == 'denied' && !is_notification_skipped" v-cloak>
                <div class="media align-items-center d-flex justify-content-between alert alert-warning mb-4">
                    <div><i class="fas fa-exclamation-triangle"></i> @lang('Please allow your browser to get instant push notification. Allow it from notification setting.')</div>
                    <button class="close-btn pt-1" @click.prevent="skipNotification"><i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="section dashboard">
            <div class="row g-4">
                <div class="col-md-8">
                    <div class="row g-4">
                        <div class="col-xl-4 col-sm-6">
                            <div class="box-card grayish-blue-card moduleRecord">
                                <div class="top">
                                    <div class="icon-box">
                                        <i class="fa-solid fa-dollar-sign"></i>
                                    </div>
                                    <div class="text-box">
                                        <h5 class="title">@lang('Balance')</h5>
                                    </div>
                                </div>
                                <div class="bottom">
                                    <div class="item" data-bs-toggle="tooltip" data-bs-placement="top"
                                         title="@lang('Wallet Balance')">
                                        <span class="unAnsweredChat">{{currencyPosition(auth()->guard('web')->user()->balance??0)}}</span>
                                    </div>

                                    <div class="item" data-bs-toggle="tooltip" data-bs-placement="top"
                                         title="@lang('Profit Balance')">
                                        <span class="ProjectProfit">{{currencyPosition(auth()->guard('web')->user()->profit_balance??0)}}</span>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-sm-6">
                            <div class="box-card grayish-green-card moduleRecord">
                                <div class="top">
                                    <div class="icon-box">
                                         <i class="fa-light fa-file-chart-pie"></i>
                                    </div>
                                    <div class="text-box">
                                        <h5 class="title">@lang('Profit Statistics')</h5>
                                    </div>
                                </div>
                                <div class="bottom">
                                    <div class="item" data-bs-toggle="tooltip" data-bs-placement="top"
                                         title="@lang('Plan Profit')">
                                        <span class="planProfit">{{currencyPosition(auth()->guard('web')->user()->plan_profit??0)}}</span>
                                    </div>
                                    <div class="item" data-bs-toggle="tooltip" data-bs-placement="top"
                                         title="@lang('Project Profit')">
                                        <span class="ProjectProfit">{{currencyPosition(auth()->guard('web')->user()->project_profit??0)}}</span>
                                    </div>
                                    <div class="item" data-bs-toggle="tooltip" data-bs-placement="top"
                                         title="@lang('Total Profit')">
                                        <span class="ProjectProfit">{{currencyPosition(auth()->guard('web')->user()->total_profit??0)}}</span>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-sm-6">
                            <div class="box-card strong-orange-card moduleRecord">
                                <div class="top">
                                    <div class="icon-box">
                                        <i class="fa-light fa-chart-network"></i>
                                    </div>
                                    <div class="text-box">
                                        <h5 class="title">@lang('Invest Statistics')</h5>
                                    </div>
                                </div>
                                <div class="bottom">
                                    <div class="item" data-bs-toggle="tooltip" data-bs-placement="top"
                                         title="@lang('Plan Invest')">
                                        <span class="plan_invest">{{currencyPosition(auth()->guard('web')->user()->plan_invest??0)}}</span>
                                    </div>
                                    <div class="item" data-bs-toggle="tooltip" data-bs-placement="top"
                                         title="@lang('Project Invest')">
                                        <span class="project_invest">{{currencyPosition(auth()->guard('web')->user()->project_invest??0)}}</span>
                                    </div>
                                    <div class="item" data-bs-toggle="tooltip" data-bs-placement="top"
                                         title="@lang('Total Invest')">
                                        <span class="project_invest">{{currencyPosition(auth()->guard('web')->user()->total_invest??0)}}</span>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                   <div class="mt-30">
                       <div class="row g-4">
                           <div class="col-xl-8">
                               <h4 class="mb-20">
                                   @lang('Invest History')
                               </h4>
                               <div class="card rounded-4 " id="investHistoryCard">
                                   <div class="card-box" >
                                       <div  id="investHistory"></div>
                                   </div>
                               </div>
                           </div>
                           <div class="col-xl-4">
                               <h4 class="mb-20">@lang('Others')</h4>
                               <div class="row g-4">
                                   <div class="col-xl-12 col-sm-6">
                                       <div class="box-card grayish-blue-card moduleRecord">
                                           <div class="top">
                                               <div class="icon-box">
                                                   <i class="fa-regular fa-user-headset"></i>
                                               </div>
                                               <div class="text-box">
                                                   <h5 class="title">@lang('Ticket Statistics')</h5>
                                               </div>
                                           </div>
                                           <div class="bottom">
                                               <div class="item" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="@lang('Pending Ticket')">
                                                   <i class="fa-regular fa-spinner"></i>
                                                   <span class="pendingTicket">{{$pending_ticket}}</span>
                                               </div>
                                               <div class="item" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Answered Ticket">
                                                   <i class="fa-regular fa-book-open"></i>
                                                   <span class="openTicket">{{$answered_ticket}}</span>
                                               </div>
                                               <div class="item" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Closed Ticket">
                                                   <i class="fa-regular fa-box-check"></i>
                                                   <span class="closeTicket">{{$closed_ticket}}</span>
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                                   <div class="col-xl-12 col-sm-6">
                                       <div class="box-card strong-orange-card moduleRecord">
                                           <div class="top">
                                               <div class="icon-box">
                                                   <i class="fa-light fa-money-bill-wave"></i>
                                               </div>
                                               <div class="text-box">
                                                   <h5 class="title">@lang('Withdraw Statistics')</h5>

                                               </div>
                                           </div>
                                           <div class="bottom">
                                               <div class="item" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Total Payout">
                                                   <span class="totalProperty">{{currencyPosition($total_withdraw??0)}}</span>
                                               </div>
                                               <div class="item" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Last Payout">
                                                   <span class="totalVisitors">{{currencyPosition($last_withdraw->amount_in_base_currency??0)}}</span>
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                               </div>
                           </div>
                       </div>
                   </div>
                    <div class="mt-30">
                        <h4 class="mb-20">@lang('Recent Activity (7 Days)')</h4>
                        <div class="card">
                            <div class="card-body">
                                <div class="row g-4">
                                    <div class="col-xxl-3  col-md-6 col-sm-6">
                                        <div class="transaction-box activity-card">
                                            <div class="icon-box">
                                                <i class="fa-regular fa-user-headset"></i>
                                            </div>
                                            <div class="text-box">
                                                <h5 class="mb-0">{{$recentTickets}}</h5>
                                                <p class="mb-0">@lang('Support')</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3  col-md-6 col-sm-6">
                                        <div class="transaction-box activity-card">
                                            <div class="icon-box">
                                                <i class="fa-light fa-money-bill-wave"></i>
                                            </div>
                                            <div class="text-box">
                                                <h5 class="mb-0">{{currencyPosition($recent_withdraw)}}</h5>
                                                <p class="mb-0">@lang('Withdraw')</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3  col-md-6 col-sm-6 ">
                                        <div class="transaction-box activity-card">
                                            <div class="icon-box">
                                                <i class="fa-regular fa-notes-medical"></i>
                                            </div>
                                            <div class="text-box">
                                                <h5 class="mb-0">{{currencyPosition($recent_plan_invest)}}</h5>
                                                <p class="mb-0">@lang('Plan Invest')</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-3  col-md-6 col-sm-6">
                                        <div class="transaction-box activity-card">
                                            <div class="icon-box">
                                                <i class="fal fa-chart-line"></i>
                                            </div>
                                            <div class="text-box">
                                                <h5 class="mb-0">{{currencyPosition($recent_project_invest)}}</h5>
                                                <p class="mb-0">@lang('Project Invest')</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between border-0">
                                    <h4>@lang('Recent Plan Invest')</h4>
                                </div>
                                <div class="card-body">
                                    <div class="cmn-table">
                                        <div class="table-responsive">
                                            <table class="table table-striped align-middle">
                                                <thead>
                                                <tr>
                                                    <th scope="col">@lang('Plan')</th>
                                                    <th scope="col">@lang('Invest')</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @forelse($recent_plan as $plan)
                                                    <tr>
                                                        <td data-label="Plan">
                                                            <p>{{optional($plan->plan)->plan_name}}</p>
                                                        </td>
                                                        <td data-label="Invest">
                                                            <p>{{currencyPosition($plan->invest_amount)}}</p>
                                                        </td>
                                                    </tr>
                                                @empty

                                                @endforelse

                                                </tbody>
                                            </table>
                                        </div>
                                        @if(count($recent_plan??[]) == 0)
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
                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between border-0">
                                    <h4>@lang('Recent Project Invest')</h4>
                                </div>
                                <div class="card-body">
                                    <div class="cmn-table">
                                        <div class="table-responsive">
                                            <table class="table table-striped align-middle">
                                                <thead>
                                                <tr>
                                                    <th scope="col">@lang('Plan')</th>
                                                    <th scope="col">@lang('Invest')</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @forelse($recent_project as $project)
                                                    <tr>
                                                        <td data-label="Plan">
                                                            <p>{{optional($project->project->details)->title}}</p>
                                                        </td>
                                                        <td data-label="Invest">
                                                            <p>{{currencyPosition($project->per_unit_price * $project->unit)}}</p>
                                                        </td>
                                                    </tr>
                                                @empty
                                                @endforelse

                                                </tbody>
                                            </table>
                                        </div>
                                        @if(count($recent_project??[]) == 0)
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
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-30">
                <div class="row g-4">
                    <div class="col-xl-6 col-lg-12">
                        <div class="card rounded-4 " id="depositPayoutHistoryCard">
                            <div class="card-header bg-white">
                                <h4 class="card-header-title">
                                    @lang('Deposit & Payout History')
                                </h4>
                            </div>
                            <div class="card-box" >
                                <div  id="depositPayoutHistory"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-12">
                        <div class="card rounded-4 " id="TransactionHistoryCard">
                            <div class="card-header bg-white">
                                <h4 class="card-header-title">
                                    @lang('Transaction Summary')
                                </h4>
                            </div>
                            <div class="card-box" >
                                <div  id="transactionHistory"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
@endsection

@push('script')
    <script>
      $(document).ready(function (){
          Notiflix.Block.standard('#investHistoryCard');
          Notiflix.Block.standard('#depositPayoutHistoryCard');
          Notiflix.Block.standard('#TransactionHistoryCard');
          var options = {
              series: [{
                  name: 'Plan Invest',
                  data: []
              }, {
                  name: 'Project Invest',
                  data: []
              }],
              chart: {
                  type: 'bar',
                  height: 410,
                  toolbar: {
                      show: false,

                  }
              },
              plotOptions: {
                  bar: {
                      horizontal: false,
                      columnWidth: '50%', // Adjusted for equal spacing
                      borderRadius: 5,
                      endingShape: 'rounded'
                  },
              },
              dataLabels: {
                  enabled: false
              },
              stroke: {
                  show: true,
                  width: 2,
                  colors: ['transparent']
              },
              xaxis: {
                  categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep','Oct','Nov','Dec'],
              },
              fill: {
                  opacity: 1
              },
              tooltip: {
                  y: {
                      formatter: function (val) {
                          return currencyPosition(val)
                      }
                  }
              },
              colors: ['#ff6600', '#fb8e09']
          };
          var chart = new ApexCharts(document.querySelector("#investHistory"), options);
          chart.render();
          InvestHistory()

        async  function InvestHistory(){
            await axios.get('{{route('user.invest.history')}}')
                  .then(function (response){
                      var newSeries = [{
                          name: '{{trans('Plan Invest')}}',
                          data: response.data.plan_invest
                      }, {
                          name: '{{trans('Project Invest')}}',
                          data: response.data.project_invest
                      }];
                      chart.updateSeries(newSeries);
                      Notiflix.Block.remove('#investHistoryCard');
                  })
                  .catch(function (error){

                  })
          }


          var depositPayoutChartOptions = {
              series: [{
                  name: "Deposit",
                  data: []
              },
                  {
                      name: "Payout",
                      data: []
                  }
              ],
              chart: {
                  height: 350,
                  type: 'line',
                  zoom: {
                      enabled: false
                  },
                  toolbar: {
                      show: false,

                  }
              },
              dataLabels: {
                  enabled: false
              },
              stroke: {
                  curve: 'smooth'
              },

              grid: {
                  row: {
                      colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                      opacity: 0.5
                  },
              },
              xaxis: {
                  categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep','Oct','Nov','Dec'],
              },
              colors: ['#ff6600', '#fb8e09'],
              tooltip: {
                  y: {
                      formatter: function (val) {
                          return currencyPosition(val)
                      }
                  }
              }
          };

          const depositPayoutChart = new ApexCharts(document.querySelector("#depositPayoutHistory"), depositPayoutChartOptions);
          depositPayoutChart.render();
          depositPayoutHistory()

         async function depositPayoutHistory(){
              await axios.get('{{route('user.depositPayout.history')}}')
                  .then(function (response){
                      let newSeries = [{
                          name: 'Deposit',
                          data: response.data.deposits
                      }, {
                          name: 'Payout',
                          data: response.data.payouts
                      }];
                      depositPayoutChart.updateSeries(newSeries)
                      Notiflix.Block.remove('#depositPayoutHistoryCard');
                  })
                  .catch(function (error){

                  })
          }


          const transactionOptions = {
              series: [{
                  data: []
              }],
              chart: {
                  type: 'bar',
                  height: 350,
                  toolbar: {
                      show: false,

                  }
              },
              plotOptions: {
                  bar: {
                      borderRadius: 4,
                      borderRadiusApplication: 'end',
                      horizontal: true,
                  }
              },
              dataLabels: {
                  enabled: false
              },
              xaxis: {
                  categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep','Oct','Nov','Dec'],
              },
              tooltip: {
                  y: {
                      formatter: function (val) {
                          return currencyPosition(val)
                      }
                  }
              },
              colors: ['#ff6600', '#fb8e09'],
          };

          const TransactionChart = new ApexCharts(document.querySelector("#transactionHistory"), transactionOptions);
          TransactionChart.render();

          transactionHistory()

         async function transactionHistory(){
              await axios.get('{{route('user.transaction.history')}}')
                  .then(function (response){
                      let newSeries = [{
                          name: 'Transaction',
                          data: response.data.transactions
                      }];
                      TransactionChart.updateSeries(newSeries)
                      Notiflix.Block.remove('#TransactionHistoryCard');
                  })
                  .catch(function (err){
                      console.error(err)
                  })
          }



      })
      $(document).ready(function () {
          $('[data-bs-toggle="tooltip"]').tooltip();

          $('.item').hover(function() {
              $(this).tooltip('show');
          }, function() {
              $(this).tooltip('hide');
          });
      });

    </script>
@endpush

@if($firebaseNotify)
    @push('script')
        <script type="module">

            import {initializeApp} from "https://www.gstatic.com/firebasejs/9.17.1/firebase-app.js";
            import {
                getMessaging,
                getToken,
                onMessage
            } from "https://www.gstatic.com/firebasejs/9.17.1/firebase-messaging.js";

            const firebaseConfig = {
                apiKey: "{{$firebaseNotify['apiKey']}}",
                authDomain: "{{$firebaseNotify['authDomain']}}",
                projectId: "{{$firebaseNotify['projectId']}}",
                storageBucket: "{{$firebaseNotify['storageBucket']}}",
                messagingSenderId: "{{$firebaseNotify['messagingSenderId']}}",
                appId: "{{$firebaseNotify['appId']}}",
                measurementId: "{{$firebaseNotify['measurementId']}}"
            };

            const app = initializeApp(firebaseConfig);
            const messaging = getMessaging(app);
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('{{ getProjectDirectory() }}' + `/firebase-messaging-sw.js`, {scope: './'}).then(function (registration) {
                        requestPermissionAndGenerateToken(registration);
                    }
                ).catch(function (error) {
                });
            } else {
            }

            onMessage(messaging, (payload) => {
                if (payload.data.foreground || parseInt(payload.data.foreground) == 1) {
                    const title = payload.notification.title;
                    const options = {
                        body: payload.notification.body,
                        icon: payload.notification.icon,
                    };
                    new Notification(title, options);
                }
            });

            function requestPermissionAndGenerateToken(registration) {
                document.addEventListener("click", function (event) {
                    if (event.target.id == 'allow-notification') {
                        Notification.requestPermission().then((permission) => {
                            if (permission === 'granted') {
                                getToken(messaging, {
                                    serviceWorkerRegistration: registration,
                                    vapidKey: "{{$firebaseNotify['vapidKey']}}"
                                })
                                    .then((token) => {
                                        $.ajax({
                                            url: "{{ route('user.save.token') }}",
                                            method: "post",
                                            data: {
                                                token: token,
                                            },
                                            success: function (res) {
                                            }
                                        });
                                        window.newApp.notificationPermission = 'granted';
                                    });
                            } else {
                                window.newApp.notificationPermission = 'denied';
                            }
                        });
                    }
                });
            }
        </script>
        <script>
            window.newApp = new Vue({
                el: "#firebase-app",
                data: {
                    user_foreground: '',
                    user_background: '',
                    notificationPermission: Notification.permission,
                    is_notification_skipped: sessionStorage.getItem('is_notification_skipped') == '1'
                },
                mounted() {
                    sessionStorage.clear();
                    this.user_foreground = "{{$firebaseNotify['user_foreground']}}";
                    this.user_background = "{{$firebaseNotify['user_background']}}";
                },
                methods: {
                    skipNotification() {
                        sessionStorage.setItem('is_notification_skipped', '1')
                        this.is_notification_skipped = true;
                    }
                }
            });
        </script>
    @endpush
@endif

