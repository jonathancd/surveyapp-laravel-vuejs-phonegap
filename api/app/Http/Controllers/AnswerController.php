<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Validator;

use App\Answer;
use App\Factor;
use App\Factor_Result;
use App\Factor_Secundary_Time;
use App\Question;
use App\User;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   

        $user_id = $request->input('user_id');


        foreach($request->answer as $anser_1){
            if(is_array($anser_1)){
                foreach($anser_1 as $answer_2){
                    if(count($answer_2) == 2){
                        $answer = new Answer;
                            $answer->question_id = $answer_2[1];
                            $answer->user_id = $request->input('user_id');
                            $answer->respuesta = $answer_2[0];
                        $answer->save();
                    }
                }
            }
        }


        $factor = Factor::where(['parent' => '-1', 'tipo' => '1'])->first();

        foreach (Factor::where(['parent' => $factor->id, 'tipo' => 2])->get() as $factor_child) {
            $questions = Question::where(['factor_id' => $factor_child->id])->get();
            $points_needed = count($questions) * 5 * 0.60;
            $down_up = 0;
            $user_points = 0;


            foreach($questions as $question){
                $answer = Answer::where(['question_id'=>$question->id,'user_id' => $user_id])->first();
                if($answer){
                    $user_points+= $answer->respuesta;
                }
            }


            //Para saber si quedo arriba o abajo del 70% del factor
            if($user_points >= $points_needed)
                $down_up = 1; //Arriba del 70% del factor
            else
                $down_up = 0; //Abajo del 70% del factor

            $factor_result = new Factor_Result;
                $factor_result->factor_id = $factor_child->id;
                $factor_result->user_id = $user_id;
                $factor_result->type = 1;
                $factor_result->points_needed = $points_needed;
                $factor_result->result = $user_points;
                $factor_result->down_up = $down_up;
                $factor_result->questions = 0;
            $factor_result->save();

        }

        // $factor_result = new Factor_Result;
        //     $factor_result->factor_id = -1;
        //     $factor_result->user_id = $user_id;
        //     $factor_result->type = 0;
        //     $factor_result->points_needed = 0;
        //     $factor_result->result = 0;
        //     $factor_result->down_up = -1;
        //     $factor_result->questions = 0;
        // $factor_result->save();


        $factor_secundary_time = new Factor_Secundary_Time;
            $factor_secundary_time->user_id = $user_id;
            $factor_secundary_time->date_initial = \Carbon::today();
            $factor_secundary_time->date = \Carbon::today();
            $factor_secundary_time->questions_answered = 0;
            $factor_secundary_time->active = 1;
        $factor_secundary_time->save();


        return response()->json([
                'error' => 'Sus respuestas han sido enviadas...',
                'redirect' => '/'
            ]);
    }



    public function store_today_question(Request $request){

        $user_id = $request->input('user_id');
        $question_id = $request->input('question_id');
        $answer = $request->input('answer');
        $factor_result_id = $request->input('factor_result_id');
        $factor_time_id = $request->input('factor_time_id');

        $validator = Validator::make($request->all(), [
            'answer' => 'required|max:5|min:1',
            'user_id' => 'required',
            'factor_result_id' => 'required',
            'factor_id' => 'required',
            'question_id' => 'required'
            // 'factor_time_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first()
            ]);
        } else {

            $factor_result = Factor_Result::find($request->input('factor_result_id'));
            if($factor_result){
                $factor_result->questions += 1;
                $factor_result->save();
            }

            // $factor_user_time = Factor_Secundary_Time::where('user_id', $request->input('user_id'))->first();

            // $factor_user_time = Factor_Secundary_Time::find($factor_result->factor_time_id);
            $factor_user_time = Factor_Secundary_Time::where(['user_id' => $user_id, 'active' => 1])->first();

            if($factor_user_time){
                $factor_user_time->date = \Carbon::today();
                $factor_user_time->questions_answered += 1;
                $factor_user_time->save();
            }



            $answer = new Answer;
                $answer->question_id = $request->input('question_id');
                $answer->user_id = $request->input('user_id');
                $answer->factor_time_id = $factor_user_time->id;
                $answer->respuesta = $request->input('answer');
            $answer->save();



            if($factor_user_time->questions_answered == 30){
                $factor_user_time->active = 0;
                $factor_user_time->save();

                $factors_user_time = Factor_Secundary_Time::where('user_id', $request->input('user_id'))->get();

                if(count($factors_user_time) < 3){

                    $new_factor_secundary_time = new Factor_Secundary_Time;
                        $new_factor_secundary_time->user_id = $user_id;
                        $new_factor_secundary_time->date_initial = \Carbon::today();
                        $new_factor_secundary_time->date = \Carbon::today();
                        $new_factor_secundary_time->questions_answered = 0;
                        $new_factor_secundary_time->active = 1;
                    $new_factor_secundary_time->save();


                    $factors = collect();
                    foreach (Factor::where(['parent' => -1, 'tipo' => 2])->where('categoria', '!=', -1)->get() as $factor) {

                        $factor->questions = collect();
                        $factor->user_points;
                        $questions = Question::where('factor_id', $factor->id)->get();

                        foreach($questions as $question){
                            $answer = Answer::where(['question_id' => $question->id,'user_id' => $user_id, 'factor_time_id' => $factor_user_time->id])->first();

                            if($answer){
                                $factor->questions->push($question);
                                $factor->user_points += $answer->respuesta;
                            }
                        }



                        if(count($factor->questions)>0){
                            $questions_count = count($factor->questions);
                            $points_needed = $questions_count * 5 * 0.60;
                            $user_points = $factor->user_points;

                            //Para saber si quedo arriba o abajo del 60% del factor
                            if($user_points >= $points_needed)
                                $down_up = 1; //Arriba del 60% del factor
                            else
                                $down_up = 0; //Abajo del 60% del factor

                            $new_factor_result = new Factor_Result;
                                $new_factor_result->factor_id = $factor->id;
                                $new_factor_result->user_id = $user_id;
                                $new_factor_result->factor_time_id = $new_factor_secundary_time->id;
                                $new_factor_result->type = 1;
                                $new_factor_result->points_needed = $points_needed;
                                $new_factor_result->result = $user_points;
                                $new_factor_result->down_up = $down_up;
                                $new_factor_result->questions = 0;
                            $new_factor_result->save();
                        }

                    }


                }
            }
            

            return response()->json([
                    'error' => 'Su respuesta ha sido enviada...',
                    'redirect' => '/factor/response/success'
                ]);
        }

        
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
