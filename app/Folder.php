<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Folder extends Model
{
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'order', 'upperfolder_id'
    ];

    public function resources()
    {
        return $this->hasMany('App\Resource')->with('folder');
    }

    public function folders()
    {
        return $this->hasMany('App\Folder', 'upperfolder_id', 'id');
    }

    public function folder()
    {
        return $this->belongsTo('App\Folder', 'upperfolder_id');
    }
}
