<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvestHistory;
use App\Models\InvestmentPlan;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Traits\Upload;

class InvestmentPlanController extends Controller
{
    use Upload;

    public function index(): View
    {
        return view('admin.investment_plan.index');
    }

    public function list(Request $request)
    {
        $investmentPlans = InvestmentPlan::query()
            ->where('soft_delete', 0)
            ->when(!empty($request->search['value']), function ($q) use ($request) {
                $q->where('plan_name', 'LIKE', '%' . $request->search['value'] . '%');
            });
        return DataTables::of($investmentPlans)
            ->addColumn('sl', function () {
                static $sl = 0;
                return ++$sl;
            })
            ->addColumn('name', function ($item) {
                return $item->plan_name;
            })
            ->addColumn('price', function ($item) {
                return $item->investAmount();
            })
            ->addColumn('period', function ($item) {
                return $item->getAdminPlanPeriod();
            })
            ->addColumn('status', function ($item) {
                return $item->getPlanStatus();
            })
            ->addColumn('return_period', function ($item) {
                return 'Every ' . $item->getReturnPeriod();
            })
            ->addColumn('profit', function ($item) {
                return $item->getProfit();
            })
            ->addColumn('capital_back', function ($item) {
                return $item->getCapitalBack();
            })
            ->addColumn('action', function ($item) {
                return '<div class="btn-group" role="group">
                    <a class="btn btn-white btn-sm" href="' . route('admin.investment.plan.edit', $item->id) . '">
                      <i class="bi-pencil-fill me-1"></i> Edit
                    </a>

                    <!-- Button Group -->
                    <div class="btn-group">
                      <button type="button" class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty" id="productsEditDropdown1" data-bs-toggle="dropdown" aria-expanded="false"></button>

                      <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="productsEditDropdown1" style="">
                        <a class="dropdown-item deleteBtn" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#deleteModal" data-route="' . route('admin.investment.plan.destroy', $item->id) . '">
                          <i class="bi-trash dropdown-item-icon"></i> Delete
                        </a>
                      </div>
                    </div>
                    <!-- End Button Group -->
                  </div>';
            })
            ->rawColumns(['sl', 'name', 'price', 'period', 'status', 'return_period', 'capital_back', 'action'])
            ->make(true);
    }

