<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'name',
        'email',
        'department',
        'salary',
        'join_date',
        'status',
    ];

    protected $dates = [
        'join_date',
        'created_at',
        'updated_at',
    ];
}
