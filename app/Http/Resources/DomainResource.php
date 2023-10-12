<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DomainResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'domain_id' => $this->id,
            'domain' => $this->domain_name,
            'status' => $this->status,
            'date_last_crawled' => $this->date_last_crawled,
            'no_of_items' => $this->no_of_items,
            'user' => $this->whenLoaded('user', function () {
                return new UserResource($this->user);
            }),
            'items' => $this->whenLoaded('items', function () {
                return DomainItemResource::collection($this->items);
            })
        ];
    }
}
