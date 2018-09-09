<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Question extends Model
{
    public static function getRandomQuestion($questions){
        $rand = rand(0, count($questions)-1);
        return $questions[$rand];
    }
}
