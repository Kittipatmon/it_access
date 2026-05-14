<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequestFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'request_type' => 'required|string|in:new_employee,resign,position_change,transfer,add_remove_access',


            // Personal Info (Snapshot fields)
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'nickname_th' => 'required|string|max:255',
            'firstname_en' => 'required|string|max:255',
            'lastname_en' => 'required|string|max:255',
            'nickname_en' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'phone_ext' => 'nullable|string|max:255', // Ext remains nullable as it might not exist
            'emp_code' => 'required|string|max:255',
            'position_level' => 'required|string',
            'position_level_other' => 'required_if:position_level,other|nullable|string',
            'position_name' => 'required|string|max:255',
            'department_name' => 'required|string|max:255',
            'division_name' => 'required|string|max:255',

            // Access
            'system_access' => 'nullable|array',
            'program_access' => 'nullable|array',
            'equipment_access' => 'nullable|array',
            
            // Allow any dynamic access fields, custom fields, and sub options
            '*_access' => 'nullable|array',
            '*_access_other' => 'nullable|string',
            'custom_fields' => 'nullable|array',
            'sub_options' => 'nullable|array',

            'details' => 'nullable|string',
            'signature' => 'required|string',
            'existing_signature' => 'nullable|string',
            'signature_file' => 'nullable|image|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'request_type.required' => 'กรุณาเลือกประเภทคำร้อง',

            'firstname.required' => 'กรุณาระบุชื่อ',
            'lastname.required' => 'กรุณาระบุนามสกุล',
            'nickname_th.required' => 'กรุณาระบุชื่อเล่น',
            'firstname_en.required' => 'กรุณาระบุชื่อ (ภาษาอังกฤษ)',
            'lastname_en.required' => 'กรุณาระบุนามสกุล (ภาษาอังกฤษ)',
            'nickname_en.required' => 'กรุณาระบุชื่อเล่น (ภาษาอังกฤษ)',
            'phone.required' => 'กรุณาระบุเบอร์โทรศัพท์',
            'emp_code.required' => 'กรุณาระบุรหัสพนักงาน',
            'position_level.required' => 'กรุณาเลือกระดับตำแหน่ง',
            'position_name.required' => 'กรุณาระบุชื่อตำแหน่ง',
            'department_name.required' => 'กรุณาระบุแผนก',
            'division_name.required' => 'กรุณาระบุฝ่าย',
            'signature.required' => 'กรุณาลงลายมือชื่อ',
            'signature_file.image' => 'ไฟล์ที่อัปโหลดต้องเป็นรูปภาพเท่านั้น',
            'signature_file.max' => 'ขนาดไฟล์รูปภาพห้ามเกิน 2MB',
        ];
    }
}
