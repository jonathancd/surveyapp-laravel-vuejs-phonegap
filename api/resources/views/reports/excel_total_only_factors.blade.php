<title>Reporte</title>

<table style="width: 100%" cellpadding="0" cellspacing="0">
	
	<thead>
		<tr valign="top">
			@if(!empty($factors))
			@if(count($factors)>0)
				@foreach($factors[0]->childs as $key=>$factor)
					<th width="15px;">Factor {{$key+1}}</th>
				@endforeach
			@endif
			@endif
		</tr>
	</thead>

	<tbody>
		@foreach($students as $key=>$student)
			<tr>
				@if(!empty($factors))
				@if(count($factors)>0)
					@foreach($factors[0]->childs as $key=>$factor)
						@foreach($student->factor_points as $point)
							@if($point->id == $factor->id)
								<td width="20px;">{{$student->getFactorResult($point)->text}}</td>
							@endif
						@endforeach
					@endforeach
				@endif
				@endif

				<td></td>
				<td></td>
				<td></td>
			</tr>
		@endforeach

			<tr></tr>
			<tr></tr>
			<tr></tr>


			<tr>
				<td></td>
				<td></td>
				@if(!empty($factors))
				@if(count($factors)>0)
					@foreach($factors[0]->childs as $key=>$factor)
						<th width="20px;">Factor {{$key+1}}</th>
					@endforeach
				@endif
				@endif
			</tr>

			<!-- Resultados globales -->
			<tr>
				<td></td>
				<td>Muy Suficiente</td>
				@if(!empty($factors))
				@if(count($factors)>0)
					<?php  
						$total = 0;
					?>
					@foreach($factors[0]->childs as $key=>$factor)
						@if($factor->muy_suficiente > 0)
						<td width="20px;">{{$factor->muy_suficiente}}</td>
						@else 
						<td width="20px;"></td>
						@endif
						<?php  
							$total += $factor->muy_suficiente;
						?>
					@endforeach
				@endif
				@endif
				@if($total>0)
					<td>{{$total}}</td>
				@endif
			</tr>
			<tr>
				<td></td>
				<td>Suficiente</td>
				@if(!empty($factors))
				@if(count($factors)>0)
					<?php  
						$total = 0;
					?>
					@foreach($factors[0]->childs as $key=>$factor)
						@if($factor->suficiente > 0)
						<td width="20px;">{{$factor->suficiente}}</td>
						@else 
						<td width="20px;"></td>
						@endif
						<?php  
							$total += $factor->suficiente;
						?>
					@endforeach
				@endif
				@endif
				@if($total>0)
					<td>{{$total}}</td>
				@endif
			</tr>
			<tr>
				<td></td>
				<td>Insuficiente</td>
				@if(!empty($factors))
				@if(count($factors)>0)
					<?php  
						$total = 0;
					?>
					@foreach($factors[0]->childs as $key=>$factor)
						@if($factor->insuficiente > 0)
						<td width="20px;">{{$factor->insuficiente}}</td>
						@else 
						<td width="20px;"></td>
						@endif
						<?php  
							$total += $factor->insuficiente;
						?>
					@endforeach
				@endif
				@endif
				@if($total>0)
					<td>{{$total}}</td>
				@endif
			</tr>
			<tr>
				<td></td>
				<td>Deficiente</td>
				@if(!empty($factors))
				@if(count($factors)>0)
					<?php  
						$total = 0;
					?>
					@foreach($factors[0]->childs as $key=>$factor)
						@if($factor->deficiente > 0)					
						<td width="20px;">{{$factor->deficiente}}</td>
						@else 
						<td width="20px;"></td>
						@endif
						<?php  
							$total += $factor->deficiente;
						?>
					@endforeach
				@endif
				@endif
				@if($total>0)
					<td>{{$total}}</td>
				@endif
			</tr>
			<tr>
				<td></td>
				<td>Muy Deficiente</td>
				@if(!empty($factors))
				@if(count($factors)>0)
					<?php  
						$total = 0;
					?>
					@foreach($factors[0]->childs as $key=>$factor)
						@if($factor->muy_deficiente > 0)	
						<td width="20px;">{{$factor->muy_deficiente}}</td>
						@else 
						<td width="20px;"></td>
						@endif
						<?php  
							$total += $factor->muy_deficiente;
						?>
					@endforeach
				@endif
				@endif
				@if($total>0)
					<td>{{$total}}</td>
				@endif
			</tr>

			<!-- Total por factor -->
			<tr>
				<td></td>
				<td></td>
				@if(!empty($factors))
				@if(count($factors)>0)
					@foreach($factors[0]->childs as $key=>$factor)
						<?php 
							$total = $factor->muy_suficiente + $factor->suficiente + $factor->insuficiente + $factor->deficiente + $factor->muy_deficiente;
						 ?>
						 <td width="20px;">{{$total}}</td>
					@endforeach
				@endif
				@endif
			</tr>

			

			
	</tbody>
	
</table>
