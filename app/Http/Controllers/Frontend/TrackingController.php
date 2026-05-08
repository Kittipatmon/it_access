<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Repositories\RequestFormRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackingController extends Controller
{
    protected $requestRepo;

    public function __construct(RequestFormRepository $requestRepo)
    {
        $this->requestRepo = $requestRepo;
    }

    public function index()
    {
        $requests = $this->requestRepo->getByUserId(Auth::id());
        return view('frontend.tracking.index', compact('requests'));
    }

    public function show($requestNo)
    {
        $request = $this->requestRepo->findByRequestNo($requestNo);
        
        if (!$request) {
            abort(404);
        }

        // Allow: 1. Requester, 2. Admin, 3. Any Approver of this request
        $isApprover = $request->steps()->where('approver_id', Auth::id())->exists();

        if ($request->user_id !== Auth::id() && Auth::user()->role !== 'admin' && !$isApprover) {
            abort(404);
        }

        return view('frontend.tracking.show', compact('request'));
    }

    public function destroy($requestNo)
    {
        $request = $this->requestRepo->findByRequestNo($requestNo);

        if (!$request || $request->user_id !== Auth::id()) {
            abort(403);
        }

        if ($request->status !== 'pending') {
            return redirect()->back()->with('error', 'ไม่สามารถลบใบคำร้องที่อยู่ระหว่างดำเนินการหรือเสร็จสิ้นแล้วได้');
        }

        $request->delete();

        return redirect()->route('tracking.index')->with('success', 'ลบใบคำร้องเรียบร้อยแล้ว');
    }
}
