<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $date = Carbon::parse($this->created_at);
        return [
            'status' => $this->type,
            'message' => $this->message,
            'url' => $this->url,
            'date_sent' => $date->format('M j, Y')
        ];
    }
}
