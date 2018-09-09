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

use App\Institution;
use App\Program;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        foreach($users as $user){
            $user->program = Program::find($user->program_id);
        }

        return response()->json($users);
    }


    public function home(){
        $users = count(User::where('rol',1)->get());
        $questions = count(Question::all());

        return response()->json([
                'users' => $users,
                'questions' => $questions
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $institutions = Institution::all();
        $programs     = Program::all();

        return response()->json([
                'institutions' => $institutions,
                'programs' => $programs
            ]);
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
            'institution_id' => 'required',
            'program_id' => 'required',

            'name' => 'required|unique:users,name|max:33|min:4',
            'email' => 'required|unique:users,email|max:60|min:4',
            'password' => 'required|max:18|min:4',
            'ci' => 'required|unique:users,ci|max:18|min:4',
            'codigo_estudiante' => 'required|unique:users,codigo_estudiante|max:12|min:2',
            'fecha_nacimiento' => 'required|max:18|min:4|date_format:"Y-m-d',
            'genero' => 'required|max:18|min:4',
            'estrato' => 'required|max:18|min:1',
            // 'programa' => 'required|max:18|min:1',
            'semestre' => 'required|max:18|min:1',
            'telefono' => 'required|max:18|min:4'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first()
            ]);
        } else {
            $user = new User;
                $user->institution_id = $request->input('institution_id');
                $user->program_id = $request->input('program_id');

                $user->name = $request->input('name');
                $user->email = $request->input('email');
                $user->password = $request->input('password');
                $user->ci = $request->input('ci');
                $user->codigo_estudiante = $request->input('codigo_estudiante');
                $user->fecha_nacimiento = $request->input('fecha_nacimiento');
                $user->genero = $request->input('genero');
                $user->estrato = $request->input('estrato');
                // $user->programa = $request->input('programa');

                $user->programa = 'programa de prueba';

                $user->semestre = $request->input('semestre');
                $user->telefono = $request->input('telefono');
                $user->rol = 1;
            $user->save();

            return response()->json([
                'error' => 'Usuario creado exitosamente...',
                'redirect' => '/user/manager'
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
        $user = User::find($id);
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $institutions = Institution::all();
        $programs     = Program::all();

        return response()->json([
                'user' => $user,
                'institutions' => $institutions,
                'programs' => $programs
            ]);
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
        $current_user = User::find($id);

        $validator = Validator::make($request->all(), [
            'institution_id' => 'required',
            'program_id' => 'required',

            'name' => 'required|unique:users,name,'.$current_user->id.'|max:33|min:4',
            'email' => 'required|unique:users,email,'.$current_user->id.'|max:60|min:4',
            // 'password' => 'required|max:18|min:4',
            'ci' => 'required|unique:users,ci,'.$current_user->id.'|max:18|min:4',
            'codigo_estudiante' => 'required|unique:users,codigo_estudiante,'.$current_user->id.'|max:12|min:2',
            'fecha_nacimiento' => 'required|max:18|min:4|date_format:"Y-m-d',
            'genero' => 'required|max:18|min:4',
            'estrato' => 'required|max:18|min:1',
            // 'programa' => 'required|max:18|min:1',
            'semestre' => 'required|max:18|min:1',
            'telefono' => 'required|max:18|min:4'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first()
            ]);
        } else {
            $user = User::find($id);
                $user->institution_id = $request->input('institution_id');
                $user->program_id = $request->input('program_id');

                $user->name = $request->input('name');
                $user->email = $request->input('email');

                if ($request->input('password')!=null) {
                    $user->password = $request->input('password');
                }

                $user->ci = $request->input('ci');
                $user->codigo_estudiante = $request->input('codigo_estudiante');
                $user->fecha_nacimiento = $request->input('fecha_nacimiento');
                $user->genero = $request->input('genero');
                $user->estrato = $request->input('estrato');
                // $user->programa = $request->input('programa');
                $user->semestre = $request->input('semestre');
                $user->telefono = $request->input('telefono');

            $user->save();

            return response()->json([
                'error' => 'Usuario actualizado exitosamente...',
                'redirect' => '/user/manager'
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
        $user= User::find($id);
        $user->delete();
        return $this->index();
    }

    public function check (Request $request) {

        $user = User::where(['email' => $request->input('email'), 'password' => $request->input('password')])->get()->first();
        if ($user) {
            if($user->rol == 0)
                return response()->json(['redirect'=>'/', 'user'=>$user]);
            else
                return response()->json(['redirect'=>'/factor/menu', 'user'=>$user]);
        } else {
            return response()->json(['error'=>'Error al validar datos']);
        }
    }





    public function factor_response($id){

        $user = User::find($id);

        $factor = Factor::where(['parent' => '-1', 'tipo' => '1'])->first();
        $factor->ready = 1;

        if($factor) {

            $childs = collect();

            foreach (Factor::where(['parent' => $factor->id, 'tipo' => 2])->get() as $factor_child) {
                $factor_child->questions = Question::where(['factor_id' => $factor_child->id])->get();
                $factor_child->nombre_categoria = Factor::getCategory($factor_child->categoria);

                $childs->push($factor_child);
            }

            $factor->childs = $childs;

            foreach($factor->childs as $child){
                foreach($child->questions as $question){
                    $question->answer = Answer::where(['question_id'=>$question->id,'user_id' => $user->id])->first();
                    if(!$question->answer){
                        $factor->ready = 0;
                    }
                }
            }            
        }

        $secundary_factors = collect();
        $secundaries = Factor::where(['parent' => '-1', 'tipo' => '2' ])->get(); 
        foreach($secundaries as $secundary){
            $secundary->nombre_categoria = Factor::getCategory($secundary->categoria);
            $secundary->questions = Question::where(['factor_id' => $secundary->id])->get();
            $secundary->has_answer = 0;

            foreach($secundary->questions as $question){
                $question->answer = Answer::where(['question_id'=>$question->id,'user_id' => $user->id])->first();
                if($question->answer){
                    $secundary->has_answer = 1;
                }
            }

            if($secundary->has_answer > 0)
                $secundary_factors->push($secundary);
        }

        // $factor_time = Factor_Secundary_Time::where('user_id',$user->id)->first();
        $factor_time = Factor_Secundary_Time::where(['user_id' => $user->id, 'active' => 1])->where('questions_answered', '<', 30)->first();




        $factors_time = Factor_Secundary_Time::where('user_id', $user->id)->get();
            foreach($factors_time as $fac_time){

                $fac_time->factors =  Factor::where(['parent' => -1, 'tipo' => 2])->get();

                foreach($fac_time->factors as $s_factor){
                    $s_factor->questions = collect();
                    $s_questions = Question::where('factor_id', $s_factor->id)->get();

                    foreach($s_questions as $f_q){
                        $f_q->answer = Answer::where(['user_id' => $user->id, 'question_id' => $f_q->id, 'factor_time_id' => $fac_time->id])->first();

                        if($f_q->answer){
                            $s_factor->questions->push($f_q);
                        }
                    }
                    
                    $s_factor->nombre_categoria = Factor::getCategory($s_factor->categoria);
                }
            }


        return response()->json([
                    'factor_time' => $factor_time,
                    'factor' => $factor,
                    'secundary_factors' => $secundary_factors,
                    'factors_time' => $factors_time
                ]);
    }




    public function statistics($id){
        $user = User::Find($id);

        if($user){
            $factor_points = User::getPointsByFactors($user->id);

            foreach($factor_points as $factor){
                $factor->results = User::getFactorResult($factor);
                $factor->nombre_categoria = Factor::getCategory($factor->categoria);
            }


            $factors_time = Factor_Secundary_Time::where('user_id', $user->id)->get();
            foreach($factors_time as $fac_time){

                $fac_time->factors = collect();
                $secundary_factors = Factor::where(['parent' => -1, 'tipo' => 2])->get();
                // $exist_factor_answers = 0;

                foreach($secundary_factors as $s_factor){
                    $f_questions = Question::where('factor_id', $s_factor->id)->get();

                    // foreach($f_questions as $f_q){
                    //     $f_q_answer = Answer::where(['user_id' => $user->id, 'question_id' => $f_q->id])->first();

                    //     if($f_q_answer){
                    //         $exist_factor_answers = 1;
                    //     }
                    // }
                    
                    // if($exist_factor_answers == 1){
                        $s_factor->results = User::getFactorSecundaryResult($s_factor, $fac_time, $user);
                        $s_factor->nombre_categoria = Factor::getCategory($s_factor->categoria);
                        $fac_time->factors->push($s_factor);
                    // }
                }

            }

            return response()->json([
                        'status' => 200,
                        'primary_results' => $factor_points,
                        'secundary_results' => $factors_time
                    ]);

            
        }else{
            return response()->json([
                    'status' => 404,
                    'msj' => 'Usuario no encontrado'
                ]);
        }
    }










    public function test(){

        $users = User::where('rol','1')->get();

        $factor = Factor::where(['parent' => '-1', 'tipo' => '1'])->first();

        foreach($users as $user){
            foreach (Factor::where(['parent' => $factor->id, 'tipo' => 2])->get() as $factor_child) {
                $questions = Question::where(['factor_id' => $factor_child->id])->get();
                $points_needed = count($questions)*5*0.60;
                $down_up = 0;
                $user_points = 0;

                foreach($questions as $question){
                    $answer = Answer::where(['question_id'=>$question->id,'user_id' => $user->id])->first();
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
                    $factor_result->user_id = $user->id;
                    $factor_result->type = 1;
                    $factor_result->points_needed = $points_needed;
                    $factor_result->result = $user_points;
                    $factor_result->down_up = $down_up;
                    $factor_result->questions = 0;
                $factor_result->save();
            }

            $factor_result = new Factor_Result;
                $factor_result->factor_id = -1;
                $factor_result->user_id = $user->id;
                $factor_result->type = 0;
                $factor_result->points_needed = 0;
                $factor_result->result = 0;
                $factor_result->down_up = -1;
                $factor_result->questions = 0;
            $factor_result->save();


            $factor_secundary_time = new Factor_Secundary_Time;
                $factor_secundary_time->user_id = $user->id;
                $factor_secundary_time->date = \Carbon::today();
                $factor_secundary_time->questions_answered = 0;
            $factor_secundary_time->save();
        }

        return "everything turn out to be ok!";
    }


}
