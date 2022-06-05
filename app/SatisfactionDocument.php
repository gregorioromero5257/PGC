<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class SatisfactionDocument extends Model
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
        return $this->belongsToMany('App\Project', 'project_satisfaction_document', 'satisfaction_document_id', 'project_id');
    }
}
