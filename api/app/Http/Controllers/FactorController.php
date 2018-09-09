<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Validator;

use App\User;
use App\Factor;
use App\Answer;
use App\Question;

class FactorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $factors = collect();
        foreach (Factor::all() as $factor) {
            $current = $factor;
            if ($current->parent == '-1' && $current->tipo == '1') {
                $childs = collect();

                foreach (Factor::where(['parent' => $current->id, 'tipo' => 2])->get() as $factor_child) {
                    $factor_child->questions = Question::where(['factor_id' => $factor_child->id])->get();
                    $factor_child->nombre_categoria = Factor::getCategory($factor_child->categoria);

                    $childs->push($factor_child);
                }

                $current->childs = $childs;

                $factors->push($current);
            }

            if ($current->parent == -1 && $current->tipo == '2') {
                $current->questions = Question::where(['factor_id' => $current->id])->get();
                $current->nombre_categoria = Factor::getCategory($current->categoria);
                $factors->push($current);
            }

        }

        return response()->json($factors);
    }



    public function questions($id){
        $factor = Factor::find($id);

        if($factor){
            $factor->nombre_categoria = Factor::getCategory($factor->categoria);
            $factor->questions = Question::where('factor_id',$factor->id)->get();

            return response()->json($factor);
        }else{
            return response()->json([
                    'status' => 404,
                    'msj' => 'Factor no encontrado'
                ]);
        }
    }



    public function change_status($id)
    {
        $factor = Factor::find($id);
        switch ($factor->estado) {
            case 1:
                $factor->estado = 0;
                $factor->save();
                break;

            case 0:
                $factor->estado = 1;
                $factor->save();
                break;
        }

        return $this->index();
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
        //
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
