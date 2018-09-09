import helpers from '../../app/helpers';

import AuthController from '../../app/controllers/AuthController';
import HomeController from '../../app/controllers/HomeController';

const HomeComponent = {
	home: {
		path: '/',
		beforeEnter: function (to, from, next) {
			console.log(helpers.auth_check());
			if(helpers.auth_check()==false) {
				next({'path': '/login'});
			} else {
				const user = helpers.user();
				if(user.rol == 0){
					next();
				}else{
					next({'path': '/factor/menu'});
				}
			}

		},
		component: HomeController.home
	},

	login: {
		path: '/login',
		beforeEnter: function (to, from, next) {
			if(helpers.auth_check()==true) {
				const user = helpers.user();
				if(user.rol == 0){
					next({'path': '/'});
				}else{
					next({'path': '/factor/menu'});
				}
			} else {
				next();
			}
		},
		component: AuthController.login
	}
}

export default HomeComponent;