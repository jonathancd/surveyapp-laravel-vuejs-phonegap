import helpers from '../../app/helpers';
import * as $ from 'jquery';
import * as Noty from 'noty';

const ReportController = {
	manager: {
		template: '#report_manager',
		data: function () {
			return {
				user: null,
				reports: []
			}
		},
		mounted: function () {
			this.user = helpers.user();
			const vm = this;

			$.ajax({
				url: helpers.url('/report'),
				type: 'GET',
				dataType: 'json'
			})
			.done(function(response) {

				if(response.status == 200){
					vm.reports = response.reports;
					vm.url = helpers.url('/uploads/')

					for(var i=0; i<vm.reports.length;i++){
						vm.reports[i].url = vm.url+vm.reports[i].name;
					}
				}else{
					new Noty({
							text: 'No hay reportes creados',
							layout: 'bottomRight',
							timeout: 2500,
							progressBar: true
						}).show();
				}
			});


		},
		methods:{
			destroy: function (id) {
				const vm = this;

				$.ajax({
					url: helpers.url('/report/'+id),
					type: 'DELETE',
					dataType: 'json'
				})
				.done(function(response) {

					new Noty({
							text: response.msj,
							layout: 'bottomRight',
							timeout: 2500,
							progressBar: true
						}).show();

					if(response.status == 200){
						vm.reports = response.reports;

						for(var i=0; i<vm.reports.length;i++){
							vm.reports[i].url = vm.url+'/'+vm.reports[i].name;
						}
					}
					
				});
			}
		}
	},
	create: {
		template: '#report_create',
		data: function () {
			return {
				user: null,
				users: []
			}
		},
		mounted: function () {
			this.user = helpers.user();
			const vm = this;

		},
		methods: {
			create: function (url) {
				const vm = this;

				new Noty({
						text: 'Creando reporte...',
						layout: 'bottomRight',
						timeout: 2500,
						progressBar: true
					}).show();

				$.ajax({
					url: helpers.url(url),   
					type: 'post',
					dataType: 'json'
				})
				.done(function(response) {

					if(response.status == 200){
						new Noty({
								text: response.msj,
								layout: 'bottomRight',
								timeout: 2500,
								progressBar: true
							}).show();
					}else{
						new Noty({
								text: 'Ocurrio un error al tratar de generar reporte',
								layout: 'bottomRight',
								timeout: 2500,
								progressBar: true
							}).show();
					}
					
				});
			}
		}
	}
};

export default ReportController;