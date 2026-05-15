<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Http\Request;

class NdaConfigController extends Controller
{
    public function index()
    {
        $setting = SystemSetting::where('key', 'nda_company_representative_id')->first();
        $autoSignSetting = SystemSetting::where('key', 'nda_auto_sign')->first();
        $users = User::where('status', 'active')->orderBy('firstname')->get();
        
        return view('backend.nda-config.index', compact('setting', 'autoSignSetting', 'users'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'representative_id' => 'required|exists:userkmlnew.employees,id'
        ]);

        SystemSetting::updateOrCreate(
            ['key' => 'nda_company_representative_id'],
            ['value' => $request->representative_id, 'description' => 'ID ของผู้ลงนามในนามบริษัทสำหรับ NDA']
        );
        
        SystemSetting::updateOrCreate(
            ['key' => 'nda_auto_sign'],
            ['value' => $request->has('nda_auto_sign') ? '1' : '0', 'description' => 'เปิดใช้งานการลงนามอัตโนมัติสำหรับ NDA']
        );

        return redirect()->back()->with('success', 'ปรับปรุงผู้เซ็นในนามบริษัทสำเร็จ');
    }
}
