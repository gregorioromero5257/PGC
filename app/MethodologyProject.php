<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class MethodologyProject extends Model
{
        //
        use HasApiTokens;

        /**
         * The attributes that are mass assignable.
         *
         * @var array
         */
        protected $fillable = [
            'methodology_id','project_id'
        ];
        protected $table = 'methodology_project';
}
