<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectInvestResource extends JsonResource
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
            'user_id' => $this->user_id,
            'project_id' => $this->project_id,
            'project_title' => optional(optional($this->project)->details)->title,
            'project_slug' => optional(optional($this->project)->details)->slug,
            'per_unit_price' => $this->per_unit_price,
            'unit' => $this->unit,
            'return' => $this->return,
            'number_of_return' => $this->number_of_return,
            'is_life_time' => $this->is_life_time,
            'return_period' => $this->return_period,
            'return_period_type' => $this->return_period_type,
            'next_return' => $this->next_return,
            'capital_back' => $this->capital_back,
            'project_expiry_date' => $this->project_expiry_date,
            'status' => $this->status,
            'project_period_is_lifetime' => $this->project_period_is_lifetime,
            'last_return' => $this->last_return,
            'total_return' => $this->total_return,
            'trx' => $this->trx,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
