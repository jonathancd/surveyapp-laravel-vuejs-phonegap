<style>
	@page { margin-top: 60px; margin-bottom: 60px; }
	* {
		font-family: Arial, sans-serif;
	}


	table{
		border-collapse: separate;
		border-spacing: 0px;
		width: 100%;
	}
	tr{
		border: 1px solid black;
		width: 100%;
	}
	tr > td{
		border: 1px solid black;
		margin: 0px;
		padding: 5px 0px;
	}

	#container-pdf{
		font-size: 10px;
		margin: 0 auto;
		/*padding-top: 50px;*/
		text-align: center;
		width: 85%;
	}

	.factor-category{
		background-color: silver;
		border: 1px solid black;
		margin: 0px;
		text-align: left;
	}

	.factor-category > p{
		height: 15px;
		line-height: 15px;
		margin: 0px;
		padding: 0px 5px;
	}

	.factor-table{
		margin: 0px;
		padding: 0px;
	}
</style>
<title>INVOICE</title>


<div id="container-pdf">

	<div >
		<p>
			Para cada una de las siguientes afirmaciones responde con toda honestidad, teniendo en cuenta la siguiente escala: (1) Completamente en desacuerdo; (2) En desacuerdo; (3) Ni de acuerdo ni en desacuerdo; (4) De acuerdo; (5) Completamente de acuerdo
		</p>
	</div>


	<div>
		@if(!empty($factor))
		@if(count($factor->childs)>0)
			<?php 
				$question_num = 1;
			 ?>
			@foreach($factor->childs as $factor)
				<div class="factor-category">
					<p>Factor {{$factor->categoria}}: {{$factor->nombre_categoria}}</p>
				</div>
				<table class="factor-table">
					@foreach($factor->questions as $question)
						<tr>
							<td width="5%" style="padding: 0px 5px 0px 0px; text-align: right;">{{$question_num}}</td>
							<td width="80%" style="padding: 0px 5px;">{{$question->titulo}}</td>
							<td width="15%" style="text-align: center;">(1) (2) (3) (4) (5)</td>
						</tr>

						<?php 
							$question_num +=1;
						 ?>
					@endforeach
				</table>
			@endforeach
		@endif
		@endif
	</div>

</div>	