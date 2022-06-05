<?php

namespace App;

// use Laravel\Scout\Searchable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->toArray();

        $array['role'] = ($this->role ? $this->role->name : '');
        $array['roletime'] = ($this->roletime ? $this->roletimes->name : '');
        $array['roleradar'] = ($this->roleradar ? $this->roleradar->name : '');
        $array['position'] = ($this->position ? $this->position->name : '');
        $array['status'] = ($this->active == 0 ? 'Inactivo' : 'Activo');

        return array(
            'name' => $array['name'],
            'email' => $array['email'],
            'role' => $array['role'],
            'roletime' => $array['roletime'],
            'roleradar' => $array['roleradar'],
            'position' => $array['position'],
            'avatar' => $array['avatar'],
            'active' => $array['active'],
            'created_at' => $array['created_at'],
            'status' => $array['status'],
            'organizacion' => $array['organizacion'],
        );
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'role_id', 'position_id',
        'avatar', 'bio_es', 'bio_en', 'active', 'password', 'google_id',
        'role_time_id','role_radar_id','organizacion',
        'supervisor_id','tipo_personal','tipo_contratacion','roles','pronombre',
        'date_in','hours_daily'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // public static function boot()
    // {
    //     parent::boot();
    //
    //     static::saved(function ($model) {
    //         $model->projects->filter(function ($item) {
    //             return $item->shouldBeSearchable();
    //         })->searchable();
    //     });
    // }

    public function files()
    {
        return $this->hasMany('App\UserFile');
    }

    public function projects()
    {
        return $this->belongsToMany('App\Project', 'member_project', 'user_id', 'project_id')->with('clients');
    }

    public function stackeholders()
    {
        return $this->hasMany('App\Stackeholder');
    }

    public function member_projects()
    {
        return $this->belongsToMany('App\Project', 'member_project', 'user_id', 'project_id');
    }

    public function role(){
        return $this->belongsTo('App\Role');
    }

    public function role_radar(){
        return $this->belongsTo('App\RoleRadar');
    }

    public function role_time(){
        return $this->belongsTo('App\RoleTime');
    }

    public function position(){
        return $this->belongsTo('App\Position');
    }

    public function supervisor(){
        return $this->belongsTo('App\User');
    }

    public function user_aditional_data(){
        return $this->belongsTo('App\UserAditionalData');
    }
}
