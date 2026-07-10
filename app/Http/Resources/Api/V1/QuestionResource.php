<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'node_id' => $this->nodeId,
            'type' => $this->type,
            'question' => $this->question,
            'options' => $this->options,
            'related_characters' => $this->relatedCharacters,
            'order' => $this->order,
        ];
    }
}
