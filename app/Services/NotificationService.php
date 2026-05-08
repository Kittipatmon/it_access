<?php

namespace App\Services;

use App\Models\RequestForm;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public function notifyRequestCompleted(RequestForm $request)
    {
        // For now, just log. In a real app, you'd send an email or database notification.
        Log::info("Request {$request->request_no} has been fully approved.");
        
        // Example: $request->user->notify(new RequestApprovedNotification($request));
    }

    public function notifyRequestRejected(RequestForm $request)
    {
        Log::info("Request {$request->request_no} has been rejected.");
        
        // Example: $request->user->notify(new RequestRejectedNotification($request));
    }

    public function notifyNextApprover(RequestForm $request, $approver)
    {
        Log::info("Request {$request->request_no} is now waiting for approval from {$approver->name}");
    }
}
