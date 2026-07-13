<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'units_count' => $this->whenCounted('units'),
            'stories_count' => $this->whenCounted('stories'),
            'units' => UnitResource::collection($this->whenLoaded('units')),
            'stories' => StoryResource::collection($this->whenLoaded('stories')),
        ];
    }
}
