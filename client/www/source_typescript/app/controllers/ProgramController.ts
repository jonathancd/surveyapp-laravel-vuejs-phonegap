import helpers from '../../app/helpers';
import * as $ from 'jquery';


const ProgramController = {
	manager: {
		template: '#program_manager',
		data: function () {
			return {
				user: null,
				programs: []
			}
		},
		mounted: function () {
			this.user = helpers.user();
			const vm = this;

			$.ajax({
				url: helpers.url('/program'),
				type: 'GET',
				dataType: 'json'
			})
			.done(function( response) {
				vm.programs = response;
			});

		},
		methods: {
			destroy: function (id) {
				const vm = this;

				$.ajax({
					url: helpers.url('/program/'+id),
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
						vm.programs = response.programs;
					}
				});
			}
		}
	},
	create: {
		template: '#program_create',
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
			save: function () {
				helpers.ajax('program_create', this);
			}
		}
	},
	edit: {
		template: '#program_edit',
		data: function () {
			return {
				user: null,
				program_edit: null,
			}
		},
		mounted: function () {
			this.user = helpers.user();
			const vm = this;

			$.ajax({
				url: helpers.url('/program/'+this.$route.params.id),
				type: 'GET',
				dataType: 'json'
			})
			.done(function(response) {

				if(response.status == 200){
					vm.program_edit = response.program;
				}else{
					new Noty({
							text: response.msj,
							layout: 'bottomRight',
							timeout: 2500,
							progressBar: true
						}).show();
				}
				
			});


		},
		methods: {
			save: function () {
				helpers.ajax('program_edit', this);
			}
		}
	}
};

export default ProgramController;