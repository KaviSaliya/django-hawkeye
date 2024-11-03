@extends($theme.'layouts.user')
@section('title',trans('Referral'))
@section('content')
    <div class="pagetitle">
        <h3 class="mb-1">@lang('Referral')</h3>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('user.dashboard')}}">@lang('Home')</a></li>
                <li class="breadcrumb-item active">@lang('Referral')</li>
            </ol>
        </nav>
    </div>
    <!-- main -->
    <div class="container-fluid">
        <div class="main row g-4">
            <div class="col-12">
               <div class="row mb-3">
                   <div class="col-xl-6 col-lg-12 mb-5">
                       <div class="card">
                           <div class="card-body">
                               <div class="row g-4">
                                   <div class="col-md-6">
                                       <div class="commission d-flex align-items-center justify-content-between">
                                           <div> <h5>@lang('Total Commission')</h5>
                                               <p>{{currencyPosition(auth()->user()->total_commission)}}</p></div>
                                           <div><i class="fa-duotone fa-sack-dollar"></i></div>
                                       </div>
                                   </div>
                                   <div class="col-md-6">
                                       <div class="commission d-flex align-items-center justify-content-between">
                                           <div>
                                               <h5>@lang('Level')</h5>
                                               <p>{{count($directReferralUsers)}}</p>
                                           </div>
                                           <div><i class="fa-light fa-user-plus"></i></div>
                                       </div>
                                   </div>
                               </div>
                               <div class="qna">
                                   <h4>@lang('Want to get more commission')?</h4>
                                   <p>@lang('Refer your friends and earn more commission')</p>
                               </div>
                               <div class=" share_link d-flex align-items-center">
                                   <i class="fa-sharp fa-regular fa-share-nodes"></i>
                                   <input type="text" class="input border-0"  id="referralURL" value="{{route('register',['ref' => auth()->id()])}}" readonly>
                                   <button class="copy_btn" onclick="copyFunction()"><i class="fa-regular fa-copy"></i></button>
                               </div>
                               <p class="refurlText">@lang('Copy this url and share with others')</p>

                               <ul id="socialShare">

                               </ul>
                           </div>
                       </div>
                   </div>
                   <div class="col-xl-6 col-lg-12">
                       <div class="card rounded-4 " id="depositPayoutHistoryCard">
                           <div class="card-header bg-white">
                               <h4 class="card-header-title">
                                   @lang('Referral Bonus')
                               </h4>
                           </div>
                           <div class="card-box" >
                               <div  id="depositPayoutHistory"></div>
                           </div>
                       </div>

                   </div>
               </div>
                @if(0 < count($directReferralUsers))
                    <div class="user-wrapper">
                        <div class="user-table">
                            <div class="card">
                                <div class="card-body">
                                        <div class="cmn-table skltbs-panel">
                                            <div class="table-responsive">
                                                <table class="table table-striped align-middle">
                                                    <thead>
                                                    <tr>
                                                        <th scope="col">@lang('Username')</th>
                                                        <th scope="col">@lang('Level')</th>
                                                        <th scope="col">@lang('Email')</th>
                                                        <th scope="col">@lang('Phone Number')</th>
                                                        <th scope="col">@lang('Joined At')</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody class="block-statistics">
                                                    @foreach($directReferralUsers as $user)
                                                        <tr id="user-{{ $user->id }}" data-level="0" data-loaded="false">
                                                            <td data-label="@lang('Username')">
                                                               <a href="javascript:void(0)"
                                                                  class="{{ count(getDirectReferralUsers($user->id)) > 0 ? 'nextDirectReferral' : '' }}"
                                                                  data-id="{{ $user->id }}"
                                                               >
                                                                   @if(count(getDirectReferralUsers($user->id)) > 0)
                                                                       <i class="far fa-circle-down color-primary"></i>
                                                                   @endif
                                                                   @lang($user->username)
                                                               </a>
                                                            </td>
                                                            <td data-label="@lang('Level')">
                                                                @lang('Level 1')
                                                            </td>
                                                            <td data-label="@lang('Email')" class="">{{$user->email}}</td>
                                                            <td data-label="@lang('Phone Number')">
                                                                {{$user->phone}}
                                                            </td>
                                                            <td data-label="@lang('Joined At')">
                                                                {{dateTime($user->created_at)}}
                                                            </td>

                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection


