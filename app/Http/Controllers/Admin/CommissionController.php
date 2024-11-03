<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BasicControl;
use App\Models\Referral;
use App\Models\ReferralBonus;
use hisorange\BrowserDetect\Exceptions\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class CommissionController extends Controller
{

    public function index()
    {
        return view('admin.commission.commission');
    }

    public function getCommissionList(Request $request)
    {
        $commissions = ReferralBonus::query()->with(['user','bonusBy'])
            ->when(!empty($request->search['value']),function ($query) use ($request){
                $query->whereHas('bonusBy',function ($query) use ($request){
                    $query->where('firstname', 'LIKE', '%'.$request->search['value'].'%')
                        ->orWhere('lastname', 'LIKE', '%'.$request->search['value'].'%')
                        ->orWhereRaw("CONCAT(firstname, ' ', lastname) LIKE ?", [$request->search['value']])
                        ->orWhere('username','LIKE','%'.$request->search['value'].'%');
                })
                    ->orWhereHas('user',function ($query) use ($request){
                        $query->where('firstname', 'LIKE', '%'.$request->search['value'].'%')
                            ->orWhere('lastname', 'LIKE', '%'.$request->search['value'].'%')
                            ->orWhereRaw("CONCAT(firstname, ' ', lastname) LIKE ?", [$request->search['value']])
                            ->orWhere('username','LIKE','%'.$request->search['value'].'%');
                    });
            })
            ->orderBy('created_at', 'desc');


        return DataTables::of($commissions)
            ->addColumn('sl', function ($commissions) {
                static $counter = 0;
                return ++$counter;
            })
            ->addColumn('user', function ($commissions) {
                return $commissions->getUser();
            })
            ->addColumn('bonus_by', function ($commissions) {
                return $commissions->getBonsuByUser();
            })
            ->addColumn('amount', function ($commission) {
               return currencyPosition($commission->amount);
            })
            ->addColumn('remarks', function ($commission) {
                return $commission->remarks;
            })
            ->addColumn('date', function ($commission) {
                return dateTime($commission->created_at);
            })
            ->rawColumns(['sl', 'user', 'bonus_by', 'amount', 'remarks', 'date'])
            ->make(true);
    }


    public function referral()
    {
        $data['referrals'] = Referral::get();
        return view('admin.commission.referral',$data);
    }


    public function commissionStatus(Request $request)
    {
        $basic = BasicControl::firstOrNew();
        $basic->deposit_commission = $request->deposit_commission;
        $basic->investment_commission = $request->investment_commission;
        $basic->profit_commission = $request->profit_commission;
        $basic->save();
        return back()->with('success', 'Update Successfully.');

    }

    public function StoreCommission(Request $request)
    {

       $request->validate([
        'level.*' => 'required|integer|min:1',
        'commission.*' => 'required|numeric',
        'commission_type' => 'required',
        'amount_type.*' => 'required'
    ]);

       if (!isset($request->commission) || !isset($request->level) || !isset($request->amount_type)) {
           return back()->with('error', 'Please fill all the required fields.');
       }

        try {

            Referral::where('commission_type', $request->commission_type)->delete();

            for ($i = 0; $i < count($request->level); $i++) {
                if ($request->commission[$i] && $request->amount_type[$i]) {
                    $referral = new Referral();
                    $referral->commission_type = $request->commission_type;
                    $referral->level = $request->level[$i];
                    $referral->commission = $request->commission[$i];
                    $referral->amount_type = $request->amount_type[$i];
                    $referral->save();
                }else{
                    return back()->with('error', 'Please enter commission or select amount type value');
                }
            }

            return back()->with('success', 'Level Bonus Has been Updated.');
        }catch (\Exception $exception){
           return back()->with('error', $exception->getMessage());
        }

    }

}
