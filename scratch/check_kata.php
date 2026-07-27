<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$kata = \App\Models\Kata::where('kata', '愛')->first();
if ($kata) {
    echo "Found Kata:\n";
    print_r($kata->toArray());
} else {
    echo "Kata '愛' not found.\n";
}
