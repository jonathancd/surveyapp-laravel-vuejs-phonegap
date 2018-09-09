<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Program;
use App\User;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Program::all());
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
            'name' => 'required|max:150|min:4'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first()
            ]);
        } else {
            $program = new Program;
                $program->name = $request->input('name');
            $program->save();

            return response()->json([
                'error' => 'Programa creado exitosamente...',
                'redirect' => '/program/manager'
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
        $program = Program::find($id);

        if($program){

            return response()->json([
                    'status' => 200,
                    'program' => $program
                ]);
        }else{
            return response()->json([
                    'status' => 404,
                    'msj' => 'Programa no encontrada'
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:150|min:4'
            
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first()
            ]);
        } else {

            $program = Program::find($id);
                $program->name = $request->input('name');
            $program->save();
                
            return response()->json([
                'error' => 'Programa actualizado exitosamente...',
                'redirect' => '/program/manager'
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
        $program = Program::find($id);

        if($program){

            $students = User::where('program_id', $program->id);
            foreach($students as $student){
                $student->program_id = 0;
            }


            $program->delete();
            
            $programs = Program::all();

            return response()->json([
                    'status' => 200,
                    'programs' => $programs,
                    'msj' => 'Programa eliminado exitosamente'
                ]);

        }else{
            return response()->json([
                    'status' => 404,
                    'msj' => 'Programa no encontrado'
                ]);
        }
    }
}
