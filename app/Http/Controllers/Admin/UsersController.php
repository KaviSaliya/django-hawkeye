<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\UserAllRecordDeleteJob;
use App\Models\Deposit;
use App\Models\Fund;
use App\Models\Gateway;
use App\Models\InvestHistory;
use App\Models\Language;
use App\Models\Payout;
use App\Models\PayoutMethod;
use App\Models\ProjectInvestment;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserKyc;
use App\Models\UserLogin;
use App\Rules\PhoneLength;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;
use App\Traits\Upload;
use App\Traits\Notify;
use Exception;

class UsersController extends Controller
{
    use Upload, Notify;

    public function index()
    {
        $data['basic'] = basicControl();
        $userRecord = \Cache::get('userRecord');
        if (!$userRecord) {
            $userRecord = User::withTrashed()->selectRaw('COUNT(id) AS totalUserWithTrashed')
                ->selectRaw('COUNT(CASE WHEN deleted_at IS NULL THEN id END) AS totalUser')
                ->selectRaw('(COUNT(CASE WHEN deleted_at IS NULL THEN id END) / COUNT(id)) * 100 AS totalUserPercentage')
                ->selectRaw('COUNT(CASE WHEN status = 1 THEN id END) AS activeUser')
                ->selectRaw('(COUNT(CASE WHEN status = 1 THEN id END) / COUNT(id)) * 100 AS activeUserPercentage')
                ->selectRaw('COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN id END) AS todayJoin')
                ->selectRaw('(COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN id END) / COUNT(id)) * 100 AS todayJoinPercentage')
                ->selectRaw('COUNT(CASE WHEN status = 0 THEN id END) AS deactivateUser')
                ->selectRaw('(COUNT(CASE WHEN status = 0 THEN id END) / COUNT(id)) * 100 AS deactivateUserPercentage')
                ->get()
                ->toArray();
            \Cache::put('userRecord', $userRecord);
        }
        $data['languages'] = Language::all();
        $data['allCountry'] = config('country');
        return view('admin.user_management.list', $data, compact('userRecord'));

    }

