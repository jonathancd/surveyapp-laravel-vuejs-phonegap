<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\Institution;
use App\User;

class InstitutionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Institution::all());
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
            $institution = new Institution;
                $institution->name = $request->input('name');
            $institution->save();

            return response()->json([
                'error' => 'Institution creada exitosamente...',
                'redirect' => '/institution/manager'
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
        $institution = Institution::find($id);

        if($institution){

            return response()->json([
                    'status' => 200,
                    'institution' => $institution
                ]);
        }else{
            return response()->json([
                    'status' => 404,
                    'msj' => 'Institucion no encontrada'
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

            $institution = Institution::find($id);
                $institution->name = $request->input('name');
            $institution->save();
                
            return response()->json([
                'error' => 'Institucion actualizada exitosamente...',
                'redirect' => '/institution/manager'
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
        $institution = Institution::find($id);

        if($institution){

            $students = User::where('institution_id', $institution->id);
            foreach($students as $student){
                $student->institution_id = 0;
            }


            $institution->delete();
            
            $institutions = Institution::all();

            return response()->json([
                    'status' => 200,
                    'institutions' => $institutions,
                    'msj' => 'Institucion eliminada exitosamente'
                ]);

        }else{
            return response()->json([
                    'status' => 404,
                    'msj' => 'Institucion no encontrada'
                ]);
        }
    }
}
