import helpers from '../../app/helpers';
import FactorController from '../../app/controllers/FactorController';

const FactorComponent = {
	manager: {
		path: '/factor/manager',
		beforeEnter: function (to, from, next) {
			console.log(helpers.auth_check());
			if(helpers.auth_check()==false) {
				next({'path': '/login'});
			} else {
				next();
			}

		},
		component: FactorController.manager
	},
	questions: {
		path: '/factor/questions/:id',
		beforeEnter: function (to, from, next) {
			console.log(helpers.auth_check());
			if(helpers.auth_check()==false) {
				next({'path': '/login'});
			} else {
				next();
			}

		},
		component: FactorController.questions
	},
	question_create: {
		path: '/factor/question/create/:id',
		beforeEnter: function (to, from, next) {
			console.log(helpers.auth_check());
			if(helpers.auth_check()==false) {
				next({'path': '/login'});
			} else {
				next();
			}

		},
		component: FactorController.question_create
	},
	question_edit: {
		path: '/factor/question/edit/:id',
		beforeEnter: function (to, from, next) {
			console.log(helpers.auth_check());
			if(helpers.auth_check()==false) {
				next({'path': '/login'});
			} else {
				next();
			}

		},
		component: FactorController.question_edit
	},
	response: {
		path: '/factor/response',
		beforeEnter: function (to, from, next) {
			console.log(helpers.auth_check());
			if(helpers.auth_check()==false) {
				next({'path': '/login'});
			} else {
				next();
			}

		},
		component: FactorController.response
	},
	response_today: {
		path: '/factor/response/today',
		beforeEnter: function (to, from, next) {
			console.log(helpers.auth_check());
			if(helpers.auth_check()==false) {
				next({'path': '/login'});
			} else {
				next();
			}

		},
		component: FactorController.response_today
	},
	response_success: {
		path: '/factor/response/success',
		beforeEnter: function (to, from, next) {
			console.log(helpers.auth_check());
			if(helpers.auth_check()==false) {
				next({'path': '/login'});
			} else {
				next();
			}

		},
		component: FactorController.response_success
	},
	menu: {
		path: '/factor/menu',
		beforeEnter: function (to, from, next) {
			console.log(helpers.auth_check());
			if(helpers.auth_check()==false) {
				next({'path': '/login'});
			} else {
				next();
			}

		},
		component: FactorController.menu
	},
	history: {
		path: '/factor/response/history',
		beforeEnter: function (to, from, next) {
			console.log(helpers.auth_check());
			if(helpers.auth_check()==false) {
				next({'path': '/login'});
			} else {
				next();
			}

		},
		component: FactorController.history
	},
	charts: {
		path: '/factor/charts',
		beforeEnter: function (to, from, next) {
			console.log(helpers.auth_check());
			if(helpers.auth_check()==false) {
				next({'path': '/login'});
			} else {
				next();
			}

		},
		component: FactorController.charts
	}
}

export default FactorComponent;