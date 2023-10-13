<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'package_id' => $this->id,
            'package_name' => $this->name,
            'start_limit' => $this->start_limit,
            'end_limit' => $this->end_limit,
            'price' => $this->price
        ];
    }
}
