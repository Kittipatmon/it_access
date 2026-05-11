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
        $requests = $this->requestRepo->getInvolvedRequests(Auth::id());
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

        $accessOptions = \App\Models\AccessOption::active()->get();
        return view('frontend.tracking.show', compact('request', 'accessOptions'));
    }

    public function print($requestNo)
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

        $accessOptions = \App\Models\AccessOption::active()->get();
        return view('frontend.tracking.pdf.frompdf', compact('request', 'accessOptions'));
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

    public function acknowledge(Request $request, $requestNo)
    {
        $requestForm = $this->requestRepo->findByRequestNo($requestNo);

        if (!$requestForm || $requestForm->user_id !== Auth::id()) {
            abort(403);
        }

        if ($requestForm->it_status !== 'completed') {
            return redirect()->back()->with('error', 'ไม่สามารถยืนยันได้เนื่องจากเจ้าหน้าที่ IT ยังดำเนินการไม่เสร็จสิ้น');
        }

        $requestForm->user_acknowledged_at = now();
        $requestForm->status = 'completed'; // Mark the whole request as completed
        $requestForm->save();

        return redirect()->back()->with('success', 'ยืนยันการรับทราบและยอมรับระเบียบเรียบร้อยแล้ว');
    }
}
