<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
// use Laravel\Passport\HasApiTokens;

class Vacancy extends Model
{
    // use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'short_description', 'requirements', 'description', 'active', 'department_id', 'slug','company','type','validity','experience','offer'
    ];

    public function department(){
        return $this->belongsTo('App\Department');
    }
}
