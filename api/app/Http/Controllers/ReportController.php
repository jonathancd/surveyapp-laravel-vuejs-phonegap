<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use PDF;
use Excel;
use File;

use App\Report;

use App\User;
use App\Factor;
use App\Factor_Result;
use App\Factor_Secundary_Time;
use App\Answer;
use App\Question;

use App\Institution;
use App\Program;

class ReportController extends Controller
{


	public function index(){
		$reports = Report::all();

		return response()->json([
				'status' => 200,
				'reports' => $reports
			]);
	}



	public function destroy($id){

		$report = Report::find($id);

		if($report){
			$name = $report->name;
			if($report->delete()){
				if( file_exists(public_path().'/uploads/'.$name) ){
               		File::delete(public_path().'/uploads/'.$name);
               	}

				$reports = Report::all();

				return response()->json([
						'status' => 200,
						'msj' => 'Reporte eliminado exitosamente',
						'reports' => $reports
					]);
			}else{
				return response()->json([
						'status' => 500,
						'msj' => 'Ocurrio un error al eliminar reporte'
					]);
			}
		}else{
			return response()->json([
					'status' => 404,
					'msj' => 'Reporte no encontrado'
				]);
		}
	}




    // Excel con lista de estudiantes y lo que respondieron en cada factor
    public function create_general_by_total($type){

    	$students = User::where('rol',1)->get();
    	foreach($students as $student){
    		$student->factor_points = User::getPointsByFactors($student->id);
    	}


    	$factors = collect();
        foreach (Factor::all() as $factor) {
            $current = $factor;
            if ($current->parent == '-1' && $current->tipo == '1') {
                $childs = collect();
                foreach (Factor::where(['parent' => $current->id, 'tipo' => 2])->get() as $factor_child) {
                    $factor_child->questions = Question::where(['factor_id' => $factor_child->id])->get();
                    $factor_child->nombre_categoria = Factor::getCategory($factor_child->categoria);

                    $factor_child->muy_suficiente = 0;
	        		$factor_child->suficiente = 0;
	        		$factor_child->insuficiente = 0;
	        		$factor_child->deficiente = 0;
	        		$factor_child->muy_deficiente = 0;

                    $childs->push($factor_child);
                }
                $current->childs = $childs;
                $factors->push($current);
            }
        }


        if(count($factors)>0){
        	foreach($factors[0]->childs as $factor){
        		foreach($students as $student){
        			foreach($student->factor_points as $point){
	        			if($point->id == $factor->id){
	        				$result = $student->getFactorResult($point)->value;

		        			if($result == 1){
		        				$factor->muy_suficiente += 1;
		        			}else if($result == 2){
		        				$factor->suficiente += 1;
		        			}else if($result == 3){
		        				$factor->insuficiente += 1;
		        			}else if($result == 4){
		        				$factor->deficiente += 1;
		        			}else if($result == 5){
		        				$factor->muy_deficiente+= 1;
		        			}
	        			}
        			}
        		}
        	}
        }

        $now = \Carbon::now();
        if($type == 1){
            $excel_name = 'Report general total - '.$now->toDateString();
            $excel_titulo = 'Report general total';
            $excel_view = 'reports.excel_total';
        }else{
            $excel_name = 'Report general total sin nombres de estudiantes- '.$now->toDateString();
            $excel_titulo = 'Report general total sin nombres de estudiantes';
            $excel_view = 'reports.excel_total_only_factors';
        }

        Excel::create($excel_name, function($excel) use ($students, $factors, $excel_view) {
            $excel->sheet('Aplicando total', function($sheet) use ($students, $factors, $excel_view) {
                $sheet->loadView($excel_view, array('students' => $students, 'factors' => $factors));
            });
        })->store('xls', public_path().'/uploads' );




        $reports_same_name = Report::where(['titulo'=> $excel_titulo, 'date' => $now->toDateString()])->get();
        foreach($reports_same_name as $r){
        	$r->delete();
        }

 
        $report = new Report;
        	$report->name = $excel_name.'.xls';
        	$report->titulo = $excel_titulo;
        	$report->date = $now;
        	$report->extension = 'Excel';
        $report->save();


    	return response()->json([
    			'status' => 200,
    			'msj' => 'Reporte general aplicando total generado'
    		]);
    }



    // Excel con lista de estudiantes y lo que respondieron en cada pregunta
    public function create_general_by_question(){

    	$students = User::where('rol',1)->get();
    	foreach($students as $student){
    		$student->factor_points = User::getPointsByFactors($student->id);
    		foreach($student->factor_points as $point){
    			$point->result = $student->getFactorResult($point);
    		}

    		$student->answers = Answer::where('user_id',$student->id)->get();
    	}


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
        }




