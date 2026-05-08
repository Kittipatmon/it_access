<?php

namespace App\Services;

use App\Repositories\RequestFormRepository;
use App\Repositories\ApprovalRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ApprovalService
{
    protected $requestRepo;
    protected $approvalRepo;
    protected $notificationService;

    public function __construct(
        RequestFormRepository $requestRepo, 
        ApprovalRepository $approvalRepo,
        NotificationService $notificationService
    ) {
        $this->requestRepo = $requestRepo;
        $this->approvalRepo = $approvalRepo;
        $this->notificationService = $notificationService;
    }

    public function approve(int $stepId, int $approverId, string $remark = null, string $signatureData = null, bool $useExisting = false, $signatureFile = null): bool
    {
        return DB::transaction(function () use ($stepId, $approverId, $remark, $signatureData, $useExisting, $signatureFile) {
            $step = $this->approvalRepo->findStepById($stepId);
            
            if (!$step || $step->approver_id !== $approverId || $step->status !== 'pending') {
                return false;
            }

            $signaturePath = null;
            if ($signatureFile instanceof \Illuminate\Http\UploadedFile) {
                $fileName = 'sig_' . $approverId . '_' . time() . '_' . Str::random(5) . '.' . $signatureFile->getClientOriginalExtension();
                $signaturePath = $signatureFile->storeAs('signatures', $fileName, 'public');
            } elseif ($useExisting) {
                $user = \App\Models\User::find($approverId);
                if ($user && $user->signature) {
                    $signaturePath = 'signatures/' . $user->signature;
                }
            } elseif ($signatureData) {
                $signaturePath = $this->saveSignature($signatureData, $approverId);
            }

            $this->approvalRepo->updateStep($stepId, [
                'status' => 'approved',
                'approved_at' => now(),
                'remark' => $remark,
                'signature_path' => $signaturePath,
            ]);

            $request = $step->requestForm;
            $nextStepOrder = $step->step_order + 1;
            $totalSteps = $request->steps()->count();

            if ($nextStepOrder > $totalSteps) {
                // All steps completed
                $this->requestRepo->update($request->id, [
                    'status' => 'approved',
                    'approved_at' => now(),
                ]);
                
                $this->notificationService->notifyRequestCompleted($request);
            } else {
                // Move to next step
                $this->requestRepo->update($request->id, [
                    'current_step' => $nextStepOrder,
                ]);
            }

            $this->approvalRepo->createHistory([
                'request_form_id' => $request->id,
                'action_by' => $approverId,
                'action_type' => 'approved',
                'remark' => $remark,
                'signature_path' => $signaturePath,
            ]);

            return true;
        });
    }

    public function reject(int $stepId, int $approverId, string $remark): bool
    {
        return DB::transaction(function () use ($stepId, $approverId, $remark) {
            $step = $this->approvalRepo->findStepById($stepId);
            
            if (!$step || $step->approver_id !== $approverId || $step->status !== 'pending') {
                return false;
            }

            $this->approvalRepo->updateStep($stepId, [
                'status' => 'rejected',
                'approved_at' => now(),
                'remark' => $remark,
            ]);

            $request = $step->requestForm;
            $this->requestRepo->update($request->id, [
                'status' => 'rejected',
            ]);

            $this->approvalRepo->createHistory([
                'request_form_id' => $request->id,
                'action_by' => $approverId,
                'action_type' => 'rejected',
                'remark' => $remark,
            ]);

            $this->notificationService->notifyRequestRejected($request);

            return true;
        });
    }

    /**
     * Save signature image from base64 data
     */
    private function saveSignature(string $base64Data, int $approverId): string
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

        $fileName = 'sig_' . $approverId . '_' . time() . '_' . Str::random(5) . '.' . $type;
        $path = 'signatures/' . $fileName;

        Storage::disk('public')->put($path, $data);

        return $path;
    }
}
