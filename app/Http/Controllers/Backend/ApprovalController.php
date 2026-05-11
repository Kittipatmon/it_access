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

        $accessOptions = \App\Models\AccessOption::active()->get();

        return view('backend.approvals.show', compact('request', 'accessOptions'));
    }

    public function approve(Request $request, $stepId)
    {
        $step = $this->approvalRepo->findStepById($stepId);
        if (!$step) {
            return redirect()->back()->with('error', 'ไม่พบขั้นตอนการอนุมัติ');
        }

        // Verify sequential approval
        if ($step->step_order != $step->requestForm->current_step) {
            return redirect()->back()->with('error', 'ยังไม่ถึงลำดับการอนุมัติของคุณ');
        }

        $success = $this->approvalService->approve(
            $stepId,
            Auth::id(),
            $request->remark,
            $request->signature,
            $request->use_existing == '1',
            $request->file('signature_file')
        );

        if ($success) {
            $requestForm = $this->requestRepo->findById($step->request_form_id);
            $message = 'อนุมัติคำร้องเรียบร้อยแล้ว';
            
            if ($requestForm->status === 'approved') {
                $message .= ' (ขั้นตอนการอนุมัติครบถ้วนแล้ว กรุณาดำเนินการขั้นตอนที่ 3 ต่อไป)';
            } else {
                $message .= ' (ส่งต่อให้ผู้ลำดับถัดไปพิจารณาเรียบร้อยแล้ว)';
            }

            return redirect()->route('backend.approvals.show', $requestForm->id)->with('success', $message);
        }

        return redirect()->back()->with('error', 'ไม่สามารถดำเนินการได้');
    }

    public function reject(Request $request, $stepId)
    {
        $request->validate(['remark' => 'required']);
        $step = $this->approvalRepo->findStepById($stepId);
        if (!$step) {
            return redirect()->back()->with('error', 'ไม่พบขั้นตอนการอนุมัติ');
        }

        // Verify sequential approval
        if ($step->step_order != $step->requestForm->current_step) {
            return redirect()->back()->with('error', 'ยังไม่ถึงลำดับการอนุมัติของคุณ');
        }

        $success = $this->approvalService->reject($stepId, Auth::id(), $request->remark);

        if ($success) {
            return redirect()->route('backend.approvals.index')->with('success', 'ปฏิเสธคำร้องเรียบร้อยแล้ว (คำร้องถูกยกเลิกแล้ว)');
        }

        return redirect()->back()->with('error', 'ไม่สามารถดำเนินการได้');
    }

    public function complete(Request $request, $id)
    {
        $form = $this->requestRepo->findById($id);
        if (!$form) {
            return redirect()->back()->with('error', 'ไม่พบใบคำร้อง');
        }

        if (Auth::user()->role !== 'admin' && Auth::user()->dept_id != 16) {
            return redirect()->back()->with('error', 'ไม่มีสิทธิ์ดำเนินการในส่วนนี้');
        }

        $form->update([
            'it_staff_id' => Auth::id(),
            'it_configured_at' => now(),
            'it_remark' => $request->it_remark,
            'it_status' => 'completed',
            'it_system_config' => $request->it_system_config,
            'it_program_config' => $request->it_program_config,
        ]);

        return redirect()->back()->with('success', 'ดำเนินการขั้นตอนที่ 3 เสร็จสมบูรณ์แล้ว (ข้อมูลถูกบันทึกเข้าระบบเรียบร้อย)');
    }
}
