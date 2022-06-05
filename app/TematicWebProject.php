<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class TematicWebProject extends Model
{
    //
    use HasApiTokens;

    protected $fillable = [
        'tematic_id','project_id','name'
    ];
    protected $table = 'tematics_web';

    public function project()
    {
        return $this->belongsTo('App\Project');
    }

}
