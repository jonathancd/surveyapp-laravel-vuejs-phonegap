import helpers from '../../app/helpers';

import InstitutionController from '../../app/controllers/InstitutionController';

const InstitutionComponent = {
	manager: {
		path: '/institution/manager',
		beforeEnter: function (to, from, next) {
			console.log(helpers.auth_check());
			if(helpers.auth_check()==false) {
				next({'path': '/login'});
			} else {
				next();
			}

		},
		component: InstitutionController.manager
	},
	create: {
		path: '/institution/create',
		beforeEnter: function (to, from, next) {
			console.log(helpers.auth_check());
			if(helpers.auth_check()==false) {
				next({'path': '/login'});
			} else {
				next();
			}

		},
		component: InstitutionController.create
	},
	edit: {
		path: '/institution/edit/:id',
		beforeEnter: function (to, from, next) {
			console.log(helpers.auth_check());
			if(helpers.auth_check()==false) {
				next({'path': '/login'});
			} else {
				next();
			}

		},
		component: InstitutionController.edit
	}
}

export default InstitutionComponent;