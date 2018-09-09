<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Validator;

use App\User;

use App\Factor;
use App\Factor_Result;
use App\Factor_Secundary_Time;
use App\Answer;
use App\Question;

class Factor_Secundary_TimeController extends Controller
{

    public function check_date($id){
    	$user = User::find($id);

    	if($user){

    		// $factor_time = Factor_Secundary_Time::where('user_id', $user->id)->first();
    		$factor_time = Factor_Secundary_Time::where(['user_id' => $user->id, 'active' => 1])->where('questions_answered', '<', 30)->first();

    		if($factor_time){
    			if(count($factor_time)>0)

		    		$now = \Carbon::today();

		    		if(\Carbon::parse($factor_time->date)->eq($now) ){
			    		return response()->json([
			    				'status' => 200,
			    				'date_status' => 0
			    			]);
		    		}else{
		    			return response()->json([
			    				'status' => 200,
			    				'date_status' => 1
			    			]);
		    		}

			}
			else{
	    		return response()->json([
	    				'status' => 404,
    					'msj' => 'Encuestas completadas'
	    			]);
	    	}
    	}else{
    		return response()->json([
    				'status' => 404,
    				'msj' => 'Usuario no encontrado'
    			]);
    	}
    }
}
