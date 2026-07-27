<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$level = \App\Models\Level::find(1);
if ($level) {
    echo "Level 1:\n";
    print_r($level->toArray());
}

$section = \Illuminate\Support\Facades\DB::table('sections')->where('id', 'huruf')->first();
if ($section) {
    echo "Section 'huruf':\n";
    print_r((array)$section);
}
