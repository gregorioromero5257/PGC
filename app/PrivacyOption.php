<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class PrivacyOption extends Model
{
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    public function projects()
    {
        return $this->belongsToMany('App\Project', 'privacy_option_project', 'privacy_option_id', 'project_id');
    }
}
