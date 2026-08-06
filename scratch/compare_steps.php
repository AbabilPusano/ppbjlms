<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\NodeStep;

echo "Steps for Node 01KYGQVAA92C3Q0M6MAY4FAYEC:\n";
$steps1 = NodeStep::where('node_id', '01KYGQVAA92C3Q0M6MAY4FAYEC')->orderBy('sort_order')->get();
foreach ($steps1 as $s) {
    echo " - ID: {$s->id}, Type: {$s->type}, Symbol: {$s->character_symbol}, Pronunciation: {$s->character_pronunciation}\n";
}

echo "\nSteps for Node 01KYGXRCH03ADARDBKW6H0QD05:\n";
$steps2 = NodeStep::where('node_id', '01KYGXRCH03ADARDBKW6H0QD05')->orderBy('sort_order')->get();
foreach ($steps2 as $s) {
    echo " - ID: {$s->id}, Type: {$s->type}, Symbol: {$s->character_symbol}, Pronunciation: {$s->character_pronunciation}\n";
}
