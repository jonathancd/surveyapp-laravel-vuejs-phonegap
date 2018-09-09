import helpers from '../../app/helpers';

import UserController from '../../app/controllers/UserController';

const UserComponent = {
	manager: {
		path: '/user/manager',
		beforeEnter: function (to, from, next) {
			console.log(helpers.auth_check());
			if(helpers.auth_check()==false) {
				next({'path': '/login'});
			} else {
				next();
			}

		},
		component: UserController.manager
	},
	create: {
		path: '/user/create',
		beforeEnter: function (to, from, next) {
			console.log(helpers.auth_check());
			if(helpers.auth_check()==false) {
				next({'path': '/login'});
			} else {
				next();
			}

		},
		component: UserController.create
	},
	show: {
		path: '/user/show/:id',
		beforeEnter: function (to, from, next) {
			console.log(helpers.auth_check());
			if(helpers.auth_check()==false) {
				next({'path': '/login'});
			} else {
				next();
			}

		},
		component: UserController.show
	},
	edit: {
		path: '/user/edit/:id',
		beforeEnter: function (to, from, next) {
			console.log(helpers.auth_check());
			if(helpers.auth_check()==false) {
				next({'path': '/login'});
			} else {
				next();
			}

		},
		component: UserController.edit
	}
}

export default UserComponent;