<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\NodeStep;

$steps = NodeStep::where('node_id', '01KYGKXYA6VDMGP4NYEQ09AKSF')
    ->where('type', 'quiz')
    ->get();

foreach ($steps as $s) {
    echo "ID: {$s->id}\n";
    echo "  type: {$s->type}\n";
    echo "  content: {$s->content}\n";
    echo "  character_symbol: {$s->character_symbol}\n";
    echo "  character_pronunciation: {$s->character_pronunciation}\n";
    echo "  quiz_instruction: {$s->quiz_instruction}\n";
    echo "  quiz_query: {$s->quiz_query}\n";
    echo "  quiz_options: " . json_encode($s->quiz_options) . "\n";
    echo "  quiz_correct_index: {$s->quiz_correct_index}\n";
}
