<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Factor extends Model
{
    public static function getCategory ($type) {
    	switch ($type) {
    		case 1:
    			return 'Ambiente – Entorno (escolar, familiar, comunitario)';
    			break;

    		case 2:
    			return 'Normativas (Legales – Culturales)';
    			break;

    		case 3:
    			return 'Satisfactores básicos';
    			break;

    		case 4:
    			return 'Violencia – Ilegalidad';
    			break;

    		case 5:
    			return 'Conciliación';
    			break;
    	}
    }


    public static function getFactorsDownUpInGeneral(){
        $factors_general = DB::select('select factors.id, 
                                        SUM(CASE WHEN factor__results.down_up = 1 THEN 1 ELSE 0 END) as factors_up, 
                                        SUM(CASE WHEN factor__results.down_up = 0 THEN 1 ELSE 0 END) as factors_down
                                        FROM factors 
                                        JOIN factor__results on factor__results.factor_id = factors.id 
                                        WHERE factor__results.type=1
                                        GROUP BY factors.id');

        return $factors_general;
    }


    public static function getFactorQuestionsAnsweredByUser($factor_id, $user_id){
        $factors_general = DB::select('select factors.id, 
                                        SUM(CASE WHEN answers.user_id = '.$user_id.' THEN 1 ELSE 0 END) as answers
                                        FROM factors 
                                        JOIN questions on questions.factor_id = factors.id 
                                        JOIN answers on answers.question_id = questions.id 
                                        WHERE factors.id='.$factor_id.' 
                                        AND answers.user_id = '.$user_id.' 
                                        GROUP BY factors.id');

        if(count($factors_general)>0)
            return intval($factors_general[0]->answers);
        else
            return 0;
    }


            //Igual que arriba pero para cuando se responde la segunda encuesta por segunda o tercera vez
    public static function getFactorQuestionsAnsweredByUser_per_time($factor_id, $user_id, $factor_time_id){
        $factors_general = DB::select('select factors.id, 
                                        SUM(CASE WHEN answers.user_id = '.$user_id.' THEN 1 ELSE 0 END) as answers
                                        FROM factors 
                                        JOIN questions on questions.factor_id = factors.id 
                                        JOIN answers on answers.question_id = questions.id 
                                        WHERE factors.id='.$factor_id.' 
                                        AND answers.user_id = '.$user_id.'
                                        AND answers.factor_time_id = '.$factor_time_id.'
                                        GROUP BY factors.id');

        if(count($factors_general)>0)
            return intval($factors_general[0]->answers);
        else
            return 0;
    }




    public static function getTodayFactorWhenUpAndDown_down($factors_down, $questions_count,$x,$y,$z){
        $factors_by_down = $questions_count / count($factors_down);
                            //21
        if(is_int($factors_by_down)){
            foreach($factors_down as $key=>$factor_down){
                
                if($factor_down->questions < $factors_by_down){

                    return $object = (object) ['factor_result' => $factor_down, 'factor_secundary' =>  $factor_down->factor_secundary];
                }
            }
            return -1;
        }else{

            switch (count($factors_down)) {
                case 2:
                    foreach($factors_down as $key=>$factor_down){
                        if($key != count($factors_down)-1){
                            //10
                            if($factor_down->questions < $x && $factor_down->questions < $factor_down->factor_secundary->questions){

                                return $object = (object) ['factor_result' => $factor_down, 'factor_secundary' =>  $factor_down->factor_secundary];
                            }
                        }else{
                            //11
                            if($factor_down->questions < $x+1 && $factor_down->questions < $factor_down->factor_secundary->questions){

                                return $object = (object) ['factor_result' => $factor_down, 'factor_secundary' =>  $factor_down->factor_secundary];
                            }
                        }
                    }
                    return -1;
                    break;

                case 4:
                    foreach($factors_down as $key=>$factor_down){
                        if($key != count($factors_down)-1){
                            //5
                            if($factor_down->questions < $y && $factor_down->questions < $factor_down->factor_secundary->questions){

                                return $object = (object) ['factor_result' => $factor_down, 'factor_secundary' =>  $factor_down->factor_secundary];
                            }
                        }else{
                            //6
                            if($y == 5){
                                if($factor_down->questions<$y+1 && $factor_down->questions < $factor_down->factor_secundary->questions){

                                    return $object = (object) ['factor_result' => $factor_down, 'factor_secundary' =>  $factor_down->factor_secundary];
                                }
                            }else{
                                if($factor_down->questions<$y+2 && $factor_down->questions < $factor_down->factor_secundary->questions){

                                    return $object = (object) ['factor_result' => $factor_down, 'factor_secundary' =>  $factor_down->factor_secundary];
                                }
                            }
                        }
                    }
                    return -1;
                    break;

                case 5:
                    foreach($factors_down as $key=>$factor_down){
                        if($key != count($factors_down)-1){
                            //4
                            if($factor_down->questions<$z && $factor_down->questions < $factor_down->factor_secundary->questions){

                                return $object = (object) ['factor_result' => $factor_down, 'factor_secundary' =>  $factor_down->factor_secundary];
                            }
                        }else{
                            //5
                            if($factor_down->questions<$z+1 && $factor_down->questions < $factor_down->factor_secundary->questions){

                                return $object = (object) ['factor_result' => $factor_down, 'factor_secundary' =>  $factor_down->factor_secundary];
                            }
                        }
                    }
                    return -1;
                    break;
            }
        }

        return -1;
    }

    /*----- Chequear esta function.. la misma que digo en el controller-----*/
    public static function getTodayFactorWhenUpAndDown_up($factors_up){

        foreach($factors_up as $key=>$factor_up){
            if($factor_up->questions < 3  && $factor_up->questions < $factor_up->factor_secundary->questions){
 

                return $object = (object) ['factor_result' => $factor_up, 'factor_secundary' =>  $factor_up->factor_secundary];
                return $factor_up;
            }

            // if($factor_up->questions==3)
                // return -1;
        }

        return -1;
    }


    /*----- Para cuando hay que agarrar una pregunta que cubra el 70% "y" todos los factores o quedaron por encima o por debajo-----*/
    public static function getTodayFactor($factors_up,$factors_down,$questions_count,$type) {
        if(count($factors_up)>0 && count($factors_down)==0)
            $sw = 1;
        else if(count($factors_up)==0 && count($factors_down)>0)
            $sw = 2;

        switch ($sw) {
            case 1:
                foreach($factors_up as $key=>$factor_up){
                    if($key != count($factors_up)-1){
                        if($factor_up->questions < $questions_count){
                            
                            return $object = (object) ['factor_result' => $factor_up, 'factor_secundary' =>  $factor_up->factor_secundary];

                            return $factor_up;
                        }
                    }else{
                        if($type == 1){
                            if($factor_up->questions < $questions_count-1 && $factor_up->questions < $factor_up->factor_secundary->questions){
                                
                                return $object = (object) ['factor_result' => $factor_up, 'factor_secundary' =>  $factor_up->factor_secundary];

                                return $factor_up;
                            }
                        }else{
                            if($factor_up->questions < $questions_count+1 && $factor_up->questions < $factor_up->factor_secundary->questions){
                                
                                return $object = (object) ['factor_result' => $factor_up, 'factor_secundary' =>  $factor_up->factor_secundary];

                                return $factor_up;
                            }
                        }
                    }
                }

                break;
            case 2:
                foreach($factors_down as $key=>$factor_down){
                    if($key != count($factors_down)-1){
                        if($factor_down->questions<$questions_count){

                            return $object = (object) ['factor_result' => $factor_down, 'factor_secundary' =>  $factor_down->factor_secundary];

                        }
                    }else{
                        if($type == 1){
                            
                            if($factor_down->questions<$questions_count-1 && $factor_down->questions < $factor_down->factor_secundary->questions){

                                return $object = (object) ['factor_result' => $factor_down, 'factor_secundary' =>  $factor_down->factor_secundary];

                            }

                        }else{
                            
                            if($factor_down->questions<$questions_count+1 && $factor_down->questions < $factor_down->factor_secundary->questions){
                                
                                return $object = (object) ['factor_result' => $factor_down, 'factor_secundary' =>  $factor_down->factor_secundary];
                            }
                        }
                    }
                }

                break;
        }

        return -1;
    }





        /*----- Para cuando hay que agarrar una pregunta que cubra el 20% del desempeño de la institucion pero nadie ha respondido la encuesta-----*/
    public static function getTodayFactorGeneralPartWhen_first($factors_up,$factors_down,$questions_count) {
        if(count($factors_up)>0 && count($factors_down)==0)
            $sw = 1;
        else if(count($factors_up)==0 && count($factors_down)>0)
            $sw = 2;

        switch ($sw) {
            case 1:
                foreach($factors_up as $key=>$factor_up){

                    if($factor_up->factor_time->questions_answered < 6){
                        if($factor_up->factor_secundary->answered < $factor_up->factor_secundary->questions){

                            return $object = (object) ['factor_result' => $factor_up, 'factor_secundary' =>  $factor_up->factor_secundary];
                        }
                    }
                }
                return -1;

            case 2:
                foreach($factors_down as $key=>$factor_down){

                    if($factor_down->factor_time->questions_answered < 6){
                        
                        if($factor_down->factor_secundary->answered < $factor_down->factor_secundary->questions){

                            return $object = (object) ['factor_result' => $factor_down, 'factor_secundary' =>  $factor_down->factor_secundary];
                        }

                    }
                }
                return -1;
        }
        return -1;
    }


/*------------------------------------------------------------  ------------------------------------------------------------*/


        /*----- Para cuando hay que agarrar una pregunta que cubra el 20% del desempeño de la institucion -----*/
    public static function getTodayFactorGeneralPartWhen_second($factors_up,$factors_down) {
        if(count($factors_up)>0 && count($factors_down)==0)
            $sw = 1;
        else if(count($factors_up)==0 && count($factors_down)>0)
            $sw = 2;

        switch ($sw) {
            case 1:
                foreach($factors_up as $key=>$factor_up){

                    if($factor_up->factor_time->questions_answered < 6){

                        if($factor_up->factor_secundary->answered < $factor_up->factor_secundary->questions){

                            return $object = (object) ['factor_result' => $factor_up, 'factor_secundary' =>  $factor_up->factor_secundary];
                        }

                    }
                }
                return -1;
            case 2:
                foreach($factors_down as $key=>$factor_down){

                    if($factor_down->factor_time->questions_answered < 6){

                        if($factor_down->factor_secundary->answered < $factor_down->factor_secundary->questions){

                            return $object = (object) ['factor_result' => $factor_down, 'factor_secundary' =>  $factor_down->factor_secundary];
                        }
                    }
                }
                return -1;
        }
        return -1;
    }




    public static function getTodayFactorGeneral_down($factors_down){

        foreach($factors_down as $key=>$factor_down){

            if($factor_down->factor_time->questions_answered < 6){

                if($factor_down->factor_secundary->answered <  $factor_down->factor_secundary->questions){

                    return $object = (object) ['factor_result' => $factor_down, 'factor_secundary' =>  $factor_down->factor_secundary];
                }
            }
        }

        return -1;
    }


    public static function getTodayFactorGeneral_up($factors_up){

        foreach($factors_up as $key=>$factor_up){

            if($factor_up->factor_time->questions_answered < 6){

                if($factor_up->factor_secundary->answered < $factor_up->factor_secundary->questions){

                    return $object = (object) ['factor_result' => $factor_up, 'factor_secundary' =>  $factor_up->factor_secundary];
                }
            }
        }

        return -1;
    }













        //COPIANDO CODIGO PARA QUE SE PUEDA RESPONDER LA SEGUNDA ENCUESTA 3 VECES. ES EL MISMO CODIGO PERO VOY A CAMBIAR VARIABLES O CONDICIONES PARA NO ARRUINAR LAS FUNCIONES


    /*----- Para cuando hay que agarrar una pregunta que cubra el 70% "y" todos los factores o quedaron por encima o por debajo-----*/
    public static function getTodayFactor_second_time($factors_up,$factors_down) {
        if(count($factors_up)>0 && count($factors_down)==0)
            $sw = 1;
        else if(count($factors_up)==0 && count($factors_down)>0)
            $sw = 2;

        switch ($sw) {
            case 1:
                    switch (count($factors_up) ){
                        case 1:
                            foreach($factors_up as $key=>$factor_up){
                                if($factor_up->questions < 21){
                                    return $object = (object) ['factor_result' => $factor_up, 'factor_secundary' =>  $factor_up->factor_secundary];
                                }
                            }

                            return -1;
                            break;
                        case 2:
                            foreach($factors_up as $key=>$factor_up){

                                if($key != count($factors_up)-1){
                                    if($factor_up->questions < 10){
                                        return $object = (object) ['factor_result' => $factor_up, 'factor_secundary' =>  $factor_up->factor_secundary];
                                    }
                                }else{
                                    if($factor_up->questions < 11){
                                        return $object = (object) ['factor_result' => $factor_up, 'factor_secundary' =>  $factor_up->factor_secundary];
                                    }
                                }
                            }

                            return -1;
                            break;
                        case 3:
                            foreach($factors_up as $key=>$factor_up){
                                if($factor_up->questions < 7){
                                    return $object = (object) ['factor_result' => $factor_up, 'factor_secundary' =>  $factor_up->factor_secundary];
                                }
                            }

                            return -1;
                            break;
                        case 4:
                            foreach($factors_up as $key=>$factor_up){

                                if($key != count($factors_up)-1){
                                    if($factor_up->questions < 5){
                                        return $object = (object) ['factor_result' => $factor_up, 'factor_secundary' =>  $factor_up->factor_secundary];
                                    }
                                }else{
                                    if($factor_up->questions < 6){
                                        return $object = (object) ['factor_result' => $factor_up, 'factor_secundary' =>  $factor_up->factor_secundary];
                                    }
                                }
                            }

                            return -1;
                            break;
                        case 5:
                            foreach($factors_up as $key=>$factor_up){

                                if($key != count($factors_up)-1){
                                    if($factor_up->questions < 4){
                                        return $object = (object) ['factor_result' => $factor_up, 'factor_secundary' =>  $factor_up->factor_secundary];
                                    }
                                }else{
                                    if($factor_up->questions < 5){
                                        return $object = (object) ['factor_result' => $factor_up, 'factor_secundary' =>  $factor_up->factor_secundary];
                                    }
                                }
                            }

                            return -1;
                            break;
                    }

            case 2:
                    switch (count($factors_down) ){
                        case 1:
                            foreach($factors_down as $key=>$factor_down){
                                if($factor_down->questions < 21){
                                    return $object = (object) ['factor_result' => $factor_down, 'factor_secundary' =>  $factor_down->factor_secundary];
                                }
                            }

                            return -1;
                            break;
                        case 2:
                            foreach($factors_down as $key=>$factor_down){

                                if($key != count($factors_down)-1){
                                    if($factor_down->questions < 10){
                                        return $object = (object) ['factor_result' => $factor_down, 'factor_secundary' =>  $factor_down->factor_secundary];
                                    }
                                }else{
                                    if($factor_down->questions < 11){
                                        return $object = (object) ['factor_result' => $factor_down, 'factor_secundary' =>  $factor_down->factor_secundary];
                                    }
                                }
                            }

                            return -1;
                            break;
                        case 3:
                            foreach($factors_down as $key=>$factor_down){
                                if($factor_down->questions < 7){
                                    return $object = (object) ['factor_result' => $factor_down, 'factor_secundary' =>  $factor_down->factor_secundary];
                                }
                            }

                            return -1;
                            break;
                        case 4:
                            foreach($factors_down as $key=>$factor_down){

                                if($key != count($factors_down)-1){
                                    if($factor_down->questions < 5){
                                        return $object = (object) ['factor_result' => $factor_down, 'factor_secundary' =>  $factor_down->factor_secundary];
                                    }
                                }else{
                                    if($factor_down->questions < 6){
                                        return $object = (object) ['factor_result' => $factor_down, 'factor_secundary' =>  $factor_down->factor_secundary];
                                    }
                                }
                            }

                            return -1;
                            break;
                        case 5:
                            foreach($factors_down as $key=>$factor_down){

                                if($key != count($factors_down)-1){
                                    if($factor_down->questions < 4){
                                        return $object = (object) ['factor_result' => $factor_down, 'factor_secundary' =>  $factor_down->factor_secundary];
                                    }
                                }else{
                                    if($factor_down->questions < 5){
                                        return $object = (object) ['factor_result' => $factor_down, 'factor_secundary' =>  $factor_down->factor_secundary];
                                    }
                                }
                            }

                            return -1;
                            break;
                    }
        }
        return -1;
    }





}
