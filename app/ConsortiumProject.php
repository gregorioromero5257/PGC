<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class ConsortiumProject extends Model
{
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'project_id','company','company_represent','actions','final_client'
    ];

    public function project()
    {
        return $this->belongsTo('App\Project');
    }
}
