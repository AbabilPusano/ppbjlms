<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\NodeStep;
use App\Models\Node;

$steps = NodeStep::where('character_symbol', 'が')->get();
foreach ($steps as $step) {
    $node = Node::find($step->node_id);
    echo "Step ID: {$step->id}, Type: {$step->type}, Node ID: {$step->node_id}, Node Title: " . ($node ? $node->title : 'null') . "\n";
}