    public function search(Request $request)
    {

        $users = User::query()->orderBy('id', 'DESC')
            ->when(!empty($request->search['value']), function ($query) {
                $search = request()->search['value'];
                return $query->where('email', 'LIKE', "%{$search}%")
                    ->orWhere('username', 'LIKE', "%{$search}%")
                    ->orWhere('firstname', 'LIKE', "%{$search}%")
                    ->orWhere('lastname', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
            })
            ->when(isset($request->filterName) && !empty($request->filterName), function ($query) {
                $filterName = request()->filterName;
                return $query->where('username', 'LIKE', "%{$filterName}%")
                    ->orWhere('firstname', 'LIKE', "%{$filterName}%")
                    ->orWhere('lastname', 'LIKE', "%{$filterName}%");
            })
            ->when(isset($request->filterStatus), function ($query) {
                $filterStatus = request()->filterStatus;
                if ($filterStatus == 'all') {
                    return $query->where('status', '!=', null);
                }
                return $query->where('status', $filterStatus);
            })
            ->when(isset($request->filterEmailVerification) && !empty($request->filterEmailVerification), function ($query)  {
                return $query->where('email_verification', request()->filterEmailVerification);
            })
            ->when(isset($request->filterSMSVerification) && !empty($request->filterSMSVerification), function ($query)  {
                return $query->where('sms_verification', request()->filterSMSVerification);
            })

            ->when(isset($request->filterTwoFaVerification) && !empty($request->filterTwoFaVerification), function ($query)  {
                return $query->where('two_fa_verify', request()->filterTwoFaVerification);
            });


        return DataTables::of($users)
            ->addColumn('checkbox', function ($item) {
                return ' <input type="checkbox" id="chk-' . $item->id . '"
                                       class="form-check-input row-tic tic-check" name="check" value="' . $item->id . '"
                                       data-id="' . $item->id . '">';
            })
            ->addColumn('name', function ($item) {
                $url = route('admin.user.view.profile', $item->id);
                return '<a class="d-flex align-items-center me-2" href="' . $url . '">
                                <div class="flex-shrink-0">
                                  ' . $item->profilePicture() . '
                                </div>
                                <div class="flex-grow-1 ms-3">
                                  <h5 class="text-hover-primary mb-0">' . $item->firstname . ' ' . $item->lastname . '</h5>
                                  <span class="fs-6 text-body">@' . $item->username . '</span>
                                </div>
                              </a>';

            })
            ->addColumn('email-phone', function ($item) {
                return '<span class="d-block h5 mb-0">' . $item->email . '</span>
                            <span class="d-block fs-5">' . $item->phone . '</span>';
            })
            ->addColumn('balance', function ($item) {
                return currencyPosition($item->balance);
            })
            ->addColumn('profit_balance', function ($item) {
                return currencyPosition($item->profit_balance);
            })
            ->addColumn('status', function ($item) {
                if ($item->status == 1) {
                    return '<span class="badge bg-soft-success text-success">
                    <span class="legend-indicator bg-success"></span>' . trans('Active') . '
                  </span>';

                } else {
                    return '<span class="badge bg-soft-danger text-danger">
                    <span class="legend-indicator bg-danger"></span>' . trans('Inactive') . '
                  </span>';
                }
            })
            ->addColumn('last login', function ($item) {
                return diffForHumans($item->last_login);
            })
            ->addColumn('action', function ($item) {
                $editUrl = route('admin.user.edit', $item->id);
                $viewProfile = route('admin.user.view.profile', $item->id);
                return '<div class="btn-group" role="group">
                      <a href="' . $editUrl . '" class="btn btn-white btn-sm edit_user_btn">
                        <i class="bi-pencil-fill me-1"></i> ' . trans("Edit") . '
                      </a>
                    <div class="btn-group">
                      <button type="button" class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty" id="userEditDropdown" data-bs-toggle="dropdown" aria-expanded="false"></button>
                      <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="userEditDropdown">
                       <a class="dropdown-item" href="' . $viewProfile . '">
                          <i class="bi-eye-fill dropdown-item-icon"></i> ' . trans("View Profile") . '
                        </a>
                          <a class="dropdown-item" href="' . route('admin.send.email', $item->id) . '"> <i
                                class="bi-envelope dropdown-item-icon"></i> ' . trans("Send Mail") . ' </a>
                          <a class="dropdown-item loginAccount" href="javascript:void(0)"
                           data-route="' . route('admin.login.as.user', $item->id) . '"
                           data-bs-toggle="modal" data-bs-target="#loginAsUserModal">
                            <i class="bi bi-box-arrow-in-right dropdown-item-icon"></i>
                           ' . trans("Login As User") . '
                        </a>
                         <a class="dropdown-item addBalance" href="javascript:void(0)"
                           data-route="' . route('admin.user.update.balance', $item->id) . '"
                           data-balance="' . currencyPosition($item->balance) . '"
                           data-bs-toggle="modal" data-bs-target="#addBalanceModal">
                            <i class="bi bi-cash-coin dropdown-item-icon"></i>
                            ' . trans("Manage Balance") . '
                        </a>
                      </div>
                    </div>
                  </div>';
            })->rawColumns(['action', 'checkbox', 'name', 'balance','profit_balance', 'email-phone', 'status'])
            ->make(true);
    }


    public function deleteMultiple(Request $request)
    {
        if ($request->strIds == null) {
            session()->flash('error', 'You do not select User.');
            return response()->json(['error' => 1]);
        } else {
            User::whereIn('id', $request->strIds)->get()->map(function ($user) {
                UserAllRecordDeleteJob::dispatch($user);
                $user->forceDelete();
            });
            session()->flash('success', 'User has been deleted successfully');
            return response()->json(['success' => 1]);
        }
    }

    public function userEdit($id)
    {
        $data['languages'] = Language::all();
        $data['basicControl'] = basicControl();
        $data['allCountry'] = config('country');
        $data['userLoginInfo'] = UserLogin::where('user_id', $id)->orderBy('id', 'desc')->limit(5)->get();

        $data['user'] = User::findOrFail($id);
        return view('admin.user_management.edit_user', $data);
    }

    public function userUpdate(Request $request, $id)
    {

        $languages = Language::all()->map(function ($item) {
            return $item->id;
        });
        $phoneCode = $request->phone_code;
        $request->validate([
            'firstName' => 'required|string|min:2|max:100',
            'lastName' => 'required|string|min:2|max:100',
            'phone' => ['required', 'string'],
            'phone_code' => 'required | max:15',
            'country_code' => 'required | string | max:80',
            'country' => 'required | string | max:80',
            'city' => 'required|string|min:2|max:100',
            'state' => 'required|string|min:2|max:100',
            'addressOne' => 'required|string|min:2|max:100',
            'addressTwo' => 'nullable|string|min:2',
            'zipCode' => 'required|string|min:2|max:100',
            'status' => 'nullable|integer|in:0,1',
            'image' => 'nullable|mimes:jpeg,png,jpg,gif|max:2048',
            'language_id' => Rule::in($languages),
        ]);


        $user = User::where('id', $id)->firstOr(function () {
            throw new \Exception('User not found!');
        });
        if ($request->hasFile('image')) {
            try {
                $image = $this->fileUpload($request->image, config('filelocation.profileImage.path'), null, null, 'avif', 60, $user->image, $user->image_driver);
                if ($image) {
                    $profileImage = $image['path'];
                    $driver = $image['driver'];
                }
            } catch (\Exception $exp) {
                return back()->with('error', 'Image could not be uploaded.');
            }
        }

        try {
            $user->update([
                'firstname' => $request->firstName,
                'lastname' => $request->lastName,
                'phone' => $request->phone,
                'phone_code' => $request->phone_code,
                'country' => $request->country,
                'country_code' => $request->country_code,
                'language_id' => $request->language_id,
                'address_one' => $request->addressOne,
                'address_two' => $request->addressTwo,
                'city' => $request->city,
                'state' => $request->state,
                'zip_code' => $request->zipCode,
                'image' => $profileImage ?? $user->image,
                'image_driver' => $driver ?? $user->image_driver,
                'status' => $request->status
            ]);

            return back()->with('success', 'Basic Information Updated Successfully.');
        } catch (\Exception $exp) {
            return back()->with('error', $exp->getMessage());
        }
    }


    public function passwordUpdate(Request $request, $id)
    {
        $request->validate([
            'newPassword' => 'required|min:5|same:confirmNewPassword',
        ]);

        try {
            $user = User::where('id', $id)->firstOr(function () {
                throw new \Exception('User not found!');
            });

            $user->update([
                'password' => bcrypt($request->newPassword)
            ]);

            return back()->with('success', 'Password Updated Successfully.');

        } catch (\Exception $exp) {
            return back()->with('error', $exp->getMessage());
        }
    }

    public function EmailUpdate(Request $request, $id)
    {
        $request->validate([
            'new_email' => 'required|email:rfc,dns|unique:users,email,' . $id
        ]);

        try {
            $user = User::where('id', $id)->firstOr(function () {
                throw new \Exception('User not found!');
            });

            $user->update([
                'email' => $request->new_email,
            ]);

            return back()->with('success', 'Email Updated Successfully.');

        } catch (\Exception $exp) {
            return back()->with('error', $exp->getMessage());
        }

    }

    public function usernameUpdate(Request $request, $id)
    {


        $request->validate([
            'username' => 'required|unique:users,username,' . $id
        ]);

        try {
            $user = User::where('id', $id)->firstOr(function () {
                throw new \Exception('User not found!');
            });

            $user->update([
                'username' => $request->username,
            ]);

            return back()->with('success', 'Username Updated Successfully.');

        } catch (\Exception $exp) {
            return back()->with('error', $exp->getMessage());
        }

    }

    public function updateBalanceUpdate(Request $request, $id)
    {

        $request->validate([
            'amount' => 'required|numeric|min:1'
        ]);

        try {

            $user = User::where('id', $id)->firstOr(function () {
                throw new \Exception('User not found!');
            });
            $basic = basicControl();
            $walletType = 'wallet';
            if ($request->balance_operation == 1) {

              if ($request->balance_type == 1){
                  $user->balance += $request->amount;
              }else{
                  $walletType = "profit";
                  $user->profit_balance += $request->amount;
              }
                $user->save();

                $fund = new Fund();
                $fund->user_id = $user->id;
                $fund->percentage_charge = 0;
                $fund->fixed_charge = 0;
                $fund->charge = 0;
                $fund->amount = getAmount($request->amount);
                $fund->status = 1;
                $fund->save();

                $transaction = new Transaction();
                $transaction->user_id = $user->id;
                $transaction->amount = getAmount($request->amount);
                $transaction->balance = getAmount($user->balance);
                $transaction->charge = 0;
                $transaction->trx_type = '+';
                $transaction->remarks = "Add Balance to $walletType";
                $transaction->trx_id = $fund->transaction;
                $transaction->wallet_type = $walletType;
                $fund->transactional()->save($transaction);

                $msg = [
                    'amount' => currencyPosition($fund->amount),
                    'main_balance' => currencyPosition($user->balance),
                    'transaction' => $transaction->trx_id
                ];

                $action = [
                    "link" => route('user.transaction.list'),
                    "icon" => "fa fa-money-bill-alt text-white"
                ];
                $firebaseAction = route('user.transaction.list');
                $this->userFirebasePushNotification($user, 'ADD_BALANCE', $msg, $firebaseAction);
                $this->userPushNotification($user, 'ADD_BALANCE', $msg, $action);
                $this->sendMailSms($user, 'ADD_BALANCE', $msg);

                return redirect()->route('admin.user.transaction', $user->id)->with('success', 'Balance Updated Successfully.');

            } else {

                if ($request->amount > $user->balance) {
                    return back()->with('error', 'Insufficient Balance to deducted.');
                }
                if ($request->balance_type == 1){
                    $user->balance -= $request->amount;
                }else{
                    $walletType = "profit";
                    $user->profit_balance -= $request->amount;
                }
                $user->save();

                $transaction = new Transaction();
                $transaction->user_id = $user->id;
                $transaction->amount = getAmount($request->amount);
                $transaction->balance = $user->balance;
                $transaction->charge = 0;
                $transaction->trx_type = '-';
                $transaction->remarks = "Deduction Balance from $walletType";
                $transaction->wallet_type = $walletType;
                $transaction->save();

                $msg = [
                    'amount' => currencyPosition($request->amount),
                    'main_balance' => currencyPosition($user->balance),
                    'transaction' => $transaction->trx_id
                ];
                $action = [
                    "link" => route('user.transaction.list'),
                    "icon" => "fa fa-money-bill-alt text-white"
                ];
                $firebaseAction = route('user.transaction.list');
                $this->userFirebasePushNotification($user, 'DEDUCTED_BALANCE', $msg, $firebaseAction);
                $this->userPushNotification($user, 'DEDUCTED_BALANCE', $msg, $action);
                $this->sendMailSms($user, 'DEDUCTED_BALANCE', $msg);

                return redirect()->route('admin.user.transaction', $user->id)->with('success', 'Balance Updated Successfully.');

            }

        } catch (\Exception $exp) {
            return back()->with('error', $exp->getMessage());
        }

    }


    public function preferencesUpdate(Request $request, $id)
    {
        $languages = Language::all()->map(function ($item) {
            return $item->id;
        });

        $request->validate([
            'language_id' => Rule::in($languages),
            'time_zone' => 'required|string|min:1|max:100',
            'email_verification' => 'nullable|integer|in:0,1',
            'sms_verification' => 'nullable|integer|in:0,1',
        ]);

        try {
            $user = User::where('id', $id)->firstOr(function () {
                throw new \Exception('User not found!');
            });

            $user->update([
                'language_id' => $request->language_id,
                'time_zone' => $request->time_zone,
                'email_verification' => $request->email_verification,
                'sms_verification' => $request->sms_verification,
            ]);

            return back()->with('success', 'Preferences Updated Successfully.');

        } catch (\Exception $exp) {
            return back()->with('error', $exp->getMessage());
        }


    }

    public function userTwoFaUpdate(Request $request, $id)
    {
        try {
            $user = User::where('id', $id)->firstOr(function () {
                throw new \Exception('User not found!');
            });
            $user->update([
                'two_fa_verify' => ($request->two_fa_security == 1) ? 0 : 1
            ]);

            return back()->with('success', 'Two Fa Security Updated Successfully.');

        } catch (\Exception $exp) {
            return back()->with('error', $exp->getMessage());
        }
    }

    public function userDelete(Request $request, $id)
    {
        try {
            $user = User::where('id', $id)->firstOr(function () {
                throw new \Exception('User not found!');
            });

            UserAllRecordDeleteJob::dispatch($user);
            $user->forceDelete();
            return redirect()->route('admin.users')->with('success', 'User Account Deleted Successfully.');

        } catch (\Exception $exp) {
            return back()->with('error', $exp->getMessage());
        }
    }

    public function userAdd()
    {
        $data['allCountry'] = config('country');
        return view('admin.user_management.add_user', $data);
    }

    public function userStore(Request $request)
    {
        $phoneCode = $request->phone_code;
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string|min:2|max:255',
            'lastName' => 'required|string|min:2|max:255',
            'username' => 'required|string|unique:users,username|min:2|max:255',
            'email' => 'required|email:rfc,dns|unique:users,email|min:2|max:255',
            'phone' => ['required', 'string'],
            'phone_code' => 'required | max:15',
            'country_code' => 'required | string | max:80',
            'country' => 'required | string | max:80',
            'city' => 'required|string|min:2|max:255',
            'state' => 'nullable|string|min:2|max:255',
            'zipCode' => 'nullable|string|min:2|max:20',
            'addressOne' => 'required|string|min:2',
            'addressTwo' => 'nullable|string|min:2',
            'status' => 'nullable|integer|in:0,1',
            'image' => 'nullable|mimes:jpeg,png,jpg,gif',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('image')) {
            try {
                $image = $this->fileUpload($request->image, config('filelocation.profileImage.path'), null, null, 'avif', 60);
                if ($image) {
                    $profileImage = $image['path'];
                    $driver = $image['driver'];
                }
            } catch (\Exception $exp) {
                return back()->with('error', 'Image could not be uploaded.');
            }
        }

        try {
            $response = User::create([
                'firstname' => $request->firstName,
                'lastname' => $request->lastName,
                'username' => $request->username,
                'email' => $request->email,
                'phone' => $request->phone,
                'phone_code' => $request->phone_code,
                'country' => $request->country,
                'country_code' => $request->country_code,
                'language_id' => $request->language_id,
                'address_one' => $request->addressOne,
                'address_two' => $request->addressTwo,
                'city' => $request->city,
                'state' => $request->state,
                'zip_code' => $request->zipCode,
                'image' => $profileImage ?? null,
                'image_driver' => $driver ?? 'local',
                'status' => $request->status
            ]);

            if (!$response) {
                throw new Exception('Something went wrong, Please try again.');
            }

            return redirect()->route('admin.user.create.success.message', $response->id)->with('success', 'User created successfully');

        } catch (\Exception $exp) {
            return back()->with('error', $exp->getMessage());
        }
    }

