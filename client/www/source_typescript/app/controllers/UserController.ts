import helpers from '../../app/helpers';
import * as $ from 'jquery';


const UserController = {
	manager: {
		template: '#user_manager',
		data: function () {
			return {
				user: null,
				users: []
			}
		},
		mounted: function () {
			this.user = helpers.user();
			const vm = this;

			$.ajax({
				url: helpers.url('/user'),
				type: 'GET',
				dataType: 'json'
			})
			.done(function( response) {
				vm.users = response;
			});

		},
		methods: {
			destroy: function (id) {
				const vm = this;

				$.ajax({
					url: helpers.url('/user/'+id),
					type: 'DELETE',
					dataType: 'json'
				})
				.done(function(response) {
					vm.users = response;
				});
			}
		}
	},
	create: {
		template: '#user_create',
		data: function () {
			return {
				user: null,
				institutions: null,
				programs: null
			}
		},
		mounted: function () {
			this.user = helpers.user();
			const vm = this;

			$.ajax({
				url: helpers.url('/user/create'),
				type: 'GET',
				dataType: 'json'
			})
			.done(function( response) {
				vm.institutions = response.institutions;
				vm.programs = response.programs;
			});
		},
		methods: {
			save: function () {
				helpers.ajax('user_create', this);
			}
		}
	},
	show: {
		template: '#user_show',
		data: function () {
			return {
				user: null,
				user_show: null,
			}
		},
		mounted: function () {
			this.user = helpers.user();
			const vm = this;

			$.ajax({
				url: helpers.url('/user/'+this.$route.params.id),
				type: 'GET',
				dataType: 'json'
			})
			.done(function(response) {
				vm.user_show = response;
			});


		}
	},
	edit: {
		template: '#user_edit',
		data: function () {
			return {
				user: null,
				user_edit: null,
				institutions: null,
				programs: null
			}
		},
		mounted: function () {
			this.user = helpers.user();
			const vm = this;

			$.ajax({
				url: helpers.url('/user/'+this.$route.params.id+'/edit'),
				type: 'GET',
				dataType: 'json'
			})
			.done(function(response) {
				vm.user_edit = response.user;
				vm.institutions = response.institutions;
				vm.programs = response.programs;
			});


		},
		methods: {
			save: function () {
				helpers.ajax('user_edit', this);
			}
		}
	}
};

export default UserController;