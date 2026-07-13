<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'section_id' => $this->sectionId,
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'order' => $this->order,
            'nodes_count' => $this->whenCounted('nodes'),
            'section' => new SectionResource($this->whenLoaded('section')),
            'nodes' => NodeResource::collection($this->whenLoaded('nodes')),
        ];
    }
}
