<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\User;
use App\Models\ApprovalStep;
use App\Models\RequestForm;

$user = User::where('firstname', 'กิตติพัฒน์')->first();
echo "User ID: " . ($user ? $user->id : 'Not found') . "\n";

$pendingSteps = ApprovalStep::where('status', 'pending')->get();
foreach ($pendingSteps as $step) {
    echo "Step: {$step->step_name}, Order: {$step->step_order}, Approver ID: {$step->approver_id}, Request: {$step->requestForm->request_no}, Current Step: {$step->requestForm->current_step}\n";
}
