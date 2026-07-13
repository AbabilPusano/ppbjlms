<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ProgressResource;
use App\Models\Node;
use App\Models\UserProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProgressController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $progress = $request->user()
            ->progress()
            ->with('node.unit.section')
            ->latest('updated_at')
            ->get();

        return response()->json([
            'success' => true,
            'data' => ProgressResource::collection($progress),
        ]);
    }

    public function complete(Request $request, Node $node): JsonResponse
    {
        $progress = UserProgress::query()->firstOrNew([
            'userId' => $request->user()->id,
            'nodeId' => $node->id,
        ]);

        if (! $progress->exists) {
            $progress->id = (string) Str::uuid();
        }

        $progress->status = 'completed';
        $progress->save();
        $progress->load('node.unit.section');

        return response()->json([
            'success' => true,
            'message' => 'Progress berhasil disimpan.',
            'data' => new ProgressResource($progress),
        ]);
    }
}
