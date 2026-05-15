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

    public function index(Request $request)
    {
        $userId = Auth::id();
        $repId = \App\Models\SystemSetting::where('key', 'nda_company_representative_id')->value('value');
        $isRep = ($userId == $repId);
        $autoSign = \App\Models\SystemSetting::where('key', 'nda_auto_sign')->value('value') == '1';
        
        $query = \App\Models\RequestForm::where(function($q) use ($userId, $isRep, $autoSign) {
                $q->where('user_id', $userId)
                  ->orWhereHas('steps', function ($query) use ($userId) {
                      $query->where('approver_id', $userId)->where('status', 'pending');
                  })
                  ->orWhereHas('confidentialityAgreement', function ($query) use ($userId, $isRep, $autoSign) {
                      $query->where(function($sq) use ($userId) {
                          $sq->where('witness1_user_id', $userId)->whereNull('witness1_agreed_at');
                      })->orWhere(function($sq) use ($userId) {
                          $sq->where('witness2_user_id', $userId)->whereNull('witness2_agreed_at');
                      });

                      if ($isRep) {
                          $query->orWhere(function($sq) {
                              $sq->whereNull('company_agreed_at')->where('is_auto_sign', 0);
                          });
                      }
                  });
            })
            ->with(['user', 'steps.approver', 'confidentialityAgreement'])
            ->latest();

        // Apply Filters
        if ($request->filled('year')) {
            $query->whereYear('created_at', $request->year);
        }
        if ($request->filled('month')) {
            $query->whereMonth('created_at', $request->month);
        }

        $allRequests = $query->get();

        // Separate requests that the current user needs to approve
        $toApprove = $allRequests->filter(function ($request) use ($userId) {
            if ($request->status !== 'pending') return false;
            
            $currentStep = $request->steps
                ->where('step_order', $request->current_step)
                ->where('status', 'pending')
                ->first();

            return $currentStep && $currentStep->approver_id == $userId;
        });

        // Requests that the current user needs to acknowledge (IT completed but user hasn't signed off)
        $toAcknowledge = $allRequests->filter(function ($request) use ($userId) {
            return $request->user_id == $userId && 
                   $request->status == 'approved' && 
                   $request->it_status == 'completed' && 
                   !$request->user_acknowledged_at;
        });

        // Requests that the current user needs to verify as a witness
        $toVerifyNDA = $allRequests->filter(function ($request) use ($userId) {
            $nda = $request->confidentialityAgreement;
            if (!$nda) return false;

            $isW1Pending = $nda->witness1_user_id == $userId && !$nda->witness1_agreed_at;
            $isW2Pending = $nda->witness2_user_id == $userId && !$nda->witness2_agreed_at;

            return $isW1Pending || $isW2Pending;
        });

        // Requests that the current user needs to sign as Company Representative
        $toSignNDACompany = $allRequests->filter(function ($request) use ($userId, $isRep) {
            if (!$isRep) return false;
            $nda = $request->confidentialityAgreement;
            return $nda && !$nda->company_agreed_at && !$nda->is_auto_sign;
        });

        // Requests that the requester needs to record NDA for
        $toRecordNDA = $allRequests->filter(function ($request) use ($userId) {
            return $request->user_id == $userId && 
                   $request->status == 'approved' && // Approved by all, ready for NDA
                   $request->it_status == 'completed' && // IT finished, ready for NDA
                   $request->user_acknowledged_at && // User acknowledged, ready for NDA
                   !$request->confidentialityAgreement;
        });

        return view('frontend.tracking.index', [
            'requests' => $allRequests,
            'toApprove' => $toApprove,
            'toAcknowledge' => $toAcknowledge,
            'toVerifyNDA' => $toVerifyNDA,
            'toSignNDACompany' => $toSignNDACompany,
            'toRecordNDA' => $toRecordNDA
        ]);
    }

    public function show($requestNo)
    {
        $request = $this->requestRepo->findByRequestNo($requestNo);
        
        if (!$request) {
            abort(404);
        }

        // Allow: 1. Requester, 2. Admin, 3. Any Approver, 4. Any Witness of this request
        $isApprover = $request->steps()->where('approver_id', Auth::id())->exists();
        
        $nda = $request->confidentialityAgreement;
        $isWitness = $nda && ($nda->witness1_user_id == Auth::id() || $nda->witness2_user_id == Auth::id());

        $repId = \App\Models\SystemSetting::where('key', 'nda_company_representative_id')->value('value');
        $isRep = ($repId == Auth::id());

        if ($request->user_id !== Auth::id() && Auth::user()->role !== 'admin' && !$isApprover && !$isWitness && !$isRep) {
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

        // Only allow cancellation if status is pending (not yet approved by anyone or final)
        // If you want to allow cancellation even after some steps are approved, just check $request->status !== 'completed'
        // But usually 'not yet approved' means pending first approval.
        if ($request->status !== 'pending') {
            return redirect()->back()->with('error', 'ไม่สามารถยกเลิกใบคำร้องที่อยู่ระหว่างดำเนินการหรือเสร็จสิ้นแล้วได้');
        }

        $request->delete();

        return redirect()->route('tracking.index')->with('success', 'ยกเลิกใบคำร้องเรียบร้อยแล้ว');
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

        return redirect()->route('request.nda', $requestNo)
            ->with('success', 'ยืนยันการรับทราบและยอมรับระเบียบเรียบร้อยแล้ว กรุณาดำเนินการต่อในส่วนของข้อตกลงรักษาความลับ');
    }
}
