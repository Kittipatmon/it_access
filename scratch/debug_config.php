<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\ApprovalStepConfig;

$configs = ApprovalStepConfig::all();
foreach ($configs as $c) {
    echo "Step: {$c->step_name}, Approver ID: {$c->approver_id}\n";
}
