<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalStepConfig extends Model
{
    protected $fillable = [
        'step_name',
        'approver_id',
        'step_order',
        'is_active',
        'is_auto_sign',
    ];

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id', 'id');
    }
}
