<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\NodeStep;

$existsNormal = NodeStep::where('character_symbol', 'が')->exists();
$existsBinary = NodeStep::whereRaw('BINARY character_symbol = ?', ['が'])->exists();

echo "Normal exists: " . ($existsNormal ? "YES" : "NO") . "\n";
echo "Binary exists: " . ($existsBinary ? "YES" : "NO") . "\n";
