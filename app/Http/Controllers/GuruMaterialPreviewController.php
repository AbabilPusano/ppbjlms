<?php

namespace App\Http\Controllers;

use App\Models\Node;
use App\Services\LessonContentBuilder;
use Illuminate\Contracts\View\View;

class GuruMaterialPreviewController extends Controller
{
    public function __invoke(Node $material, LessonContentBuilder $lessonContentBuilder): View
    {
        $material->load('unit.level');
        $unit = $material->unit;

        abort_if($unit === null, 404);

        return view('lesson-ui', [
            'node' => $material,
            'questions' => $lessonContentBuilder->build($material),
            'levelId' => $unit->level_id,
            'unitOrder' => ((int) $unit->order) + 1,
            'levelOrder' => ((int) $material->order) + 1,
            'isPreview' => true,
            'lessonContext' => "{$unit->title} · Isi unit ".(((int) $material->order) + 1),
            'returnUrl' => route('guru.units.materials', $unit),
            'returnLabel' => 'Kembali ke kelola isi unit',
            'manageStepsUrl' => route('guru.materials.questions', $material),
        ]);
    }
}
