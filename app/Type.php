<?php

namespace App;

// use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Type extends Model
{
    // use Searchable;
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
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

    public function projects()
    {
        return $this->belongsToMany('App\Project');
    }
}
