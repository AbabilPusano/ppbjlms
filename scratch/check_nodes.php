<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Node;

$nodes = Node::where('title', 'like', '%KA - KO%')->get();
foreach ($nodes as $n) {
    echo "Node ID: {$n->id}, Title: {$n->title}, Unit ID: {$n->unitId}, Type: {$n->type}\n";
}
