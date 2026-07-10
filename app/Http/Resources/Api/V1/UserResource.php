<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'role' => $this->role,
            'bio' => $this->bio,
            'avatar' => $this->avatar,
            'streak' => $this->streak,
            'total_xp' => $this->totalXP,
            'gems' => $this->gems,
            'league' => $this->league,
        ];
    }
}
