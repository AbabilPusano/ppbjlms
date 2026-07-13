<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\NodeResource;
use App\Http\Resources\Api\V1\QuestionResource;
use App\Http\Resources\Api\V1\NodeStepResource;
use App\Http\Resources\Api\V1\SectionResource;
use App\Http\Resources\Api\V1\StoryResource;
use App\Http\Resources\Api\V1\UnitResource;
use App\Models\Node;
use App\Models\Section;
use App\Models\Story;
use App\Models\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LmsController extends Controller
{
    public function sections(): JsonResponse
    {
        $sections = Section::query()
            ->withCount(['units', 'stories'])
            ->orderBy('name')
            ->get();

        return $this->ok(SectionResource::collection($sections));
    }

    public function section(Section $section): JsonResponse
    {
        $section->load([
            'units' => fn ($query) => $query->withCount('nodes'),
            'stories',
        ]);

        return $this->ok(new SectionResource($section));
    }

    public function sectionUnits(Section $section): JsonResponse
    {
        $units = $section->units()
            ->withCount('nodes')
            ->get();

        return $this->ok(UnitResource::collection($units));
    }

    public function unit(Unit $unit): JsonResponse
    {
        $unit->load(['section', 'nodes' => fn ($query) => $query->withCount('questions')]);

        return $this->ok(new UnitResource($unit));
    }

    public function unitNodes(Unit $unit): JsonResponse
    {
        $nodes = $unit->nodes()
            ->withCount('questions')
            ->get();

        return $this->ok(NodeResource::collection($nodes));
    }

    public function node(Node $node): JsonResponse
    {
        $node->load(['unit.section', 'questions']);

        return $this->ok(new NodeResource($node));
    }

    public function nodeQuestions(Node $node): JsonResponse
    {
        return $this->ok(QuestionResource::collection($node->questions));
    }

    public function nodeSteps(Node $node): JsonResponse
    {
        return $this->ok(NodeStepResource::collection($node->steps));
    }

    public function stories(Request $request): JsonResponse
    {
        $stories = Story::query()
            ->with('section')
            ->when($request->string('section_id')->toString(), fn ($query, string $sectionId) => $query->where('sectionId', $sectionId))
            ->orderBy('order')
            ->get();

        return $this->ok(StoryResource::collection($stories));
    }

    private function ok(mixed $data): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
