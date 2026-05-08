<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalHistory extends Model
{
    protected $fillable = [
        'request_form_id',
        'action_by',
        'action_type',
        'remark',
        'signature_path',
    ];

    public function requestForm()
    {
        return $this->belongsTo(RequestForm::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'action_by');
    }
}