    public function create(): View
    {
        return view('admin.investment_plan.create');
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'plan_name' => 'required|string',
            'plan_price' => ['required_if:has_amount_fixed,1', function ($attribute, $value, $fail) use ($request) {
                if ($request->has_amount_fixed == 1 && !is_numeric($value)) {
                    $fail('Minimum invest field must be a number');
                }
            }],
            'minimum_invest' => ['required_if:has_amount_fixed,0', function ($attribute, $value, $fail) use ($request) {
                if ($request->has_amount_fixed == 0 && !is_numeric($value)) {
                    $fail('Minimum invest field must be a number');
                }
            }],
            'maximum_invest' => ['required_if:has_amount_fixed,0', function ($attribute, $value, $fail) use ($request) {
                if ($request->has_amount_fixed == 0 && !is_numeric($value)) {
                    $fail('Maximum invest field must be a number');
                }
            }],
            'plan_period' => ['required_if:unlimited_period,0', function ($attribute, $value, $fail) use ($request) {
                if ($request->unlimited_period == 0 && !is_numeric($value)) {
                    $fail('Plan Period field must be a number');
                }
            }],
            'unlimited_period' => ['required', 'in:0,1'],
            'plan_period_type' => ['required', 'string', 'in:Month,Year,Day'],
            'return_period' => ['required', 'numeric'],
            'return_period_type' => 'required|in:Month,Year,Day,Hour',
            'profit' => ['required', 'numeric'],
            'profit_type' => ['required', 'string', 'in:Fixed,Percentage'],
            'status' => ['required', 'numeric', 'in:1,0'],
            'number_of_return' => ['required_if:number_of_return_type,0', function ($attribute, $value, $fail) use ($request) {
                if ($request->number_of_return_type == 0 && !is_numeric($value)) {
                    $fail('Plan Period field must be a number');
                }
            }],
            'capital_back' => ['required', 'in:1,0', function ($attribute, $value, $fail) use ($request) {
                if ($request->number_of_return_type == 1 && $value) {
                    $fail('Capital Back Cannot on if return type is lifetime');
                }
            }],
            'image' => ['nullable', 'mimes:jpg,jpeg,png', 'max:3000', 'image'],
            'maturity' => 'required|numeric'
        ]);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $uploadImage = $this->fileUpload($image, config('filelocation.plan.path'), null, null, 'webp', 60);
            if ($uploadImage) {
                $img = $uploadImage['path'];
                $driver = $uploadImage['driver'];
            } else {
                return back()->withErrors(['image' => 'Image Upload Failed']);
            }
        }
        try {
            $investmentPlan = new InvestmentPlan();
            $investmentPlan->plan_name = $data['plan_name'];
            if ($request->has_amount_fixed == 1) {
                $investmentPlan->plan_price = $data['plan_price'];
            }
            if ($request->has_amount_fixed == 0) {
                $investmentPlan->min_invest = $data['minimum_invest'];
                $investmentPlan->max_invest = $data['maximum_invest'];
            }
            $investmentPlan->return_typ_has_lifetime = $request->number_of_return_type;
            $investmentPlan->amount_has_fixed = $request->has_amount_fixed;

            $investmentPlan->return_period = $data['return_period'];
            $investmentPlan->return_period_type = $data['return_period_type'];
            if ($request->number_of_return_type == 0) {
                $investmentPlan->number_of_profit_return = $data['number_of_return'];
            }
            if ($request->unlimited_period == 0) {
                $investmentPlan->plan_period = $data['plan_period'];
                $investmentPlan->plan_period_type = $data['plan_period_type'];
            } else {
                $investmentPlan->unlimited_period = $request->unlimited_period;
            }
            $investmentPlan->profit = $data['profit'];
            $investmentPlan->profit_type = $data['profit_type'];
            $investmentPlan->capital_back = $data['capital_back'];
            $investmentPlan->status = $data['status'];
            $investmentPlan->image = $img ?? null;
            $investmentPlan->driver = $driver ?? null;
            $investmentPlan->maturity = $data['maturity'];
            $investmentPlan->save();
            return redirect()->route('admin.investment.plan.index')->with('success', 'Investment plan has been created successfully.');
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function edit(InvestmentPlan $investmentPlan)
    {
        return view('admin.investment_plan.edit', compact('investmentPlan'));
    }

    public function update(Request $request, InvestmentPlan $investmentPlan)
    {
        $data = $request->validate([
            'plan_name' => 'required | string',
            'plan_price' => ['required_if:has_amount_fixed,1', function ($attribute, $value, $fail) use ($request) {
                if ($request->has_amount_fixed == 1 && !is_numeric($value)) {
                    $fail('Minimum invest field must be a number');
                }
            }],
            'minimum_invest' => ['required_if:has_amount_fixed,0', function ($attribute, $value, $fail) use ($request) {
                if ($request->has_amount_fixed == 0 && !is_numeric($value)) {
                    $fail('Minimum invest field must be a number');
                }
            }],
            'maximum_invest' => ['required_if:has_amount_fixed,0', function ($attribute, $value, $fail) use ($request) {
                if ($request->has_amount_fixed == 0 && !is_numeric($value)) {
                    $fail('Maximum invest field must be a number');
                }
            }],
            'plan_period' => ['required_if:unlimited_period,0', function ($attribute, $value, $fail) use ($request) {
                if ($request->unlimited_period == 0 && !is_numeric($value)) {
                    $fail('Plan Period field must be a number');
                }
            }],
            'unlimited_period' => ['required', 'in:0,1'],
            'plan_period_type' => ['required', 'string', 'in:Month,Year,Day'],
            'return_period' => ['required', 'numeric'],
            'return_period_type' => ['required', 'string', 'in:Month,Year,Day,Hour'],
            'profit' => ['required', 'numeric'],
            'profit_type' => ['required', 'string', 'in:Fixed,Percentage'],
            'status' => ['required', 'numeric', 'in:1,0'],
            'number_of_return' => ['required_if:number_of_return_type,0', function ($attribute, $value, $fail) use ($request) {
                if ($request->number_of_return_type == 0 && !is_numeric($value)) {
                    $fail('Plan Period field must be a number');
                }
            }],
            'capital_back' => ['required', 'in:1,0', function ($attribute, $value, $fail) use ($request) {
                if ($request->number_of_return_type == 1 && $value) {
                    $fail('Capital Back Cannot on if return type is lifetime');
                }
            }],
            'image' => ['nullable', 'mimes:jpg,jpeg,png', 'max:3000', 'image'],
            'maturity' => 'required|numeric'
        ]);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $uploadImage = $this->fileUpload($image, config('filelocation.plan.path'), null, null, 'webp', 60, $investmentPlan->image, $investmentPlan->driver);
            if ($uploadImage) {
                $img = $uploadImage['path'];
                $driver = $uploadImage['driver'];
            } else {
                return back()->withErrors(['image' => 'Image Upload Failed']);
            }
        }

        try {
            $investmentPlan->plan_name = $data['plan_name'];
            if ($request->has_amount_fixed == 1) {
                $investmentPlan->plan_price = $data['plan_price'];
            }
            if ($request->has_amount_fixed == 0) {
                $investmentPlan->min_invest = $data['minimum_invest'];
                $investmentPlan->max_invest = $data['maximum_invest'];
            }
            $investmentPlan->return_typ_has_lifetime = $request->number_of_return_type;
            $investmentPlan->amount_has_fixed = $request->has_amount_fixed;

            $investmentPlan->return_period = $data['return_period'];
            $investmentPlan->return_period_type = $data['return_period_type'];
            if ($request->number_of_return_type == 0) {
                $investmentPlan->number_of_profit_return = $data['number_of_return'];
            }
            if ($request->unlimited_period == 0) {
                $investmentPlan->plan_period = $data['plan_period'];
                $investmentPlan->plan_period_type = $data['plan_period_type'];
            } else {
                $investmentPlan->unlimited_period = $request->unlimited_period;
            }
            $investmentPlan->profit = $data['profit'];
            $investmentPlan->profit_type = $data['profit_type'];
            $investmentPlan->capital_back = $data['capital_back'];
            $investmentPlan->status = $data['status'];
            $investmentPlan->image = $img ?? $investmentPlan->image;
            $investmentPlan->driver = $driver ?? $investmentPlan->driver;
            $investmentPlan->maturity = $data['maturity'];
            $investmentPlan->update();
            return redirect()->route('admin.investment.plan.index')->with('success', 'Investment plan has been updated successfully.');
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function delete(InvestmentPlan $investmentPlan)
    {
        if ($investmentPlan->investment->first()) {
            $investmentPlan->soft_delete = 1;
            $investmentPlan->update();
        } else {
            $investmentPlan->delete();
        }

        return back()->with('success', 'Investment plan has been deleted successfully.');
    }

    public function investHistory()
    {
        return view('admin.plan_invest_history.index');
    }


    public function getInvestPlanHistory(Request $request)
    {
        $investHistory = InvestHistory::query()
            ->with(['user', 'plan'])
            ->when(!empty($request->search['value']), function ($query) use ($request) {
                $query->whereHas('plan', function ($query) use ($request) {
                    $query->where('plan_name', 'LIKE', '%' . $request->search['value'] . '%')
                            ->orWhere('trx',$request->search['value']);
                })
                    ->orWhereHas('user', function ($query) use ($request) {
                        $query->where('firstname', 'LIKE', '%' . $request->search['value'] . '%')
                            ->orWhere('lastname', 'LIKE', '%' . $request->search['value'] . '%')
                            ->orWhereRaw("CONCAT(firstname, ' ', lastname) LIKE ?", [$request->search['value']])
                            ->orWhere('username', 'LIKE', '%' . $request->search['value'] . '%');
                    });
            })
            ->orderBy('created_at', 'desc');

        return DataTables::of($investHistory)
            ->addColumn('name', function ($item) {
                return $item->getUser();
            })
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
            ->rawColumns(['name', 'plan', 'return', 'return_period', 'received_amount', 'last_payment', 'next_payment'])
            ->make(true);
    }

}
