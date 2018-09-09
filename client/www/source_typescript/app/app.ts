import Vue from 'vue';
import Vuex from 'vuex';
import * as $ from 'jquery';
import helpers from '../app/helpers';

import VueRouter from 'vue-router';
import HomeComponent from '../app/components/HomeComponent';
import UserComponent from '../app/components/UserComponent';
import InstitutionComponent from '../app/components/InstitutionComponent';
import ProgramComponent from '../app/components/ProgramComponent';
import FactorComponent from '../app/components/FactorComponent';
import ReportComponent from '../app/components/ReportComponent';

Vue.config.productionTip = false;
Vue.use(VueRouter);
Vue.use(Vuex);

const routes = [
    HomeComponent.home,
    HomeComponent.login,

    UserComponent.manager,
    UserComponent.create,
    UserComponent.show,
    UserComponent.edit,

    InstitutionComponent.manager,
    InstitutionComponent.create,
    InstitutionComponent.edit,

    ProgramComponent.manager,
    ProgramComponent.create,
    ProgramComponent.edit,

    FactorComponent.manager,
    FactorComponent.questions,
    FactorComponent.question_create,
    FactorComponent.question_edit,


    FactorComponent.response,
    FactorComponent.response_today,
    FactorComponent.response_success,
    FactorComponent.history,
    FactorComponent.menu,
    FactorComponent.charts,

    ReportComponent.manager,
    ReportComponent.create,
];

const router = new VueRouter({ routes });


const store = new Vuex.Store({
  state: {
    cordova: ''
  }
});




helpers.load_view('home');
helpers.load_view('login');
helpers.load_view('parts/top_header');
helpers.load_view('parts/breadcrumb');
helpers.load_view('parts/page_sidebar');

helpers.load_view('user/manager');
helpers.load_view('user/create');
helpers.load_view('user/show');
helpers.load_view('user/edit');

helpers.load_view('institution/manager');
helpers.load_view('institution/create');
helpers.load_view('institution/edit');

helpers.load_view('program/manager');
helpers.load_view('program/create');
helpers.load_view('program/edit');

helpers.load_view('factor/manager');
helpers.load_view('factor/questions');
helpers.load_view('factor/question_create');
helpers.load_view('factor/question_edit');
helpers.load_view('factor/response');
helpers.load_view('factor/response_today_question');
helpers.load_view('factor/response_success');
helpers.load_view('factor/history');
helpers.load_view('factor/menu');
helpers.load_view('factor/charts');

helpers.load_view('report/manager');
helpers.load_view('report/create');

helpers.load_view('parts/page_footer');

Vue.component('top_header', {
  props: ['user'],
  template: '#top_header',
  methods: {
    logout: function () {
        sessionStorage.setItem('user_cb', 'null');
        this.$router.push('/login');
    }
  }
});

Vue.component('breadcrumb', {
  props: ['title'],
  template: '#breadcrumb'
});

Vue.component('page_sidebar', {
  props: ['user'],
  template: '#page_sidebar'
});

Vue.component('page_footer', {
  template: '#page_footer'
});


setTimeout(function(){
	new Vue({
	  store,
	  router
	}).$mount('#app');
}, 500);

