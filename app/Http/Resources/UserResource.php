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
            'user_id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'package_id' => $this->package_id,
            'is_admin' => $this->is_admin,
            'is_onboarding' => $this->is_onboarding,
            'package' => $this->whenLoaded('package', function () {
                return new PackageResource($this->package);
            }),
            'allow_email' => $this->allow_email,
            'allow_sms' => $this->allow_sms,
            'sms_number' => $this->sms_number,
            'sms_code' => $this->sms_code
        ];
    }
}
