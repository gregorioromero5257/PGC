<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupervisorUsers extends Model
{
    //
    protected $fillable = [
        'user_id', 'supervisor_id'
    ];
    protected $table = 'supervisor_users';
}
