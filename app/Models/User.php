<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    protected $connection = 'userkmlnew';
    protected $table = 'employees';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'emp_code',
        'firstname',
        'lastname',
        'nickname_th',
        'first_name_en',
        'last_name_en',
        'nickname_en',
        'phone',
        'dept_id',
        'position',
        'status',
        'role',
        'profile_pic',
        'signature',
    ];

    protected $appends = ['fullname', 'department_name', 'signature_url'];

    public function getDepartmentNameAttribute(): string
    {
        return $this->department_rel ? $this->department_rel->name : 'N/A';
    }


    public function getUsertypeAttribute()
    {
        return (object) [
            'description' => ($this->role === 'admin' ? 'Administrator' : 'Employee')
        ];
    }

    public function getFullnameAttribute(): string
    {
        return trim(($this->attributes['firstname'] ?? '') . ' ' . ($this->attributes['lastname'] ?? ''));
    }

    public function getNameAttribute()
    {
        return $this->fullname;
    }

    public function department_rel() // Renamed from department to avoid conflict with field
    {
        return $this->belongsTo(Department::class, 'dept_id', 'id');
    }

    // Accessors for fields no longer in appkum_user.employees
    public function getDivisionAttribute()
    {
        return (object) ['division_name' => ''];
    }
    public function getSectionAttribute()
    {
        return (object) ['section_code' => '', 'section_name' => ''];
    }

    // Compatibility Accessors for code still using old attribute names
    public function getPhotoUserAttribute()
    {
        return $this->attributes['profile_pic'] ?? null;
    }
    public function getEmployeeCodeAttribute()
    {
        return $this->attributes['emp_code'] ?? null;
    }
    public function getDepartmentIdAttribute()
    {
        return $this->attributes['dept_id'] ?? null;
    }
    public function getFirstNameAttribute()
    {
        return $this->attributes['firstname'] ?? null;
    }
    public function getLastNameAttribute()
    {
        return $this->attributes['lastname'] ?? null;
    }
    public function getPositionAttribute()
    {
        return '';
    }
    public function getHrStatusAttribute()
    {
        return (int) ($this->attributes['dept_id'] ?? 0) === 14 ? 1 : 0;
    }
    public function getLevelUserAttribute()
    {
        if (($this->attributes['role'] ?? '') === 'admin')
            return 10;
        if (in_array($this->attributes['dept_id'] ?? 0, [14, 16]))
            return 3;
        return 1;
    }

    public function setLevelUserAttribute($value)
    {
        // For compatibility with code that still sets level_user
        if ($value >= 10 || $value === 'admin') {
            $this->attributes['role'] = 'admin';
        } else {
            $this->attributes['role'] = 'staff';
        }
    }
    public function getSignatureUpdatedAtAttribute()
    {
        if ($this->signature && \Illuminate\Support\Facades\Storage::disk('public')->exists('signatures/' . $this->signature)) {
            $timestamp = \Illuminate\Support\Facades\Storage::disk('public')->lastModified('signatures/' . $this->signature);
            return \Carbon\Carbon::createFromTimestamp($timestamp)->timezone('Asia/Bangkok');
        }
        return null;
    }

    public function getSignatureUrlAttribute()
    {
        if (!$this->signature) return null;

        $localPath = 'signatures/' . $this->signature;
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($localPath)) {
            return asset('storage/' . $localPath);
        }

        return 'https://appkum.kumwell.com/storage/signatures/' . $this->signature;
    }
}
