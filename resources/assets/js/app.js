require('./bootstrap');
import Vue from 'vue';

import VueRouter from 'vue-router'
Vue.use(VueRouter);

import Vuetify from 'vuetify'
Vue.use(Vuetify);

import VeeValidate from 'vee-validate';
Vue.use(VeeValidate);

import infiniteScroll from 'vue-infinite-scroll'
Vue.use(infiniteScroll)

import App from './components/App.vue'
import store from './store.js'
import router from './router.js'
import _ from 'lodash'
Vue.use(_);

import GlobalScript from './components/js/GlobalScript.js'
Vue.use(GlobalScript);

/*import uploader from 'vue-simple-uploader'
Vue.use(uploader)*/

import vuexI18n from 'vuex-i18n';
import fr_json from './trans.json'
Vue.use(vuexI18n.plugin, store)
const translationsDe = fr_json; //french translations
Vue.i18n.add('fr', translationsDe);

/*condition here change language based on location storage*/
if(localStorage.getItem('flang') !== null){
	const currentLang = localStorage.getItem('flang');
	Vue.i18n.set(currentLang);
}else{
	Vue.i18n.set('en');
}
/*loading bar*/
import { loadProgressBar } from 'axios-progress-bar'
import 'axios-progress-bar/dist/nprogress.css'
loadProgressBar()

Vue.use(store)


Vue.component('example-component', require('./components/App.vue'));

/*Upon loading lets get user proof*/


/*after logged in check*/
router.afterEach((to, from) => {
  // ...

})

if(store.state.Authentication.currentUserInfo.length <= 0 && window.localStorage.getItem('ktool_token') !== null){
	store.dispatch('USER_DETAILS')
		.then(response=>{
			if(response.status === true){

			}
		})
		.catch(error=>{

		});
}else{

}


/*NAVIGATION GUARD*/
router.beforeEach((to, from, next) => {
	let isLoggedIn = store.state.Authentication.isLoggedIn //
	if(to.matched.some(record => record.meta.requiresAuth)){ //check if thsi meta requires to be authenticated
		if(isLoggedIn){ //If use logged in
				/*if logged user trying the access something*/
				//get current roles 
				var curRoles = [];
				if(localStorage.getItem('ktool_token') !== null){
					var curRoles = JSON.parse(localStorage.getItem('ktool_token'));
				}

				//console.log(_.intersection(to.meta.roles, ['director','owner']).length);
				if(to.name === 'fca_dashboard' && _.intersection(to.meta.roles, curRoles.roles).length <= 0){
					next({
						path: '/ktool'
					})
				}else{
					next()	
				}

					
		} else{ //else if user not logged in 
			next({
				path:'/login',
				query: {redirect:'/'}
			})
		}
	}else if(to.matched.some(record => record.meta.forGuess) === true ){
		if(isLoggedIn){ //if logged in or authenticated prevent from viewing this because this is for guess only
			next({
				path:'/ktool'
			})
		} else{ //else if user logged in
			next()
		}
	}else{
		next()
	}
	
	document.title = to.meta.title
})

router.afterEach((to, from) => {
  // ...
  
})



new Vue(Vue.util.extend({
	store,
	router
}, App))
.$mount('#app')