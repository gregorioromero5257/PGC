<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class UserAditionalData extends Model
{
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'organization','position','type','supervisor','workday'
    ];
    protected $table = 'user_aditional_data';

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
