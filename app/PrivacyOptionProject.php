<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class PrivacyOptionProject extends Model
{
    //
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'privacy_option_id','project_id'
    ];
    protected $table = 'privacy_option_project';
}
