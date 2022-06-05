<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class ConsortionProject extends Model
{
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
  protected $table = 'project_consortion';

  public function user()
  {
      return $this->belongsTo('App\Project');
  }
}
