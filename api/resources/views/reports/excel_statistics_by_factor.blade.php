
<table style="width: 100%" cellpadding="0" cellspacing="0">
	

	<tbody>

		<tr>
			<th height="50" style="font-size: 24px;">
				Factor {{$factor->categoria}} - {{$factor->nombre_categoria}}
			</th>
		</tr>
		<tr></tr>

		<tr>
			<th width="15px" style="border:1px solid #000000;">Deficiente</th>
			<td width="10px;" style="border:1px solid #000000;">{{$factor->deficiente}}</td>
		</tr>
		
		<tr>
			<th width="15px" style="border:1px solid #000000;">Insuficiente</th>
			<td width="10px;" style="border:1px solid #000000;">{{$factor->insuficiente}}</td>
		</tr>
		
		<tr>
			<th width="15px" style="border:1px solid #000000;">Muy Deficiente</th>
			<td width="10px;" style="border:1px solid #000000;">{{$factor->muy_deficiente}}</td>
		</tr>

		<tr>
			<th width="15px" style="border:1px solid #000000;">Muy Suficiente</th>
			<td width="10px;" style="border:1px solid #000000;">{{$factor->muy_suficiente}}</td>
		</tr>

		<tr>
			<th width="15px" style="border:1px solid #000000;">Suficiente</th>
			<td width="10px;" style="border:1px solid #000000;">{{$factor->suficiente}}</td>
		</tr>

	
	</tbody>
	
</table>
