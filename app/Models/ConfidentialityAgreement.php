<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfidentialityAgreement extends Model
{
    protected $fillable = [
        'request_form_id',
        'user_id',
        'prefix',
        'full_name',
        'age',
        'id_card_no',
        'address_no',
        'soi',
        'road',
        'tambon',
        'amphoe',
        'province',
        'contact_no',
        'employee_signature',
        'company_signature',
        'witness1_signature',
        'witness2_signature',
        'witness1_name',
        'witness2_name',
        'witness1_user_id',
        'witness2_user_id',
        'witness1_agreed_at',
        'witness2_agreed_at',
        'company_agreed_at',
        'is_auto_sign',
        'agreement_date',
    ];

    protected $casts = [
        'agreement_date' => 'datetime',
        'witness1_agreed_at' => 'datetime',
        'witness2_agreed_at' => 'datetime',
        'company_agreed_at' => 'datetime',
        'is_auto_sign' => 'boolean',
    ];

    public function requestForm()
    {
        return $this->belongsTo(RequestForm::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function witness1()
    {
        return $this->belongsTo(User::class, 'witness1_user_id');
    }

    public function witness2()
    {
        return $this->belongsTo(User::class, 'witness2_user_id');
    }
}
