import helpers from '../../app/helpers';
import * as $ from 'jquery';

const AuthController = {
	login: {
		template: '#login_template',
		data: function () {
			return {
				user: null
			}
		},
		mounted: function () {
			this.user = helpers.user();
		},
		methods: {
			login: function () {
				helpers.ajax('login', this);
			}
		}
	}
};

export default AuthController;