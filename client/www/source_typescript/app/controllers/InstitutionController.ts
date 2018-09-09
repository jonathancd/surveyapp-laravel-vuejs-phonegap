import helpers from '../../app/helpers';
import * as $ from 'jquery';


const InstitutionController = {
	manager: {
		template: '#institution_manager',
		data: function () {
			return {
				user: null,
				institutions: []
			}
		},
		mounted: function () {
			this.user = helpers.user();
			const vm = this;

			$.ajax({
				url: helpers.url('/institution'),
				type: 'GET',
				dataType: 'json'
			})
			.done(function( response) {
				vm.institutions = response;
			});

		},
		methods: {
			destroy: function (id) {
				const vm = this;

				$.ajax({
					url: helpers.url('/institution/'+id),
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
						vm.institutions = response.institutions;
					}
				});
			}
		}
	},
	create: {
		template: '#institution_create',
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
				helpers.ajax('institution_create', this);
			}
		}
	},
	edit: {
		template: '#institution_edit',
		data: function () {
			return {
				user: null,
				institution_edit: null,
			}
		},
		mounted: function () {
			this.user = helpers.user();
			const vm = this;

			$.ajax({
				url: helpers.url('/institution/'+this.$route.params.id),
				type: 'GET',
				dataType: 'json'
			})
			.done(function(response) {

				if(response.status == 200){
					vm.institution_edit = response.institution;
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
				helpers.ajax('institution_edit', this);
			}
		}
	}
};

export default InstitutionController;