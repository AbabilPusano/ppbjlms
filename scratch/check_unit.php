<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$unit = \App\Models\Unit::query()
    ->orderBy('order')
    ->first();

if ($unit) {
    echo "Unit details:\n";
    print_r($unit->toArray());
    
    $node = $unit->nodes()
        ->orderBy('order')
        ->first();
        
    if ($node) {
        echo "Node details:\n";
        print_r($node->toArray());
        
        $steps = $node->steps()->get();
        foreach ($steps as $idx => $step) {
            echo "Step " . ($idx+1) . " type: " . $step->type . " symbol: " . $step->character_symbol . "\n";
        }
    }
}
