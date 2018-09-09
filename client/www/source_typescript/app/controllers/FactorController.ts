import helpers from '../../app/helpers';
import * as $ from 'jquery';
import * as Noty from 'noty';

import * as Chart from 'chart.js';


const FactorController = {
	manager: {
		template: '#factor_manager',
		data: function () {
			return {
				user: null,
				primary_factors: []
			}
		},
		mounted: function () {
			this.user = helpers.user();
			const vm = this;

			$.ajax({
				url: helpers.url('/factor'),
				type: 'GET',
				dataType: 'json'
			})
			.done(function(response) {
				console.log(response);
				vm.primary_factors = response;
			});
		},
		methods: {
			change_status: function (id) {
				const vm = this;

				$.ajax({
					url: helpers.url('/factor/change_status/'+id),
					type: 'POST',
					dataType: 'json'
				})
				.done(function(response) {
					vm.primary_factors = response;
				});
			}
		}
	},
	questions: {
		template: '#factor_questions',
		data: function () {
			return {
				user: null,
				factor: null,
				factor_id: null,
				parent: null,
				questions: []
			}
		},
		mounted: function () {
			this.user = helpers.user();
			const vm = this;

			$.ajax({
				url: helpers.url('/factor/'+this.$route.params.id+'/questions'),
				type: 'GET',
				dataType: 'json'
			})
			.done(function(response) {
				vm.factor = response;
				vm.factor_id = vm.factor.id;
				vm.parent = vm.factor.parent;
				vm.questions = response.questions;
			});
		},
		methods: {
			destroy: function (id) {
				const vm = this;

				$.ajax({
					url: helpers.url('/question/'+id),
					type: 'DELETE',
					dataType: 'json'
				})
				.done(function(response) {

					if(response.status == 200){
						vm.questions = response.questions;
					}

					new Noty({
						    text: response.msj,
						    layout: 'bottomRight',
						    timeout: 2500,
						    progressBar: true
						}).show();
				});

			}
		}
	},

	question_create: {
		template: '#question_create',
		data: function () {
			return {
				user: null,
				factor_id: null
			}
		},
		mounted: function () {
			this.user = helpers.user();
			const vm = this;

			vm.factor_id = this.$route.params.id;

		},
		methods: {
			save: function () {
				helpers.ajax('question_create', this);
			}
		}
	},

	question_edit: {
		template: '#question_edit',
		data: function () {
			return {
				user: null,
				question_edit: null
			}
		},
		mounted: function () {
			this.user = helpers.user();
			const vm = this;

			$.ajax({
				url: helpers.url('/question/'+this.$route.params.id+'/edit'),
				type: 'GET',
				dataType: 'json'
			})
			.done(function(response) {
				vm.question_edit = response;
			});
		},
		methods: {
			save: function () {
				helpers.ajax('question_edit', this);
			}
		}
	},

	response: {
		template: '#factor_response',
		data: function () {
			return {
				user: null,
				factor: null,
				primary_factors: []
			}
		},
		mounted: function () {
			this.user = helpers.user();
			const vm = this;

			$.ajax({
				url: helpers.url('/user/'+this.user.id+'/response'),
				type: 'GET',
				dataType: 'json'
			})
			.done(function(response) {

				console.log(response);

				if(response.factor.ready == 0){
					vm.primary_factors = response.factor.childs;
				}else{
					new Noty({
							text: 'Usted ya respondio la encuesta',
							layout: 'bottomRight',
							timeout: 2500,
							progressBar: true
						}).show();

					vm.$router.push('/factor/menu');
				}
				
			})
			.fail(function(error){
				console.log("ocurrio un error");
				console.log(error)
			});
		},
		methods: {
			save: function () {
				// helpers.ajax('factor_principal_response', this);
				const vm = this;

				const form = $('form#factor_principal_response');
				const data = form.serialize();

				if(helpers.validate_factor_response()){

					new Noty({
							text: 'Enviando respuestas, espere...',
							layout: 'bottomRight',
							timeout: 2500,
							progressBar: true
						}).show();


					$.ajax({
						url: helpers.url('/answer'),
						type: 'POST',
						dataType: 'json',
						data: data,
					})
					.done(function(response) {
						new Noty({
							    text: 'Sus respuestas han sido enviadas',
							    layout: 'bottomRight',
							    timeout: 2500,
							    progressBar: true
							}).show();


						vm.$router.push('/factor/response/success');
					})
					.fail(function(error){
						console.log("ocurrio un error");
						console.log(error)
					});

				}else{
					new Noty({
							text: 'Debe responder todas las preguntas',
							layout: 'bottomRight',
							timeout: 2500,
							progressBar: true
						}).show();
				}	

			}
		}
	},

	response_today: {
		template: '#factor_response_today',
		data: function () {
			return {
				user: null,
				factor: null,
				factor_result: null,
				question: null
			}
		},
		mounted: function () {
			this.user = helpers.user();
			const vm = this;

			$.ajax({
				url: helpers.url('/question/today/'+this.user.id),
				type: 'GET',
				dataType: 'json'
			})
			.done(function(response) {

				if(response.ready == false){
					if(response.status == 200){

						vm.question = response.question;
						vm.factor = response.factor;
						vm.factor_result = response.factor_result;

					}else{
						new Noty({
								text: response.msj,
								layout: 'bottomRight',
								timeout: 2500,
								progressBar: true
							}).show();

						vm.$router.push('/factor/menu');
					}
				}else{
					new Noty({
							text: 'Usted ya respondio la encuesta',
							layout: 'bottomRight',
							timeout: 2500,
							progressBar: true
						}).show();

					vm.$router.push('/factor/menu');
				}

				
			})
			.fail(function(error){
				console.log("ocurrio un error");
				console.log(error)
			});
		},
		methods: {
			save: function () {

				if(helpers.validate_factor_response()){

					helpers.ajax('factor_secundary_response', this);
					
				}else{
					new Noty({
							text: 'Debe responder la pregunta',
							layout: 'bottomRight',
							timeout: 2500,
							progressBar: true
						}).show();
				}

			}
		}
	},

	response_success: {
		template: '#response_success',
		data: function () {
			return {
				user: null
			}
		},
		mounted: function () {
			this.user = helpers.user();
			const vm = this;


		},
		methods: {
			
		}
	},

	menu: {
		template: '#factor_menu',
		data: function () {
			return {
				user: null,
				factor: null,
				factor_time: null
			}
		},
		mounted: function () {
			this.user = helpers.user();
			const vm = this;

			$.ajax({
				url: helpers.url('/user/'+this.user.id+'/response'),
				type: 'GET',
				dataType: 'json'
			})
			.done(function(response) {
					console.log(response);
				vm.factor = response.factor;
				vm.factor_time = response.factor_time;
				
			})
			.fail(function(error){
				console.log("ocurrio un error");
				console.log(error)
			});

		},
		methods: {
			today_question: function () {
				this.user = helpers.user();
				const vm = this;

				$.ajax({
					url: helpers.url('/today-question-availability/'+this.user.id),
					type: 'GET',
					dataType: 'json'
				})
				.done(function(response) {

					if(response.status == 200){
						if(response.date_status == 1){
							vm.$router.push('/factor/response/today');
						}else{
							new Noty({
									text: 'Vuelve mañana para la pregunta del día',
									layout: 'bottomRight',
									timeout: 2500,
									progressBar: true
								}).show();
						}
					}else{
						new Noty({
								text: response.msj,
								layout: 'bottomRight',
								timeout: 2500,
								progressBar: true
							}).show();
					}
					
				})
				.fail(function(error){
					console.log("ocurrio un error");
					console.log(error)
				});
			}
		}
	},

	history: {
		template: '#factor_history',
		data: function () {
			return {
				user: null,
				factor: null,
				primary_factors: [],
				secundary_factors: [],
				factors_time: []
			}
		},
		mounted: function () {
			this.user = helpers.user();
			const vm = this;

			$.ajax({
				url: helpers.url('/user/'+this.user.id+'/response'),
				type: 'GET',
				dataType: 'json'
			})
			.done(function(response) {

				if(response.factor.ready == 1){
					vm.primary_factors = response.factor.childs;
					vm.secundary_factors = response.secundary_factors;

					vm.factors_time = response.factors_time;
				}else{
					vm.$router.push('/factor/menu');
				}
			})
			.fail(function(error){
				console.log("ocurrio un error");
				console.log(error)
			});

		},
		methods: {

		}
	},
	
	charts: {
		template: '#factor_charts',
		data: function () {
			return {
				user: null,
				factor: null,
				primary_results: [],
				secundary_results: []
			}
		},
		mounted: function () {
			this.user = helpers.user();
			const vm = this;

			$.ajax({
				url: helpers.url('/user/'+this.user.id+'/statistics'),
				type: 'GET',
				dataType: 'json'
			})
			.done(function(response) {

				if(response.status == 200){
					vm.primary_results = response.primary_results;
					vm.secundary_results = response.secundary_results;

					helpers.draw_primary_chart(vm.primary_results);
					
					setTimeout(function(){ 
						vm.draw_secundary_charts(vm.secundary_results);
					}, 3000);
					

				}else{
					new Noty({
							text: response.msj,
							layout: 'bottomRight',
							timeout: 2500,
							progressBar: true
						}).show();

					vm.$router.push('/factor/menu');
				}
			})
			.fail(function(error){
				console.log("ocurrio un error");
				console.log(error)
			});

		},
		methods: {
			draw_secundary_charts: function(results){

				for(var i=0; i<results.length; i++){

					var nombres_categoria = [], results_data = [], results_background = [];

					for(var j=0; j < results[i].factors.length; j++){
						
						nombres_categoria[j] = results[i].factors[j].nombre_categoria.substring(0, 25);


						results_data[j] = results[i].factors[j].results.percent;

						if(results[i].factors[j].results.value == 1)
							results_background[j] = "#009933";

						else if(results[i].factors[j].results.value == 2)
							results_background[j] = "#0000ff";

						else if(results[i].factors[j].results.value == 3)
							results_background[j] = "#ffff00";

						else if(results[i].factors[j].results.value == 4)
							results_background[j] = "#ff9900";

						else if(results[i].factors[j].results.value == 5)
							results_background[j] = "#ff0000";
					}


					new Chart(document.getElementById("bar-chart-"+(i+1)  ), {
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
								text: 'Resultados de la encuesta secundaria en porcentajes'
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

				}
			}
		}
	}
};

export default FactorController;