<?php

namespace App\Services;

use App\Repositories\RequestFormRepository;
use App\Repositories\ApprovalRepository;
use App\Models\RequestForm;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WorkflowService
{
    protected $requestRepo;
    protected $approvalRepo;

    public function __construct(RequestFormRepository $requestRepo, ApprovalRepository $approvalRepo)
    {
        $this->requestRepo = $requestRepo;
        $this->approvalRepo = $approvalRepo;
    }

    public function initiateRequest(array $data, array $steps): RequestForm
    {
        return DB::transaction(function () use ($data, $steps) {
            // Handle signature if provided
            $signaturePath = null;
            
            if (isset($data['signature_file']) && $data['signature_file'] instanceof \Illuminate\Http\UploadedFile) {
                // Save uploaded file
                $fileName = 'req_sig_' . $data['user_id'] . '_' . time() . '_' . Str::random(5) . '.' . $data['signature_file']->getClientOriginalExtension();
                $signaturePath = $data['signature_file']->storeAs('signatures', $fileName, 'public');
                $data['signature_path'] = $signaturePath;
                unset($data['signature_file']);

                // Auto-save to user profile if not exists
                $user = \App\Models\User::find($data['user_id']);
                if ($user && empty($user->signature)) {
                    $user->signature = $fileName;
                    $user->save();
                }
            } elseif (isset($data['existing_signature']) && $data['existing_signature']) {
                // Use existing signature path
                $signaturePath = 'signatures/' . $data['existing_signature'];
                $data['signature_path'] = $signaturePath;
                unset($data['existing_signature']);
            } elseif (isset($data['signature']) && $data['signature']) {
                // Save new signature (Base64)
                $signaturePath = $this->saveSignature($data['signature'], $data['user_id']);
                $data['signature_path'] = $signaturePath;
                
                // Auto-save to user profile if not exists
                $user = \App\Models\User::find($data['user_id']);
                if ($user && empty($user->signature)) {
                    $user->signature = basename($signaturePath);
                    $user->save();
                }
                
                unset($data['signature']);
            }

            // Generate Request Number
            $requestNo = 'REQ-' . date('Ymd') . '-' . strtoupper(Str::random(4));
            
            $request = $this->requestRepo->create(array_merge($data, [
                'request_no' => $requestNo,
                'status' => 'pending',
                'current_step' => 1,
            ]));

            foreach ($steps as $index => $step) {
                $this->approvalRepo->createStep([
                    'request_form_id' => $request->id,
                    'step_order' => $index + 1,
                    'step_name' => $step['name'],
                    'approver_id' => $step['approver_id'],
                    'status' => 'pending',
                    'is_auto_sign' => $step['is_auto_sign'] ?? false,
                ]);
            }

            $this->approvalRepo->createHistory([
                'request_form_id' => $request->id,
                'action_by' => $data['user_id'],
                'action_type' => 'created',
                'remark' => 'Request created',
            ]);

            return $request;
        });
    }

    public function getNextStep(RequestForm $request)
    {
        return $request->steps()->where('step_order', $request->current_step)->first();
    }

    /**
     * Save signature image from base64 data
     */
    private function saveSignature(string $base64Data, int $userId): string
    {
        // Extract the base64 part
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
            $data = substr($base64Data, strpos($base64Data, ',') + 1);
            $type = strtolower($type[1]); // png, jpg, etc

            if (!in_array($type, ['png', 'jpg', 'jpeg'])) {
                throw new \Exception('Invalid image type');
            }

            $data = base64_decode($data);

            if ($data === false) {
                throw new \Exception('base64_decode failed');
            }
        } else {
            throw new \Exception('did not match data URI with image data');
        }

        $fileName = 'req_sig_' . $userId . '_' . time() . '_' . Str::random(5) . '.' . $type;
        $path = 'signatures/' . $fileName;

        Storage::disk('public')->put($path, $data);

        return $path;
    }
}
