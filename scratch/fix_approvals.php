<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\ApprovalStep;
use App\Models\ApprovalStepConfig;

// Fix the pending request
$step = ApprovalStep::where('request_form_id', function($q) {
    $q->select('id')->from('request_forms')->where('request_no', 'REQ-20260508-0UEX');
})->where('status', 'pending')->where('approver_id', 652)->first();

if ($step) {
    $step->approver_id = 664;
    $step->save();
    echo "Updated request step approver to 664\n";
} else {
    echo "Request step not found or already updated\n";
}

// Fix the configuration for future requests
$config = ApprovalStepConfig::where('step_name', 'IT Manager')->where('approver_id', 652)->first();
if ($config) {
    $config->approver_id = 664;
    $config->save();
    echo "Updated config approver to 664\n";
}
