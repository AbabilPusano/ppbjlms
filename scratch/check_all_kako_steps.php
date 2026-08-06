<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\NodeStep;
use App\Models\Node;

$kakoNodes = Node::where('title', 'like', '%ka%')->get();
foreach ($kakoNodes as $n) {
    echo "Node ID: {$n->id}, Title: {$n->title}\n";
    $steps = NodeStep::where('node_id', $n->id)->orderBy('sort_order')->get();
    foreach ($steps as $s) {
        echo "  - Step ID: {$s->id}, Type: {$s->type}, Symbol: {$s->character_symbol}, Pron: {$s->character_pronunciation}\n";
    }
}
