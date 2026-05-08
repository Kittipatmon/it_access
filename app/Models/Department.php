<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $connection = 'userkmlnew';
    protected $table = 'departments';
    protected $fillable = ['id', 'name'];
}
