import helpers from '../../app/helpers';

import ProgramController from '../../app/controllers/ProgramController';

const ProgramComponent = {
	manager: {
		path: '/program/manager',
		beforeEnter: function (to, from, next) {
			console.log(helpers.auth_check());
			if(helpers.auth_check()==false) {
				next({'path': '/login'});
			} else {
				next();
			}

		},
		component: ProgramController.manager
	},
	create: {
		path: '/program/create',
		beforeEnter: function (to, from, next) {
			console.log(helpers.auth_check());
			if(helpers.auth_check()==false) {
				next({'path': '/login'});
			} else {
				next();
			}

		},
		component: ProgramController.create
	},
	edit: {
		path: '/program/edit/:id',
		beforeEnter: function (to, from, next) {
			console.log(helpers.auth_check());
			if(helpers.auth_check()==false) {
				next({'path': '/login'});
			} else {
				next();
			}

		},
		component: ProgramController.edit
	}
}

export default ProgramComponent;