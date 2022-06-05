<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class ProjectCode extends Model
{
  use HasApiTokens;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'codigo', 'organizacion', 'categoria', 'comentarios', 'project_id'
  ];

  public function project()
  {
      return $this->belongsTo('App\Project');
  }
}
