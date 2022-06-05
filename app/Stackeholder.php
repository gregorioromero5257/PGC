<?php

namespace App;

// use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Stackeholder extends Model
{
    // use Searchable;
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'type',
        'name',
        'sh_type_id',
        'sh_description_id'
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

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function sh_type()
    {
        return $this->belongsTo('App\ShType', 'sh_type_id');
    }

    public function sh_description()
    {
        return $this->belongsTo('App\ShDescription', 'sh_description_id');
    }

    public function contacts()
    {
        return $this->hasMany('App\Contact');
    }

    public function projects()
    {
        return $this->belongsToMany('App\Project', 'client_project', 'stackeholder_id', 'project_id');
    }
}
