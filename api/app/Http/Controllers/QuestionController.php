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

class QuestionController extends Controller
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
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|max:150|min:4',
            'factor_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first()
            ]);
        } else {
            $question = new Question;
                $question->factor_id = $request->input('factor_id');
                $question->titulo = $request->input('titulo');
                $question->estado = 1;
            $question->save();

            return response()->json([
                'error' => 'Pregunta creada exitosamente...',
                'redirect' => '/factor/questions/'.$question->factor_id
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



    public function show_today($id)
    {
        $user = User::find($id);

        if($user){
            $factor_times = Factor_Secundary_Time::where('user_id',$user->id)->get();

            if(count($factor_times)==0){

                return response()->json([
                        'status' => 202,
                        'code' => 5,
                        'ready' => true,
                        'msj' => 'Aun no ha respondido la encuesta principal'
                    ]);

            }else if(count($factor_times)<4){

                $factor_time = Factor_Secundary_Time::where(['user_id' => $user->id, 'active' => 1])->where('questions_answered', '<', 30)->first();

                if($factor_time){
                    if($factor_time->questions_answered < 30){

                        return response()->json([
                                'status' => 202,
                                'code' => 4,
                                'ready' => false,
                                'msj' => 'Este usuario aun no ha respondido la encuesta'
                            ]);
                    }else{
                            return response()->json([
                                    'status' => 200,
                                    'code' => 5,
                                    'ready' => true,
                                    'msj' => 'Ya respondio la encuesta secundaria'
                                ]);
                    }
                }else{
                    return response()->json([
                            'status' => 202,
                            'code' => 4,
                            'ready' => false,
                            'msj' => 'Ha completado la encuesta secundaria'
                        ]);
                }
            }else{
                return response()->json([
                        'status' => 200,
                        'code' => 5,
                        'ready' => true,
                        'msj' => 'Ya respondio la encuesta secundaria'
                    ]);
            }
        }else{
            return response()->json([
                    'status' => 404,
                    'code' => 6,
                    'ready' => false,
                    'msj' => 'Usuario no encontrado'
                ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $question = Question::find($id);
        return response()->json($question);

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
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|max:150|min:4'
            
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first()
            ]);
        } else {

            $question = Question::find($id);
                $question->titulo = $request->input('titulo');
            $question->save();
                
            return response()->json([
                'error' => 'Pregunta actualizada exitosamente...',
                'redirect' => '/factor/questions/'.$question->factor_id
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $question = Question::find($id);

        if($question){

            $factor = Factor::find($question->factor_id);

            $question->delete();
            
            $questions = Question::where('factor_id',$factor->id)->get();

            return response()->json([
                    'status' => 200,
                    'questions' => $questions,
                    'msj' => 'Pregunta eliminada exitosamente'
                ]);

        }else{
            return response()->json([
                    'status' => 404,
                    'msj' => 'Preguntada no encontrada'
                ]);
        }
    }
}
