<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NodeResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'unit_id' => $this->unitId,
            'title' => $this->title,
            'type' => $this->type,
            'order' => $this->order,
            'questions_count' => $this->whenCounted('questions'),
            'unit' => new UnitResource($this->whenLoaded('unit')),
            'questions' => QuestionResource::collection($this->whenLoaded('questions')),
        ];
    }
}
