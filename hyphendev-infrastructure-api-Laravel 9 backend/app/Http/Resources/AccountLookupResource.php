<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountLookupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'service' => $this->resource['service'],
            'name' => $this->resource['name'] ?? '',
            'email' => $this->resource['email'] ?? '',
            'status' => $this->resource['status'] ?? '',
            'error' => $this->resource['error'] ?? '',
        ];
    }
}
