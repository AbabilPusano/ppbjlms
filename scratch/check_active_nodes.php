<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Node;

$nodes = Node::where('unitId', '01KXF7DSGB8EAXVE32TEWJR1Y0')->orderBy('order')->get();
foreach ($nodes as $n) {
    $stepsCount = \App\Models\NodeStep::where('node_id', $n->id)->count();
    echo "ID: {$n->id}, Title: {$n->title}, Order: {$n->order}, Steps Count: {$stepsCount}\n";
}
