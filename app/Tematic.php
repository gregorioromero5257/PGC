<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Tematic extends Model
{
    //
    use HasApiTokens;

    protected $fillable = [
        'name','company_id'
    ];
    protected $table = 'tematics';

}
