<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class FunderProject extends Model
{
    //
    //
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'stackeholder_id', 'project_id'
    ];
    protected $table = 'funder_project';

    public function user()
    {
        return $this->belongsTo('App\Project');
    }
}
