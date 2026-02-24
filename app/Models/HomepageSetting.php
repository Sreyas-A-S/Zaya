<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageSetting extends Model
{

      protected $fillable = [
        'language',
        'header_title',
        'header_subtitle',
        'description',

         'key',
          'value',
           'language',
            'type',
    ];
  

}
