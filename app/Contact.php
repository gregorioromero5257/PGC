<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Contact extends Model
{
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'stackeholder_id',
        'name',
        'email',
        'position',
        'phone',
        'project_id',
        'type',
    ];

    public function stackeholder()
    {
        return $this->belongsTo('App\Stackeholder');
    }
}
