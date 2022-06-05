<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class LinkedProject extends Model
{
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_link_id','project_id','code'
    ];

    public function project()
    {
        return $this->belongsTo('App\Project');
    }
}
