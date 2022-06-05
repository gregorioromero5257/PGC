<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class ProjectSatisfactionDocument extends Model
{
      //
      use HasApiTokens;

      /**
       * The attributes that are mass assignable.
       *
       * @var array
       */
      protected $fillable = [
          'satisfaction_document_id','project_id'
      ];
      protected $table = 'project_satisfaction_document';
}
