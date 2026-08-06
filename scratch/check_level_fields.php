<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Node;

$node = Node::find('01KYGKXYA6VDMGP4NYEQ09AKSF');
if ($node && $node->unit) {
    echo "Unit Title: {$node->unit->title}\n";
    if ($node->unit->level) {
        echo "Level ID: {$node->unit->level->id}\n";
        echo "Level Name: {$node->unit->level->name}\n";
        echo "Level Code: {$node->unit->level->code}\n";
    } else {
        echo "Level is null\n";
    }
} else {
    echo "Node or unit is null\n";
}
