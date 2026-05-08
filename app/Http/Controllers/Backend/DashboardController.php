<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\RequestFormRepository;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $requestRepo;

    public function __construct(RequestFormRepository $requestRepo)
    {
        $this->requestRepo = $requestRepo;
    }

    public function index()
    {
        $user = auth()->user();
        
        // ตรวจสอบสิทธิ์: ต้องเป็น Admin หรือ อยู่แผนก ICT (ID: 16)
        if (!($user->role === 'admin' || $user->dept_id == 16)) {
            return redirect()->route('request.index')->with('error', 'คุณไม่มีสิทธิ์เข้าถึงส่วนงานนี้ เฉพาะแผนก ICT หรือผู้ดูแลระบบเท่านั้น');
        }

        $allRequests = $this->requestRepo->getAll();
        $stats = [
            'total' => $allRequests->count(),
            'pending' => $allRequests->where('status', 'pending')->count(),
            'approved' => $allRequests->where('status', 'approved')->count(),
            'rejected' => $allRequests->where('status', 'rejected')->count(),
        ];

        return view('backend.dashboard', compact('stats', 'allRequests'));
    }
}