@push('script')
    <script>
        'use strict'
        $(document).on('click', '.nextDirectReferral', function () {
            let _this = $(this);
            let parentRow = _this.closest('tr');

            // Check if the downline is already loaded
            if (parentRow.data('loaded')) {
                return;
            }

            getDirectReferralUser(_this);
        });

        function getDirectReferralUser(_this) {

            Notiflix.Block.standard('.block-statistics');

            let userId = _this.data('id');
            let parentRow = _this.closest('tr');
            let currentLevel = parseInt(parentRow.data('level')) + 1;
            let downLabel = currentLevel + 1;

            setTimeout(function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route('user.myGetDirectReferralUser') }}",
                    method: 'POST',
                    data: {
                        userId: userId,
                    },
                    success: function (response) {

                        Notiflix.Block.remove('.block-statistics');
                        let directReferralUsers = response.data;

                        let referralData = '';

                        directReferralUsers.forEach(function (directReferralUser) {
                            referralData += `
                        <tr id="user-${directReferralUser.id}" data-level="${currentLevel}">
                            <td data-label="@lang('Username')" style="padding-left: ${currentLevel * 35}px;">
                                <a class="${directReferralUser.count_direct_referral > 0 ? 'nextDirectReferral' : ''}" href="javascript:void(0)" style="border-bottom: none !important;" data-id="${directReferralUser.id}">
                                    ${directReferralUser.count_direct_referral > 0 ? ' <i class="far fa-circle-down color-primary"></i>' : ''}
                                    ${directReferralUser.username}
                                </a>
                            </td>

                            <td data-label="@lang('Level')">
                                 <span class="text-dark">Level ${downLabel}</span>
                            </td>

                            <td data-label="@lang('Email')">
                                ${directReferralUser.email ? directReferralUser.email : '-'}
                            </td>
                            <td data-label="@lang('Phone Number')">
                                 ${directReferralUser.phone??'-'}
                            </td>

                            <td data-label="Joined At">
                                ${directReferralUser.joined_at}
                            </td>
                            </tr>`;
                        });

                        // Mark this row as having its downline loaded
                        parentRow.data('loaded', true);

                        $(`#user-${userId}`).after(referralData);
                    },
                    error: function (xhr, status, error) {
                        console.log(error);
                    }
                });
            }, 100);
        }


    </script>
    <script>
        $('#socialShare').socialSharingPlugin({
            url: '{{route('register',['ref' => auth()->id()])}}',
            title: 'join and enjoy',
            description: $('meta[property="og:description"]').attr('content'),
            img: $('meta[property="og:image"]').attr('content'),
            enable: [ 'facebook','whatsapp', 'twitter','email', 'linkedin', 'pinterest','reddit','telegram']
        });

        function copyFunction() {
            var copyText = document.getElementById("referralURL");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            /*For mobile devices*/
            document.execCommand("copy");
            Notiflix.Notify.success(`Copied: ${copyText.value}`);
        }

        $(document).ready(function (){
            Notiflix.Block.standard('#depositPayoutHistoryCard');


            var depositPayoutChartOptions = {
                series: [{
                    name: "Bonus",
                    data: []
                }
                ],
                chart: {
                    height: 410,
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
                await axios.get('{{route('user.referral.bonus.history')}}')
                    .then(function (response){
                        let newSeries = [{
                            name: 'Bonus',
                            data: response.data.referrals
                        }];
                        depositPayoutChart.updateSeries(newSeries)
                        Notiflix.Block.remove('#depositPayoutHistoryCard');
                    })
                    .catch(function (error){

                    })
            }

        })

    </script>
@endpush
