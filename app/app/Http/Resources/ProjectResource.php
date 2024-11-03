<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'location' => $this->location,
            'total_units' => $this->total_units,
            'project_duration' => $this->project_duration,
            'project_duration_type' => $this->project_duration_type,
            'return' => $this->return,
            'return_type' => $this->return_type,
            'return_period' => $this->return_period,
            'return_period_type' => $this->return_period_type,
            'number_of_return' => $this->number_of_return,
            'minimum_invest' => $this->minimum_invest,
            'maximum_invest' => $this->maximum_invest,
            'fixed_invest' => $this->fixed_invest,
            'thumbnail_image' => getFile($this->thumbnail_image_driver,$this->thumbnail_image),
            'images' => $this->getImages(),
            'start_date' => $this->start_date,
            'expiry_date' => $this->expiry_date,
            'amount_has_fixed' => $this->amount_has_fixed,
            'project_duration_has_unlimited' => $this->project_duration_has_unlimited,
            'number_of_return_has_unlimited' => $this->number_of_return_has_unlimited,
            'status' => $this->status,
            'available_units' => $this->available_units,
            'maturity' => $this->maturity,
            'capital_back' => $this->capital_back,
            'invest_last_date' => $this->invest_last_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'details' => $this->getDetails()
        ];
    }
}
