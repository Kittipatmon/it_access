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
        $groupedOptions = AccessOption::active()->ordered()->get()->groupBy('category');
        
        return view('frontend.request.index', [
            'user' => $user,
            'groupedOptions' => $groupedOptions,
            'categoryLabels' => [
                'system' => 'สิทธิการเข้าถึงระบบ (Systems Access)',
                'program' => 'สิทธิการใช้งานโปรแกรม (Programs Access)',
                'equipment' => 'อุปกรณ์การใช้งาน (Equipment Access)',
            ]
        ]);
    }

    /**
     * Process the request form submission.
     */
    public function store(StoreRequestFormRequest $request)
    {
        $validated = $request->validated();
        
        // 1. Prepare approval steps from configuration
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
                'is_auto_sign' => $config->is_auto_sign,
            ];
        })->toArray();

        // 2. Process Dynamic Access Selections
        $standardCategories = ['system', 'program', 'equipment'];
        $additionalAccess = [];
        
        // Get all keys from request that end with _access
        $accessInputs = collect($request->all())->filter(function($value, $key) {
            return str_ends_with($key, '_access');
        });

        foreach ($accessInputs as $inputKey => $values) {
            $categoryKey = str_replace('_access', '', $inputKey);
            
            // Collect Sub-options and Custom Fields for this category
            $processedData = collect($values)->map(function($itemKey) use ($request) {
                return [
                    'key' => $itemKey,
                    'sub_options' => $request->input("sub_options.{$itemKey}"),
                    'custom_fields' => $request->input("custom_fields.{$itemKey}"),
                ];
            })->toArray();

            // Check if there is an "Other" text input for this category
            $otherText = $request->input("{$categoryKey}_access_other");
            if ($otherText) {
                $processedData[] = [
                    'key' => 'other',
                    'value' => $otherText
                ];
            }

            if (in_array($categoryKey, $standardCategories)) {
                $validated["{$categoryKey}_access"] = $processedData;
            } else {
                $additionalAccess[$categoryKey] = $processedData;
            }
        }

        // 3. Handle "Other" position level if applicable
        if ($validated['position_level'] === 'other') {
            $validated['position_level'] = $validated['position_level_other'] ?? 'Other';
        }

        // 4. Finalize Data Payload
        $formData = array_merge($validated, [
            'user_id' => Auth::id(),
            'emp_code' => Auth::user()->emp_code,
            'additional_access' => !empty($additionalAccess) ? $additionalAccess : null,
        ]);

        $form = $this->workflowService->initiateRequest($formData, $steps);

        return redirect()->route('tracking.show', $form->request_no)
            ->with('success', "คำร้องเลขที่ {$form->request_no} ถูกส่งเข้าสู่ระบบเรียบร้อยแล้ว");
    }
}
