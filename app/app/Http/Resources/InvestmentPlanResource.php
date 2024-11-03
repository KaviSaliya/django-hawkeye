<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvestmentPlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'plan_name' => $this->plan_name,
            'plan_price' => $this->plan_price,
            'plan_period' => $this->plan_period,
            'plan_period_type' => $this->plan_period_type,
            'min_invest' => $this->min_invest,
            'max_invest' => $this->max_invest,
            'return_typ_has_lifetime' => $this->return_typ_has_lifetime,
            'amount_has_fixed' => $this->amount_has_fixed,
            'return_period' => $this->return_period,
            'return_period_type' => $this->return_period_type,
            'unlimited_period' => $this->unlimited_period,
            'number_of_profit_return' => $this->number_of_profit_return,
            'profit' => $this->profit,
            'profit_type' => $this->profit_type,
            'capital_back' => $this->capital_back,
            'maturity' => $this->maturity,
            'status' => $this->status,
            'image' => getFile($this->driver,$this->image),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
