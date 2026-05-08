<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestForm extends Model
{
    protected $fillable = [
        'request_no',
        'user_id',
        'status',
        'current_step',
        'approved_at',
        'details',
        // ส่วนที่ 1: ประเภทคำร้อง + ข้อมูลส่วนตัว
        'request_type',
        'emp_code',
        'firstname',
        'lastname',
        'nickname_th',
        'firstname_en',
        'lastname_en',
        'nickname_en',
        'phone',
        'phone_ext',
        'position_level',
        'position_name',
        'department_name',
        'division_name',
        // ส่วนที่ 2: การเข้าถึง
        'system_access',
        'program_access',
        'signature_path',
    ];

    protected $casts = [
        'system_access' => 'array',
        'program_access' => 'array',
        'approved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function steps()
    {
        return $this->hasMany(ApprovalStep::class)->orderBy('step_order');
    }

    public function histories()
    {
        return $this->hasMany(ApprovalHistory::class)->latest();
    }
}
