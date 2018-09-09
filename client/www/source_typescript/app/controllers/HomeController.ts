import helpers from '../../app/helpers';
import * as $ from 'jquery';


const HomeController = {
	home: {
		template: '#home_template',
		data: function () {
			return {
				user: null,
				users: null,
				questions: null
			}
		},
		mounted: function () {
			this.user = helpers.user();
			const vm = this;


			$.ajax({
				url: helpers.url('/home'),
				type: 'GET',
				dataType: 'json'
			})
			.done(function(response) {
				vm.users = response.users;
				vm.questions = response.questions;
			});

		}
	}
};

export default HomeController;