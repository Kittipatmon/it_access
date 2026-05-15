<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalStep extends Model
{
    protected $fillable = [
        'request_form_id',
        'step_order',
        'step_name',
        'approver_id',
        'status',
        'approved_at',
        'remark',
        'signature_path',
        'is_auto_sign',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function requestForm()
    {
        return $this->belongsTo(RequestForm::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