    public function userCreateSuccessMessage($id)
    {
        $data['user'] = User::findOrFail($id);
        return view('admin.user_management.components.user_add_success_message', $data);
    }

    public function userViewProfile($id)
    {
        $data['user'] = User::findOrFail($id);
        $data['basic'] = basicControl();
        $data['transactions'] = Transaction::with('user')->where('user_id', $id)->orderBy('id', 'DESC')
            ->limit(5)->get();

        $data['paymentLog'] = Deposit::with('user', 'gateway')->where('user_id', $id)
            ->where('status', '!=', 0)
            ->orderBy('id', 'DESC')
            ->limit(5)
            ->get();

        $data['withDraws'] = Payout::with('user', 'method')->where('user_id', $id)
            ->where('status', '!=', 0)
            ->orderBy('id', 'DESC')
            ->limit(5)->get();

        return view('admin.user_management.user_view_profile', $data);
    }

    public function transaction($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user_management.transactions', compact('user'));
    }

    public function userTransactionSearch(Request $request, $id)
    {

        $basicControl = basicControl();
        $search = $request->search['value'];

        $filterTransactionId = $request->filterTransactionID;
        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;

        $transaction = Transaction::with('user')
            ->where('user_id', $id)
            ->when(!empty($search), function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('trx_id', 'LIKE', "%{$search}%")
                        ->orWhere('remarks', 'LIKE', "%{$search}%");
                });
            })
            ->when(!empty($request->filterDate) && $endDate == null, function ($query) use ($startDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $query->whereDate('created_at', $startDate);
            })
            ->when(!empty($request->filterDate) && $endDate != null, function ($query) use ($startDate, $endDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $endDate = Carbon::createFromFormat('d/m/Y', trim($endDate));
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->when(!empty($filterTransactionId), function ($query) use ($filterTransactionId) {
                return $query->where('trx_id', $filterTransactionId);
            })
            ->orderBy('id', 'DESC')
            ->get();


        return DataTables::of($transaction)
            ->addColumn('no', function () {
                static $counter = 0;
                $counter++;
                return $counter;
            })
            ->addColumn('trx', function ($item) {
                return $item->trx_id;
            })
            ->addColumn('amount', function ($item) {
                $statusClass = $item->trx_type == '+' ? 'text-success' : 'text-danger';
                return "<h6 class='mb-0 $statusClass '>" . $item->trx_type . ' ' . currencyPosition($item->amount) . "</h6>";

            })
            ->addColumn('charge', function ($item) {
                return currencyPosition($item->charge);

            })
            ->addColumn('remarks', function ($item) {
                return $item->remarks;
            })
            ->addColumn('date-time', function ($item) {
                return dateTime($item->created_at, 'd M Y h:i A');
            })
            ->rawColumns(['amount', 'charge'])
            ->make(true);
    }


    public function payment($id)
    {
        $data['user'] = User::findOrFail($id);
        $data['methods'] = Gateway::where('status', 1)->orderBy('sort_by', 'asc')->get();
        return view('admin.user_management.payment_log', $data);
    }

    public function userPaymentSearch(Request $request, $id)
    {

        $filterTransactionId = $request->filterTransactionID;
        $filterStatus = $request->filterStatus;
        $filterMethod = $request->filterMethod;
        $search = $request->search['value'];

        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;

        $funds = Deposit::with('user', 'gateway')
            ->where('user_id', $id)
            ->where('status', '!=', 0)
            ->when(empty($search) && empty($filterTransactionId) && empty($filterDate) && !isset($filterMethod) && empty($filterStatus),function ($query){
                $query->whereHas('user', function ($query) {
                    $query->select('id', 'username', 'firstname', 'lastname','image','image_driver');
                })
                    ->whereHas('gateway', function ($query) {
                        $query->select('id', 'name','image','driver');
                    })
                    ->orWhere(function ($query) {
                        $query->orWhereDoesntHave('gateway');
                    });
            })
            ->when(!empty($search), function ($query) use ($search) {
                return $query->where(function ($subquery) use ($search) {
                    $subquery->where('trx_id', 'LIKE', "%$search%")
                        ->orWhereHas('user', function ($q) use ($search) {
                            $q->where('firstname', 'LIKE', "%$search%")
                                ->orWhere('lastname', 'LIKE', "%{$search}%")
                                ->orWhere('username', 'LIKE', "%{$search}%");
                        });
                });
            })
            ->when(isset($filterStatus) && $filterStatus != 'all', function ($query) use ($filterStatus) {
                if ($filterStatus != 0) {
                    $query->where('status', $filterStatus);
                }else{
                    $query->where('status', $filterStatus);
                }
            })
            ->when(!empty($filterTransactionId), function ($query) use ($filterTransactionId) {
                return $query->where('trx_id', $filterTransactionId);
            })

            ->when(!empty($request->filterDate) && $endDate == null, function ($query) use ($startDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $query->whereDate('created_at', $startDate);
            })
            ->when(isset($filterMethod) && $filterMethod != 'all' , function ($query) use ($filterMethod) {
                return $query->where('payment_method_id',$filterMethod);
            })
            ->when(!empty($request->filterDate) && $endDate != null, function ($query) use ($startDate, $endDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $endDate = Carbon::createFromFormat('d/m/Y', trim($endDate));
                $query->whereBetween('created_at', [$startDate, $endDate]);
            });





        return DataTables::of($funds)
            ->addColumn('no', function ($item) {
                static $counter = 0;
                $counter++;
                return $counter;
            })
            ->addColumn('trx', function ($item) {
                return $item->trx_id;
            })
            ->addColumn('method', function ($item) {
                return '<a class="d-flex align-items-center me-2" href="javascript:void(0)">
                                <div class="flex-shrink-0">
                                  ' . $item->picture() . '
                                </div>
                                <div class="flex-grow-1 ms-3">
                                  <h5 class="text-hover-primary mb-0">' . optional($item->gateway)->name . '</h5>
                                </div>
                              </a>';
            })
            ->addColumn('amount', function ($item) {
                $statusClass = $item->getStatusClass();
                return "<h6 class='mb-0 $statusClass '>" . fractionNumber(getAmount($item->amount)) . ' ' . $item->payment_method_currency . "</h6>";
            })
            ->addColumn('charge', function ($item) {
                return "<span class='text-danger'>" . fractionNumber(getAmount($item->percentage_charge) + getAmount($item->fixed_charge)) . ' ' . $item->payment_method_currency . "</span>";
            })
            ->addColumn('payable', function ($item) {
                return "<h6>" . currencyPosition($item->payable_amount_in_base_currency) . "</h6>";
            })
            ->addColumn('status', function ($item) {
                if ($item->status == 0) {
                    return '<span class="badge bg-soft-warning text-warning">' . trans('Pending') . '</span>';
                } else if ($item->status == 1) {
                    return '<span class="badge bg-soft-success text-success">' . trans('Successful') . '</span>';
                } else if ($item->status == 2) {
                    return '<span class="badge bg-soft-warning text-warning">' . trans('Pending') . '</span>';
                } else if ($item->status == 3) {
                    return '<span class="badge bg-soft-danger text-danger">' . trans('Cancel') . '</span>';
                }
            })
            ->addColumn('date', function ($item) {
                return dateTime($item->created_at, 'd M Y h:i A');
            })
            ->addColumn('action', function ($item) {
                $details = null;
                if ($item->information) {
                    $details = [];
                    foreach ($item->information as $k => $v) {
                        if ($v->type == "file") {
                            $details[kebab2Title($k)] = [
                                'type' => $v->type,
                                'field_name' => $v->field_name,
                                'field_value' => getFile(config('filesystems.default'), $v->field_value),
                            ];
                        } else {
                            $details[kebab2Title($k)] = [
                                'type' => $v->type,
                                'field_name' => $v->field_name,
                                'field_value' => @$v->field_value ?? $v->field_name
                            ];
                        }
                    }
                }


                if (optional($item->gateway)->id > 999) {
                    $icon = $item->status == 2 ? 'pencil' : 'eye';
                    return "<button type='button' class='btn btn-white btn-sm edit_btn' data-bs-target='#accountInvoiceReceiptModal'
                data-detailsinfo='" . json_encode($details) . "'
                data-id='$item->id'
                data-feedback='$item->note'
                data-amount='" . fractionNumber(getAmount($item->amount)) .' ' . $item->payment_method_currency  . "'
                data-method='" . optional($item->gateway)->name . "'
                data-gatewayimage='" . getFile(optional($item->gateway)->driver, optional($item->gateway)->image) . "'
                data-datepaid='" . dateTime($item->created_at) . "'
                data-status='$item->status'
                data-username='" . optional($item->user)->username . "'
                data-action='" . route('admin.payment.action', $item->id) . "'
                data-bs-toggle='modal'
                data-bs-target='#accountInvoiceReceiptModal'>  <i class='bi-$icon fill me-1'></i> </button>";
                } else {
                    return '-';
                }

            })
            ->rawColumns(['method', 'amount', 'charge', 'payable', 'status', 'action'])
            ->make(true);
    }

    public function payout($id)
    {
        $data['user'] = User::findOrFail($id);
        $data['methods'] = PayoutMethod::where('is_active', 1)->orderBy('id', 'asc')->get();
        return view('admin.user_management.payout_log', $data);
    }

    public function userPayoutSearch(Request $request, $id)
    {

        $filterTransactionId = $request->filterTransactionID;
        $filterStatus = $request->filterStatus;
        $filterMethod = $request->filterMethod;
        $basicControl = basicControl();
        $search = $request->search['value'];

        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;

        $payout = Payout::with('user', 'method')->where('user_id', $id)
            ->where('status', '!=', 0)
            ->when(!empty($search), function ($query) use ($search) {
                return $query->where(function ($subquery) use ($search) {
                    $subquery->where('trx_id', 'LIKE', "%$search%")
                        ->orWhereHas('method', function ($q) use ($search) {
                            $q->where('name', 'LIKE', "%$search%");
                        });
                });
            })
            ->when(!empty($filterTransactionId), function ($query) use ($filterTransactionId) {
                return $query->where('trx_id', $filterTransactionId);
            })
            ->when(isset($filterStatus), function ($query) use ($filterStatus) {
                if ($filterStatus == "all") {
                    return $query->where('status', '!=', null);
                }
                return $query->where('status', $filterStatus);
            })
            ->when(isset($filterMethod), function ($query) use ($filterMethod) {
                return $query->whereHas('method', function ($subQuery) use ($filterMethod) {
                    if ($filterMethod == "all") {
                        $subQuery->where('id', '!=', null);
                    } else {
                        $subQuery->where('id', $filterMethod);
                    }
                });
            })
            ->when(!empty($request->filterDate) && $endDate == null, function ($query) use ($startDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $query->whereDate('created_at', $startDate);
            })
            ->when(!empty($request->filterDate) && $endDate != null, function ($query) use ($startDate, $endDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $endDate = Carbon::createFromFormat('d/m/Y', trim($endDate));
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->get();


        return DataTables::of($payout)
            ->addColumn('No', function ($item) {
                static $counter = 0;
                $counter++;
                return $counter;
            })
            ->addColumn('trx', function ($item) {
                return $item->trx_id;
            })
            ->addColumn('method', function ($item) {
                return '<a class="d-flex align-items-center me-2 cursor-unset" href="javascript:void(0)">
                                <div class="flex-shrink-0">
                                  ' . $item->picture() . '
                                </div>
                                <div class="flex-grow-1 ms-3">
                                  <h5 class="text-hover-primary mb-0">' . optional($item->method)->name . '</h5>
                                </div>
                              </a>';
            })
            ->addColumn('amount', function ($item) {
                $statusClass = $item->getStatusClass();
                return "<h6 class='mb-0 $statusClass '>" . fractionNumber(getAmount($item->amount)) . ' ' . $item->payout_currency_code . "</h6>";

            })
            ->addColumn('charge', function ($item) {
                return "<span class='text-danger'>" . getAmount($item->charge) . ' ' . $item->payout_currency_code . "</span>";
            })
            ->addColumn('net amount', function ($item) {
                return "<h6>" . currencyPosition(getAmount($item->amount_in_base_currency)) . "</h6>";
            })
            ->addColumn('status', function ($item) {
                if ($item->status == 1) {
                    return '<span class="badge bg-soft-warning text-warning">' . trans('Pending') . '</span>';
                } else if ($item->status == 2) {
                    return '<span class="badge bg-soft-success text-success">' . trans('Successful') . '</span>';
                } else if ($item->status == 3) {
                    return '<span class="badge bg-soft-danger text-danger">' . trans('Cancel') . '</span>';
                }
            })
            ->addColumn('date', function ($item) {
                return dateTime($item->created_at, 'd M Y h:i A');
            })
            ->addColumn('action', function ($item) use ($basicControl) {

                $details = null;
                if ($item->information) {
                    $details = [];
                    foreach ($item->information as $k => $v) {
                        if ($v->type == "file") {
                            $details[kebab2Title($k)] = [
                                'type' => $v->type,
                                'field_name' => $v->field_name,
                                'field_value' => getFile(config('filesystems.default'), @$v->field_value ?? $v->field_name),
                            ];
                        } else {
                            $details[kebab2Title($k)] = [
                                'type' => $v->type,
                                'field_name' => $v->field_name,
                                'field_value' => @$v->field_value ?? $v->field_name
                            ];
                        }
                    }
                }

                $icon = $item->status == 1 ? 'pencil' : 'eye';

                $statusColor = '';
                $statusText = '';
                if ($item->status == 0) {
                    $statusColor = 'badge bg-soft-warning text-warning';
                    $statusText = 'Pending';
                } else if ($item->status == 1) {
                    $statusColor = 'badge bg-soft-warning text-warning';
                    $statusText = 'Pending';
                } else if ($item->status == 2) {
                    $statusColor = 'badge bg-soft-success text-success';
                    $statusText = 'Success';
                } else if ($item->status == 3) {
                    $statusColor = 'badge bg-soft-danger text-danger';
                    $statusText = 'Cancel';
                }

                return "<button type='button' class='btn btn-white btn-sm edit_btn'
                data-id='$item->id'
                data-info='" . json_encode($details) . "'
                data-sendername='" . $item->user->firstname . ' ' . $item->user->lastname . "'
                data-transactionid='$item->trx_id'
                data-feedback='$item->feedback'
                data-amount=' " . currencyPosition(getAmount($item->amount)) . "'
                data-method='" . optional($item->method)->name . "'
                data-gatewayimage='" . getFile(optional($item->method)->driver, optional($item->method)->image) . "'
                data-datepaid='" . dateTime($item->created_at, 'd M Y') . "'
                data-status='$item->status'

                 data-status_color='$statusColor'
                data-status_text='$statusText'
                data-username='" . optional($item->user)->username . "'
                data-action='" . route('admin.payout.action', $item->id) . "'
                data-bs-toggle='modal'
                data-bs-target='#accountInvoiceReceiptModal'>  <i class='bi-$icon fill me-1'></i> </button>";
            })
            ->rawColumns(['method', 'amount', 'charge', 'net amount', 'status', 'action'])
            ->make(true);
    }

    public function userKyc($id)
    {
        try {
            $data['user'] = User::where('id', $id)->firstOr(function () {
                throw new Exception('No User found.');
            });
            return view('admin.user_management.user_kyc', $data);
        } catch (Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function KycSearch(Request $request, $id)
    {
        $filterVerificationType = $request->filterVerificationType;
        $filterStatus = $request->filterStatus;

        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;

        $transaction = UserKyc::with('user')
            ->where('user_id', $id)
            ->orderBy('id', 'DESC')
            ->when(!empty($filterVerificationType), function ($query) use ($filterVerificationType) {
                return $query->where('kyc_type', $filterVerificationType);
            })
            ->when(isset($filterStatus), function ($query) use ($filterStatus) {
                if ($filterStatus == "all") {
                    return $query->where('status', '!=', null);
                }
                return $query->where('status', $filterStatus);
            })
            ->when(!empty($request->filterDate) && $endDate == null, function ($query) use ($startDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $query->whereDate('created_at', $startDate);
            })
            ->when(!empty($request->filterDate) && $endDate != null, function ($query) use ($startDate, $endDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $endDate = Carbon::createFromFormat('d/m/Y', trim($endDate));
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->get();

        return DataTables::of($transaction)
            ->addColumn('no', function () {
                static $counter = 0;
                $counter++;
                return $counter;
            })
            ->addColumn('verification type', function ($item) {
                return $item->kyc_type;

            })
            ->addColumn('status', function ($item) {
                if ($item->status == 0) {
                    return '<span class="badge bg-soft-warning text-warning">' . trans('Pending') . '</span>';
                } else if ($item->status == 1) {
                    return '<span class="badge bg-soft-success text-success">' . trans('Verified') . '</span>';
                } else if ($item->status == 2) {
                    return '<span class="badge bg-soft-danger text-danger">' . trans('Rejected') . '</span>';
                }
            })
            ->addColumn('date', function ($item) {
                return dateTime($item->created_at);

            })
            ->addColumn('action', function ($item) {
                $url = route('admin.kyc.view', $item->id);
                return '<a href="' . $url . '" class="btn btn-white btn-sm">
                    <i class="bi-eye-fill me-1"></i>
                  </a>';
            })
            ->rawColumns(['name', 'status', 'action'])
            ->make(true);
    }


    public function loginAsUser($id)
    {
        Auth::guard('web')->loginUsingId($id);
        return redirect()->route('user.dashboard');
    }


    public function blockProfile(Request $request, $id)
    {
        try {
            $user = User::where('id', $id)->firstOr(function () {
                throw new \Exception('No User found.');
            });

            $user->update([
                'status' => 0
            ]);

            return back()->with('success', 'Block Profile Successfully');
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function mailAllUser()
    {
        return view('admin.user_management.mail_all_user');
    }

    public function sendEmail($id)
    {
        try {
            $user = User::where('id', $id)->firstOr(function () {
                throw new \Exception('No User found.');
            });
            return view('admin.user_management.send_mail_form', compact('user'));
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function sendMailUser(Request $request, $id = null)
    {

        $request->validate([
            'subject' => 'required|min:5',
            'description' => 'required|min:10',
        ]);

        try {

            $user = User::where('id', $id)->first();

            $subject = $request->subject;
            $template = $request->description;

            if (isset($user)) {
                $this->mail($user, null, [], $subject, $template);
            } else {
                $users = User::all();
                foreach ($users as $user) {
                    $this->mail($user, null, [], $subject, $template);
                }
            }

            return back()->with('success', 'Email Sent Successfully');

        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function investors()
    {
        return view('admin.investor.index');
    }

    public function getInvestors(Request $request)
    {

        $search =  $request->search['value'];
        $investors = User::query()->where('total_invest' , '>', 1)
            ->where('status', 1)
            ->when(!empty($search), function ($query) use ($search) {
                return $query->where('email', 'LIKE', "%{$search}%")
                    ->orWhere('username', 'LIKE', "%{$search}%")
                    ->orWhere('firstname', 'LIKE', "%{$search}%")
                    ->orWhere('lastname', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
            })
            ->orderBy('created_at', 'DESC');

        return DataTables::of($investors)
            ->addColumn('investor', function ($item) {
                return $item->getUser();
            })
            ->addColumn('email', function ($item) {
                return '<span class="d-block h5 mb-0">' . $item->email . '</span>
                            <span class="d-block fs-5">' . $item->phone . '</span>';
            })
            ->addColumn('balance', function ($item) {
                return currencyPosition($item->balance);
            })
            ->addColumn('profit_balance', function ($item) {
                return currencyPosition($item->profit_balance);
            })
            ->addColumn('total_invest',function ($item){
                return currencyPosition($item->total_invest);
            })
            ->addColumn('status',function ($item){
                if ($item->status == 1) {
                    return '<span class="badge bg-soft-success text-success">
                    <span class="legend-indicator bg-success"></span>' . trans('Active') . '
                  </span>';

                } else {
                    return '<span class="badge bg-soft-danger text-danger">
                    <span class="legend-indicator bg-danger"></span>' . trans('Inactive') . '
                  </span>';
                }
            })
            ->addColumn('last_login', function ($item) {
                return diffForHumans($item->last_login);
            })
            ->rawColumns(['investor', 'email', 'balance','profit_balance', 'total_invest', 'status', 'last_login'])
            ->make(true);
    }

    public function planInvestHistory($id)
    {
        try {
            $data['user'] = User::where('id', $id)->firstOr(function () {
                throw new Exception('No User found.');
            });
            return view('admin.user_management.user_plan_invest_history', $data);
        } catch (Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function planInvestHistorySearch(Request $request , $id)
    {
        $investHistory = InvestHistory::query()
            ->with(['user','plan'])
            ->where('user_id',$id)
            ->when(!empty($request->search['value']) , function ($query) use ($request) {
                $query->whereHas('plan', function ($query) use ($request) {
                    $query->where('plan_name','LIKE', '%'.$request->search['value'].'%')
                        ->orWhere('trx',$request->search['value']);
                })
                    ->orWhereHas('user', function ($query) use ($request) {
                        $query->where('firstname', 'LIKE', '%'.$request->search['value'].'%')
                            ->orWhere('lastname', 'LIKE', '%'.$request->search['value'].'%')
                            ->orWhereRaw("CONCAT(firstname, ' ', lastname) LIKE ?", [$request->search['value']])
                            ->orWhere('username','LIKE','%'.$request->search['value'].'%');
                    });
            })
            ->orderBy('created_at', 'desc');

        return DataTables::of($investHistory)
            ->addColumn('plan', function ($item) {
                return $item->getPlan();
            })
            ->addColumn('return', function ($item) {
                return currencyPosition($item->profit);
            })
            ->addColumn('return_period', function ($item) {
                return $item->returnPeriod();
            })
            ->addColumn('received_amount', function ($item) {
                return $item->receivedAmount();
            })
            ->addColumn('last_payment', function ($item) {
                return $item->lastPayment();
            })
            ->addColumn('next_payment', function ($item) {
                return $item->nextPayment();
            })
            ->rawColumns([ 'name', 'plan', 'return','return_period','received_amount','last_payment','next_payment'])
            ->make(true);
    }

    public function projectInvestHistory($id)
    {
        try {
            $data['user'] = User::where('id', $id)->firstOr(function () {
                throw new Exception('No User found.');
            });
            return view('admin.user_management.user_project_invest_history', $data);
        } catch (Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function projectInvestHistorySearch(Request $request , $id)
    {
        $invest = ProjectInvestment::query()->with(['project.details','user'])
            ->where('payment_status',1)
            ->where('user_id',$id)
            ->when(!empty($request->search['value']),function($q) use ($request){
                $q->whereHas('project.details', function($q2) use ($request){
                    $q2->where('title', 'LIKE', '%'.$request->search['value'].'%');
                })

                    ->orWhereHas('user', function($q3) use ($request){
                        $q3->where('firstname', 'LIKE', '%'.$request->search['value'].'%')
                            ->orWhere('lastname', 'LIKE', '%'.$request->search['value'].'%')
                            ->orWhereRaw("CONCAT(firstname, ' ', lastname) LIKE ?", [$request->search['value']])
                            ->orWhere('username','LIKE','%'.$request->search['value'].'%');
                    })
                    ->orWhere('trx',$request->search['value']);
            })
            ->orderBy('created_at','DESC');

        return DataTables::of($invest)
            ->addColumn('project', function ($item) {
                return $item->getProject();
            })
            ->addColumn('unit', function ($item) {
                return "<span class='badge bg-secondary'>".$item->unit."</span>";
            })
            ->addColumn('invest_per_unit', function ($item) {
                return currencyPosition($item->per_unit_price);
            })
            ->addColumn('return',function($item){
                return currencyPosition($item->return);
            })
            ->addColumn('return_period',function($item){
                return 'Every '. $item->return_period.' '.$item->return_period_type;
            })
            ->addColumn('received_amount',function($item){
                return $item->receivedAmount();
            })
            ->addColumn('last_payment',function ($item){
                return $item->lastPayment();
            })
            ->addColumn('next_payment',function ($item){
                return $item->nextPayment();
            })
            ->rawColumns(['investor','project','unit','invest_per_unit','return','return_period','received_amount','last_payment','next_payment',])
            ->make(true);
    }


}
