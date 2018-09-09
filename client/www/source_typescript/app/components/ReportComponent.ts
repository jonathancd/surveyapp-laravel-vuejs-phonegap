import helpers from '../../app/helpers';

import ReportController from '../../app/controllers/ReportController';

const ReportComponent = {
	manager: {
		path: '/report/manager',
		beforeEnter: function (to, from, next) {
			console.log(helpers.auth_check());
			if(helpers.auth_check()==false) {
				next({'path': '/login'});
			} else {
				next();
			}

		},
		component: ReportController.manager
	},
	create: {
		path: '/report/create',
		beforeEnter: function (to, from, next) {
			console.log(helpers.auth_check());
			if(helpers.auth_check()==false) {
				next({'path': '/login'});
			} else {
				next();
			}

		},
		component: ReportController.create
	}
}

export default ReportComponent;