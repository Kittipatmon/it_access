<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ApprovalStepConfig;
use App\Models\User;
use Illuminate\Http\Request;

class ApprovalStepConfigController extends Controller
{
    public function index()
    {
        $configs = \App\Models\ApprovalStepConfig::with('approver')->orderBy('step_order')->get();
        // ดึงเฉพาะ Admin หรือ ICT หรือคนที่มีบทบาทเป็นผู้อนุมัติ (ถ้ามี)
        $users = User::with('department_rel')->where('status', 'active')->orderBy('firstname')->get(); 
        $departments = \App\Models\Department::orderBy('name')->get();
        
        return view('backend.approval-configs.index', compact('configs', 'users', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'step_name' => 'required|string|max:255',
            'approver_id' => 'required',
            'step_order' => 'required|integer|unique:approval_step_configs,step_order',
        ], [
            'step_order.unique' => 'ลำดับขั้นตอนนี้ถูกใช้งานแล้ว กรุณาเลือกลำดับอื่น',
        ]);

        ApprovalStepConfig::create($request->all());

        return redirect()->back()->with('success', 'เพิ่มขั้นตอนการอนุมัติสำเร็จ');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'step_name' => 'required|string|max:255',
            'approver_id' => 'required',
            'step_order' => 'required|integer|unique:approval_step_configs,step_order,' . $id,
        ], [
            'step_order.unique' => 'ลำดับขั้นตอนนี้ถูกใช้งานแล้ว กรุณาเลือกลำดับอื่น',
        ]);

        $config = ApprovalStepConfig::findOrFail($id);
        $config->update($request->all());

        return redirect()->back()->with('success', 'ปรับปรุงขั้นตอนการอนุมัติสำเร็จ');
    }

    public function destroy($id)
    {
        ApprovalStepConfig::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'ลบขั้นตอนการอนุมัติสำเร็จ');
    }
}
