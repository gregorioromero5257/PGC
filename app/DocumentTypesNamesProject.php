<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class DocumentTypesNamesProject extends Model
{
  //
  use HasApiTokens;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
    protected $fillable = [
        'data', 'project_id'
    ];
    protected $table = 'document_types_names_project';


    public function project()
    {
      return $this->belongsTo('App\Project');
    }
}
