<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\ApprovalRepository;
use App\Repositories\RequestFormRepository;
use App\Services\ApprovalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalController extends Controller
{
    protected $approvalRepo;
    protected $requestRepo;
    protected $approvalService;

    public function __construct(
        ApprovalRepository $approvalRepo, 
        RequestFormRepository $requestRepo,
        ApprovalService $approvalService
    ) {
        $this->approvalRepo = $approvalRepo;
        $this->requestRepo = $requestRepo;
        $this->approvalService = $approvalService;
    }

    public function index()
    {
        $user = Auth::user();
        $isAdmin = ($user->role === 'admin' || $user->dept_id == 16);

        $pendingApprovals = $this->approvalRepo->getPendingStepsByApprover($user->id);
        $allRequests = $isAdmin ? $this->requestRepo->getAll() : collect();

        return view('backend.approvals.index', compact('pendingApprovals', 'allRequests', 'isAdmin'));
    }

    public function show($id)
    {
        $request = $this->requestRepo->findById($id);
        if (!$request) {
            return redirect()->route('backend.approvals.index')->with('error', 'ไม่พบรายการคำร้อง');
        }

        return view('backend.approvals.show', compact('request'));
    }

    public function approve(Request $request, $stepId)
    {
        $success = $this->approvalService->approve(
            $stepId, 
            Auth::id(), 
            $request->remark, 
            $request->signature,
            $request->use_existing == '1',
            $request->file('signature_file')
        );
        
        if ($success) {
            return redirect()->back()->with('success', 'อนุมัติคำร้องเรียบร้อยแล้ว');
        }

        return redirect()->back()->with('error', 'ไม่สามารถดำเนินการได้');
    }

    public function reject(Request $request, $stepId)
    {
        $request->validate(['remark' => 'required']);
        
        $success = $this->approvalService->reject($stepId, Auth::id(), $request->remark);
        
        if ($success) {
            return redirect()->back()->with('success', 'ปฏิเสธคำร้องเรียบร้อยแล้ว');
        }

        return redirect()->back()->with('error', 'ไม่สามารถดำเนินการได้');
    }
}
