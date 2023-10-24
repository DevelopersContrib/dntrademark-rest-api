<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DomainCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'item_id' => $this->id,
            'keyword' => $this->keyword,
            'registration_number' => $this->registration_number,
            'serial_number' => $this->serial_number,
            'status_label' => $this->status_label,
            'status_date' => $this->status_date,
            'status_definition' => $this->status_definition,
            'filing_date' => $this->filing_date,
            'registration_date' => $this->registration_date,
            'abandonment_date' => $this->abandonment_date,
            'expiration_date' => $this->expiration_date,
            'domain' => $this->whenLoaded('domain', function () {
                return new DomainResource($this->domain);
            })
        ];
    }
}
