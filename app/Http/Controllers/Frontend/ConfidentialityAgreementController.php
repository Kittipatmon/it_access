<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ConfidentialityAgreement;
use App\Models\RequestForm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConfidentialityAgreementController extends Controller
{
    public function show($requestNo)
    {
        $requestForm = RequestForm::where('request_no', $requestNo)->firstOrFail();
        
        // Check if user is requester, witness, or admin
        $nda = ConfidentialityAgreement::where('request_form_id', $requestForm->id)->first();
        
        $isRequester = $requestForm->user_id === Auth::id();
        $isWitness1 = $nda && $nda->witness1_user_id === Auth::id();
        $isWitness2 = $nda && $nda->witness2_user_id === Auth::id();
        $isAdmin = Auth::user()->role === 'admin';

        if (!$isRequester && !$isWitness1 && !$isWitness2 && !$isAdmin) {
            abort(403);
        }

        $existing = $nda;
        
        $months = [
            '01' => 'มกราคม', '02' => 'กุมภาพันธ์', '03' => 'มีนาคม', '04' => 'เมษายน',
            '05' => 'พฤษภาคม', '06' => 'มิถุนายน', '07' => 'กรกฎาคม', '08' => 'สิงหาคม',
            '09' => 'กันยายน', '10' => 'ตุลาคม', '11' => 'พฤศจิกายน', '12' => 'ธันวาคม'
        ];

        $users = User::with('department_rel')
            ->where('id', '!=', Auth::id())
            ->where('status', 1)
            ->get();
            
        $departments = \App\Models\Department::orderBy('name')->pluck('name');

        $repId = \App\Models\SystemSetting::where('key', 'nda_company_representative_id')->value('value');
        $manager = User::find($repId) ?? User::where('firstname', 'เกรียงศักดิ์')->where('lastname', 'อำนวยโชค')->first();

        return view('frontend.request.nda', compact('requestForm', 'existing', 'months', 'users', 'departments', 'manager'));
    }

    public function store(Request $request, $requestNo)
    {
        $requestForm = RequestForm::where('request_no', $requestNo)->firstOrFail();
        
        $validated = $request->validate([
            'prefix' => 'required|string',
            'full_name' => 'required|string',
            'age' => 'required|integer',
            'id_card_no' => 'required|string',
            'address_no' => 'required|string',
            'soi' => 'nullable|string',
            'road' => 'nullable|string',
            'tambon' => 'required|string',
            'amphoe' => 'required|string',
            'province' => 'required|string',
            'contact_no' => 'required|string',
            'employee_signature' => 'required|string',
            'witness1_user_id' => 'required|exists:userkmlnew.employees,id',
            'witness2_user_id' => 'nullable|exists:userkmlnew.employees,id',
        ]);

        $witness1 = User::find($validated['witness1_user_id']);
        $witness2 = User::find($validated['witness2_user_id']);

        ConfidentialityAgreement::updateOrCreate(
            ['request_form_id' => $requestForm->id],
            array_merge($validated, [
                'user_id' => Auth::id(),
                'agreement_date' => now(),
                'witness1_name' => $witness1->fullname,
                'witness2_name' => $witness2 ? $witness2->fullname : null,
            ])
        );

        return redirect()->route('tracking.show', $requestNo)
            ->with('success', 'บันทึกข้อตกลงรักษาความลับเรียบร้อยแล้ว กรุณาแจ้งพยานให้เข้ามารับรองเอกสาร');
    }

    public function agreeWitness(Request $request, $requestNo, $witnessNo)
    {
        $requestForm = RequestForm::where('request_no', $requestNo)->firstOrFail();
        $nda = ConfidentialityAgreement::where('request_form_id', $requestForm->id)->firstOrFail();

        $updated = false;
        
        // If user is Witness 1 and hasn't signed, sign it
        if ($nda->witness1_user_id === Auth::id() && !$nda->witness1_agreed_at) {
            $nda->update([
                'witness1_agreed_at' => now(),
                'witness1_signature' => $request->signature,
            ]);
            $updated = true;
        }

        // If user is Witness 2 and hasn't signed, sign it
        if ($nda->witness2_user_id === Auth::id() && !$nda->witness2_agreed_at) {
            $nda->update([
                'witness2_agreed_at' => now(),
                'witness2_signature' => $request->signature,
            ]);
            $updated = true;
        }

        if (!$updated) {
            abort(403);
        }

        // Check if both witnesses have signed
        $nda->refresh();
        if ($nda->witness1_agreed_at && $nda->witness2_agreed_at) {
            // Optional: Update request form status if needed
            // $requestForm->update(['status' => 'fully_completed']);
        }

        return response()->json(['success' => true]);
    }
}