        $now = \Carbon::now();

        Excel::create('Report respuestas del primer formulario - '.$now->toDateString(), function($excel) use ($students, $factors) {
            $excel->sheet('Aplicando total', function($sheet) use ($students, $factors) {
                $sheet->loadView('reports.excel_general_answers', array('students' => $students, 'factors' => $factors));
            });
        })->store('xls', public_path().'/uploads' );


        $reports_same_name = Report::where(['titulo'=>'Report respuestas del primer formulario', 'date' => $now->toDateString()])->get();

        foreach($reports_same_name as $r){
        	$r->delete();
        }

 
        $report = new Report;
        	$report->name = 'Report respuestas del primer formulario - '.$now->toDateString().'.xls';
        	$report->titulo = 'Report respuestas del primer formulario';
        	$report->date = $now;
        	$report->extension = 'Excel';
        $report->save();


    	return response()->json([
    			'status' => 200,
    			'msj' => 'Reporte resultados del primer formulario generado'
    		]);

    }


    // Excel con una sheet para cada factor (simplicado)
    public function create_by_factor(){

    	$students = User::where('rol',1)->get();
    	foreach($students as $student){
    		$student->factor_points = User::getPointsByFactors($student->id);
    	}

    	$factors = collect();
        foreach (Factor::all() as $factor) {
            $current = $factor;
            if ($current->parent == '-1' && $current->tipo == '1') {
                $childs = collect();
                foreach (Factor::where(['parent' => $current->id, 'tipo' => 2])->get() as $factor_child) {
                    $factor_child->questions = Question::where(['factor_id' => $factor_child->id])->get();
                    $factor_child->nombre_categoria = Factor::getCategory($factor_child->categoria);

                    $factor_child->muy_suficiente = 0;
	        		$factor_child->suficiente = 0;
	        		$factor_child->insuficiente = 0;
	        		$factor_child->deficiente = 0;
	        		$factor_child->muy_deficiente = 0;

                    $childs->push($factor_child);
                }
                $current->childs = $childs;
                $factors->push($current);
            }
        }


        if(count($factors)>0){
        	foreach($factors[0]->childs as $factor){
        		foreach($students as $student){
        			foreach($student->factor_points as $point){
	        			if($point->id == $factor->id){
	        				$result = $student->getFactorResult($point)->value;

		        			if($result == 1){
		        				$factor->muy_suficiente += 1;
		        			}else if($result == 2){
		        				$factor->suficiente += 1;
		        			}else if($result == 3){
		        				$factor->insuficiente += 1;
		        			}else if($result == 4){
		        				$factor->deficiente += 1;
		        			}else if($result == 5){
		        				$factor->muy_deficiente+= 1;
		        			}
	        			}
        			}
        		}
        	}
        }


        $now = \Carbon::now();

        Excel::create('Report estadisticas por factores - '.$now->toDateString(), function($excel) use ($factors) {
            if(count($factors)>0){
        		foreach($factors[0]->childs as $factor){

		            $excel->sheet(substr($factor->categoria, 0, 30), function($sheet) use ($factor) {
		                $sheet->loadView('reports.excel_statistics_by_factor', array('factor' => $factor));
		            });

		        }
		    }
        })->store('xls', public_path().'/uploads' );


        $reports_same_name = Report::where(['titulo'=>'Report estadisticas por factores', 'date' => $now->toDateString()])->get();
        foreach($reports_same_name as $r){
        	$r->delete();
        }

 
        $report = new Report;
        	$report->name = 'Report estadisticas por factores - '.$now->toDateString().'.xls';
        	$report->titulo = 'Report estadisticas por factores';
        	$report->date = $now;
        	$report->extension = 'Excel';
        $report->save();

    	return response()->json([
    			'status' => 200,
    			'msj' => 'Reporte estadisticas por factores generado'
    		]);
    }



    // PDF de la encuesta principal
    public function create_final_instrument(){

        $factor = Factor::where(['parent' => '-1', 'tipo' => '1'])->first();

        if($factor) {
            $childs = collect();

            foreach (Factor::where(['parent' => $factor->id, 'tipo' => 2])->get() as $factor_child) {
                $factor_child->questions = Question::where(['factor_id' => $factor_child->id])->get();
                $factor_child->nombre_categoria = Factor::getCategory($factor_child->categoria);

                $childs->push($factor_child);
            }

            $factor->childs = $childs;          
        }


        $now = \Carbon::now();

        $pdf = PDF::loadView('reports.pdf_final_instrument',['factor' => $factor]);
        $pdf->setPaper('letter');
        $pdf->save( public_path().'/uploads/'.'Instrumento-Final-'.$now->toDateString().'.pdf' );



        // $pdf = PDF::loadView( 'reports.pdf_final_instrument', compact('factor'))->save( public_path().'/uploads/'.'pdfname.pdf' ); 

        $reports_same_name = Report::where(['titulo'=>'Instrumento Final', 'date' => $now->toDateString()])->get();
        foreach($reports_same_name as $r){
            $r->delete();
        }

        $report = new Report;
            $report->name = 'Instrumento-Final-'.$now->toDateString().'.pdf';
            $report->titulo = 'Instrumento Final';
            $report->date = $now;
            $report->extension = 'PDF';
        $report->save();


        return response()->json([
                'status' => 200,
                'msj' => 'Instrumento final generado'
            ]);
    }



    public function create_students_progress(){

        $students = User::where('rol',1)->get();
        foreach($students as $student){
            $student->progress = Factor_Secundary_Time::where('user_id', $student->id)->first();
        }

        $now = \Carbon::now();
        Excel::create('Avance por estudiantes - '.$now->toDateString(), function($excel) use ($students) {
            $excel->sheet('Lista de estudiantes', function($sheet) use ($students) {
                $sheet->loadView('reports.excel_students_progress', array('students' => $students));
            });
        })->store('xls', public_path().'/uploads' );


        $reports_same_name = Report::where(['titulo'=>'Avance por estudiantes', 'date' => $now->toDateString()])->get();

        foreach($reports_same_name as $r){
            $r->delete();
        }

 
        $report = new Report;
            $report->name = 'Avance por estudiantes - '.$now->toDateString().'.xls';
            $report->titulo = 'Avance por estudiantes';
            $report->date = $now;
            $report->extension = 'Excel';
        $report->save();


        return response()->json([
                'status' => 200,
                'msj' => 'Reporte de avances por estudiantes generado'
            ]);
    }





    public function create_students_institution_progress(){

        $institutions = Institution::all();
        foreach($institutions as $institution){
            $institution->students = User::where(['rol' => 1, 'institution_id' => $institution->id])->get();
            foreach($institution->students as $student){
                $student->progress = Factor_Secundary_Time::where('user_id', $student->id)->first();
            }
        }


        $now = \Carbon::now();
        Excel::create('Reporte avances por institucion - '.$now->toDateString(), function($excel) use ($institutions) {
            if(count($institutions)>0){
                foreach($institutions as $institution){
                    $excel->sheet(''.substr($institution->name, 0, 30), function($sheet) use ($institution) {
                        $sheet->loadView('reports.excel_institutions_progress', array('institution' => $institution));
                    });

                }
            }
        })->store('xls', public_path().'/uploads' );


        $reports_same_name = Report::where(['titulo'=>'Avance por instituciones', 'date' => $now->toDateString()])->get();

        foreach($reports_same_name as $r){
            $r->delete();
        }

 
        $report = new Report;
            $report->name = 'Avance por instituciones - '.$now->toDateString().'.xls';
            $report->titulo = 'Avance por instituciones';
            $report->date = $now;
            $report->extension = 'Excel';
        $report->save();

        return response()->json([
                'status' => 200,
                'msj' => 'Reporte de avances por instituciones generado'
            ]);
    }




    public function create_students_program_progress(){

        $programs = Program::all();
        foreach($programs as $program){
            $program->students = User::where(['rol' => 1, 'institution_id' => $program->id])->get();
            foreach($program->students as $student){
                $student->progress = Factor_Secundary_Time::where('user_id', $student->id)->first();
            }
        }

        $now = \Carbon::now();
        Excel::create('Reporte avances por programas - '.$now->toDateString(), function($excel) use ($programs) {
            if(count($programs)>0){
                foreach($programs as $program){

                    $excel->sheet(''.substr($program->name, 0, 30), function($sheet) use ($program) {
                        $sheet->loadView('reports.excel_programs_progress', array('program' => $program));
                    });

                }
            }
        })->store('xls', public_path().'/uploads' );


        $reports_same_name = Report::where(['titulo'=>'Avance por programas', 'date' => $now->toDateString()])->get();
        foreach($reports_same_name as $r){
            $r->delete();
        }

 
        $report = new Report;
            $report->name = 'Avance por programas - '.$now->toDateString().'.xls';
            $report->titulo = 'Avance por programas';
            $report->date = $now;
            $report->extension = 'Excel';
        $report->save();

        return response()->json([
                'status' => 200,
                'msj' => 'Reporte de avances por programas generado'
            ]);
    }


}
