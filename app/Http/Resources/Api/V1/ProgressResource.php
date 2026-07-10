<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgressResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'node_id' => $this->nodeId,
            'status' => $this->status,
            'completed_at' => $this->updated_at,
            'node' => new NodeResource($this->whenLoaded('node')),
        ];
    }
}
