<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NodeStepResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'node_id' => $this->node_id,
            'type' => $this->type,
            'content' => $this->content,
            'character_symbol' => $this->character_symbol,
            'character_pronunciation' => $this->character_pronunciation,
            'quiz_instruction' => $this->quiz_instruction,
            'quiz_query' => $this->quiz_query,
            'quiz_options' => $this->quiz_options,
            'quiz_correct_index' => $this->quiz_correct_index,
            'sort_order' => $this->sort_order,
        ];
    }
}
