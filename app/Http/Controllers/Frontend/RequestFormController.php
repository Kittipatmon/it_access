<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRequestFormRequest;
use App\Repositories\UserRepository;
use App\Repositories\RequestFormRepository;
use App\Services\WorkflowService;
use App\Models\AccessOption;
use Illuminate\Support\Facades\Auth;

class RequestFormController extends Controller
{
    protected $userRepo;
    protected $workflowService;
    protected $requestRepo;

    public function __construct(
        UserRepository $userRepo, 
        WorkflowService $workflowService,
        RequestFormRepository $requestRepo
    ) {
        $this->userRepo = $userRepo;
        $this->workflowService = $workflowService;
        $this->requestRepo = $requestRepo;
    }

    /**
     * Show the request form.
     */
    public function index()
    {
        $user = Auth::user()->load('department_rel');
        
        return view('frontend.request.index', [
            'user' => $user,
            'systemOptions' => AccessOption::system()->active()->ordered()->get(),
            'programOptions' => AccessOption::program()->active()->ordered()->get(),
        ]);
    }

    /**
     * Process the request form submission.
     */
    public function store(StoreRequestFormRequest $request)
    {
        $validated = $request->validated();
        
        // Prepare approval steps from configuration
        $defaultSteps = \App\Models\ApprovalStepConfig::where('is_active', true)
            ->orderBy('step_order')
            ->get();

        if ($defaultSteps->isEmpty()) {
            return redirect()->back()->withInput()->with('error', 'ระบบยังไม่ได้กำหนดขั้นตอนการอนุมัติ กรุณาติดต่อผู้ดูแลระบบ');
        }

        $steps = $defaultSteps->map(function ($config) {
            return [
                'name' => $config->step_name,
                'approver_id' => $config->approver_id,
            ];
        })->toArray();

        // Handle "Other" position level if applicable
        if ($validated['position_level'] === 'other') {
            $validated['position_level'] = $validated['position_level_other'];
        }

        // Add user_id to the payload
        $formData = array_merge($validated, [
            'user_id' => Auth::id(),
            'emp_code' => Auth::user()->emp_code, // Ensure emp_code is snapshotted from the current auth user
        ]);

        $form = $this->workflowService->initiateRequest($formData, $steps);

        return redirect()->route('tracking.show', $form->request_no)
            ->with('success', "คำร้องเลขที่ {$form->request_no} ถูกส่งเข้าสู่ระบบเรียบร้อยแล้ว");
    }
}
