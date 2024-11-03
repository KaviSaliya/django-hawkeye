<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayoutMethodResource extends JsonResource
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
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'bank_name' => $this->bank_name,
            'banks' => $this->banks,
            'parameters' => $this->parameters,
            'extra_parameters' => $this->extra_parameters,
            'inputForm' => $this->inputForm,
            'currency_lists' => $this->currency_lists,
            'supported_currency' => $this->supported_currency,
            'payout_currencies' => $this->payout_currencies,
            'is_active' => $this->is_active,
            'is_automatic' => $this->is_automatic,
            'is_sandbox' => $this->is_sandbox,
            'environment' => $this->environment,
            'confirm_payout' => $this->confirm_payout,
            'is_auto_update' => $this->is_auto_update,
            'currency_type' => $this->currency_type,
            'logo' => getFile($this->driver,$this->logo)
         ];
    }
}
