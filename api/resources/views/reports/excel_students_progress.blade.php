
<html>


<table style="width: 100%" cellpadding="0" cellspacing="0">
	
	<thead>
		<tr valign="top">
			<th width="18px;">Marca temporal</th>
			<th width="40px;">Nombre</th>
			<th width="30px;">Número del Doc. de Identidad</th>

			<th width="20px;">Primer Factor</th>
			<th width="20px;">Segundo Factor</th>
			<th width="20px;">Última respuesta</th>
			
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
				
				@if(!empty($student->progress))
				<td>
					Respondido
				</td>
				<td>
					@if($student->progress->questions_answered == 30)
						Completada
					@else
						{{$student->progress->questions_answered}}/30 Preguntas
					@endif
				</td>
				<td>
					@if(!empty($student->progress->date))
						{{$student->progress->date}}
					@endif
				</td>
				@else
				<td>
					Sin responder
				</td>
				<td>
					
				</td>
				<td>
					
				</td>
				@endif
			</tr>
		@endforeach

			
			

			
	</tbody>
	
</table>


</html>