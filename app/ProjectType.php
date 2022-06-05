<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class ProjectType extends Model
{
      //
      use HasApiTokens;

      /**
       * The attributes that are mass assignable.
       *
       * @var array
       */
      protected $fillable = [
          'type_id','project_id'
      ];
      protected $table = 'project_type';
}
