const base_url = 'http://localhost:8000';
import * as $ from 'jquery';
import * as Noty from 'noty';
import * as Chart from 'chart.js';


const helpers = {
	auth_check: function () {

		var user = sessionStorage.getItem('user_cb');

		if (user==null || user=='' || user=='null' || user==' ') {
			return false;
 		} else {
 			return true;
 		}

	},

	user: function () {

		var user = sessionStorage.getItem('user_cb');

		if (user==null) {
			return null;
 		} else {
 			return JSON.parse(user);
 		}

	},

	url: function (url) {
		return base_url+url;
	},

	load_view(name) {
		$.get('./source_typescript/app/views/'+name+'.html', function (template) {
			$('body').append(template);
		});
	},

	ajax: function (name, vue) {
		const form = $('form#'+name);
		const data = form.serialize();
		const th = this;
		const url = form.attr('action');
		let   method = form.attr('method');
		const helpers = this;

		if(method==null) {
			method = 'POST';
		}

		const vm = vue;

		new Noty({
		    text: 'Cargando, por favor espere.',
		    layout: 'bottomRight',
		    timeout: 2500,
		    progressBar: true
		}).show();


		$.ajax({
			url: this.url(url),
			type: 'POST',
			headers: {"X-HTTP-Method-Override": method},
			dataType: 'json',
			data: data,
		})
		.done(function(response) {
			
			if (response.error) {
				new Noty({
				    text: response.error,
				    layout: 'bottomRight',
				    timeout: 2500,
				    progressBar: true
				}).show();
			}

			if (response.user) {
				sessionStorage.setItem('user_cb', JSON.stringify(response.user));
				new Noty({
				    text: 'Bienvenido '+response.user.name+'.',
				    layout: 'bottomRight',
				    timeout: 2500,
				    progressBar: true
				}).show();
			}


			if (response.redirect) {
				vm.$router.push(response.redirect);
			}


		})
		.fail(function(response){
			console.log('Fail response:');
			console.log(response);
		});

	},

	draw_primary_chart(results) {

		var nombres_categoria = [], results_data = [], results_background = [];

		for(var i=0; i < results.length; i++){
			nombres_categoria[i] = results[i].nombre_categoria.substring(0, 25);
			results_data[i] = results[i].results.percent;

			if(results[i].results.value == 1)
				results_background[i] = "#009933";

			else if(results[i].results.value == 2)
				results_background[i] = "#0000ff";

			else if(results[i].results.value == 3)
				results_background[i] = "#ffff00";

			else if(results[i].results.value == 4)
				results_background[i] = "#ff9900";

			else if(results[i].results.value == 5)
				results_background[i] = "#ff0000";
		}



		new Chart(document.getElementById("bar-chart"), {
			type: 'bar',
			data: {
				labels: nombres_categoria,
				datasets: [
					{
					    label: ["Factor"],
					    backgroundColor: results_background,
					    data: results_data
					}
				]
			},
			options: {
				legend: { 
					display: false,
				    position: 'bottom',
				    labels: {
				        fontColor: "#000000",
				    }
				},
				title: {
					display: true,
					text: 'Resultados de la primera encuesta en porcentajes'
				},
			    scales: {
			      xAxes: [{
			        display: true,
			        	ticks: {
			          		min: 0,
					  		autoSkip: false
			        	}
			      	}],
			      	yAxes: [{
			        	display: true,
			        	ticks: {
			          		min: 0,
					  		autoSkip: false
			        	}
			      	}],
			  	},
				maintainAspectRatio: false,
			}
		});
	},


	validate_factor_response() {

		var $questions = $(".quiz");
		
		if($questions.find("input:radio:checked").length === $questions.length) {
		    return true;
		}

		return false;
	}
};

export default helpers;