
<html>


<title>Reporte</title>

<table style="width: 100%" cellpadding="0" cellspacing="0">
	
	<thead>
		<tr valign="top">
			<th width="18px;">Marca temporal</th>
			<th width="40px;">Nombre</th>
			<th width="30px;">Número del Doc. de Identidad</th>

			@if(!empty($factors))
			@if(count($factors)>0)
				<?php  
					$num_question = 1;
				?>
				@foreach($factors[0]->childs as $key=>$factor)
					@foreach($factor->questions as $question)
						<th width="15px;">Pregunta {{$num_question}}</th>
						<?php  
							$num_question += 1;
						?>
					@endforeach

					<th style="background-color: #F7FE2E;">Suma</th>
					<th style="background-color: #F7FE2E;"></th>
					<th width="18px;" style="background-color: #F7FE2E;">Resultado</th>
				@endforeach
			@endif
			@endif
		</tr>
	</thead>

	<tbody>
		@foreach($students as $key=>$student)
			<tr>
				<td>
					@if(!empty($student->created_at))
						{{$student->created_at}}
					@endif
				</td>
				<td>{{$student->name}}</td>
				<td>{{$student->ci}}</td>

				@if(!empty($factors))
				@if(count($factors)>0)
					@foreach($factors[0]->childs as $key=>$factor)
						@foreach($factor->questions as $question)
							<?php  
								$ready = 0;
							?>
							@foreach($student->answers as $answer)
								@if($answer->question_id == $question->id)
									<td width="15px;">{{$answer->respuesta}}</td>
									<?php  
										$ready = 1;
									?>
								@endif
							@endforeach

							@if($ready == 0)
								<td width="15px;"></td>
							@endif
						@endforeach

						
						<?php  
							$points_ready = 0;
						?>
						@foreach($student->factor_points as $points)
							
							@if($points->id == $factor->id)
								<td  style="background-color: #F7FE2E;">{{$points->points}}</td>
								<td  style="background-color: #F7FE2E;">{{$points->result->percent}}</td>
								<td  style="background-color: #F7FE2E;">{{$points->result->text}}</td>
								<?php  
									$points_ready = 1;
								?>
							@endif
						@endforeach
						@if($points_ready == 0)
							<td style="background-color: #F7FE2E;"></td>
							<td style="background-color: #F7FE2E;"></td>
							<td style="background-color: #F7FE2E;"></td>
						@endif

						
					@endforeach
				@endif
				@endif

			</tr>
		@endforeach

			
			

			
	</tbody>
	
</table>


</html>