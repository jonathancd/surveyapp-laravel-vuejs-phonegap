<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class User extends Model
{
    protected $table = 'users';


    public function program(){
        return $this->belongsTo('App\Program', 'program_id');
    }


    public static function getPointsByFactors($user_id){
        $results = Factor::select(\DB::raw('factors.*, SUM(answers.respuesta) as points'))
				         ->leftJoin('questions', 'questions.factor_id', '=', 'factors.id')
				         ->leftJoin('answers', 'answers.question_id', '=', 'questions.id')
				         ->where('answers.user_id', '=', $user_id)
				         ->where('factors.parent','<>','-1')
				         ->groupBy('factors.id')
				         ->get();

        return $results;
    }


    public function getAge(){
    	$today = \Carbon::today();

    	return $today->diffInYears(\Carbon::parse($this->fecha_nacimiento));
    }


    public static function getFactorResult($factor){
    	$questions = DB::table('questions')->where('factor_id',$factor->id)->get();
    	$result =  ($factor->points*100) / (count($questions)*5);

    	if($result > 80){
    		// return "Muy Suficiente";
    		return $object = (object) ['text' => "Muy Suficiente", 'value' =>  1, 'percent' => $result];
    	}else if($result > 60 && $result <= 80){
    		// return "Suficiente";
    		return $object = (object) ['text' => "Suficiente", 'value' =>  2, 'percent' => $result];
    	}else if($result > 40 && $result <= 60){
    		// return "Insuficiente";
    		return $object = (object) ['text' => "Insuficiente", 'value' =>  3, 'percent' => $result];
    	}else if($result > 20 && $result <= 40){
    		// return "Deficiente";
    		return $object = (object) ['text' => "Deficiente", 'value' =>  4, 'percent' => $result];
    	}else if($result <= 20){
    		// return "Muy Deficiente";
    		return $object = (object) ['text' => "Muy Deficiente", 'value' =>  5, 'percent' => $result];
    	}
	
    	return $object = (object) ['text' => "N/A", 'value' =>  -1, 'percent' => ''];
    }



    public static function getFactorSecundaryResult($factor, $factor_time, $user){
        $questions_count = 0;
        $points=0;

        $questions = DB::table('questions')->where('factor_id',$factor->id)->get();
        foreach ($questions as $key => $question) {
            $answer = DB::table('answers')->where(['question_id'=>$question->id, 'factor_time_id' => $factor_time->id, 'user_id' => $user->id])->first();

            if($answer){
                $questions_count+= 1;
                $points+= $answer->respuesta; 
            }
        }


        if($questions_count > 0)
            $result =  ($points*100) / ($questions_count*5);
        else
            $result = 0;


        if($result > 80){
            // return "Muy Suficiente";
            return $object = (object) ['text' => "Muy Suficiente", 'value' =>  1, 'percent' => $result];
        }else if($result > 60 && $result <= 80){
            // return "Suficiente";
            return $object = (object) ['text' => "Suficiente", 'value' =>  2, 'percent' => $result];
        }else if($result > 40 && $result <= 60){
            // return "Insuficiente";
            return $object = (object) ['text' => "Insuficiente", 'value' =>  3, 'percent' => $result];
        }else if($result > 20 && $result <= 40){
            // return "Deficiente";
            return $object = (object) ['text' => "Deficiente", 'value' =>  4, 'percent' => $result];
        }else if($result <= 20){
            // return "Muy Deficiente";
            return $object = (object) ['text' => "Muy Deficiente", 'value' =>  5, 'percent' => $result];
        }
    
        return $object = (object) ['text' => "N/A", 'value' =>  -1, 'percent' => ''];
    }

}
