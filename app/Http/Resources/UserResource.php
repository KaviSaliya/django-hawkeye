<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'username' => $this->username,
            'email' => $this->email,
            'language_id' => $this->language_id,
            'address' => $this->address_one,
            'phone' => $this->phone,
            'phone_code' => $this->phone_code,
            'country' => $this->country,
            'country_code' => $this->country_code,
            'image' => getFile($this->image_driver, $this->image)
        ];
    }
}
