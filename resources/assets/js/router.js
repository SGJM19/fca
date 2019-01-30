import VueRouter from 'vue-router'
import Login from './components/pages/Login.vue';
import Home from './components/pages/Home.vue';
import ErrorPage from './components/pages/ErrorPage.vue';
import Fca from './components/pages/Fca.vue';
import FcaDashboard from './components/admin/FcaDashboard.vue';
import Settings from './components/admin/Settings.vue';
import ExemptStore from './components/admin/ExemptStore.vue'


let routes = [
	{
		name: 'Login',
		path: '/ktool/login',
		component: Login,
		meta: {
			title: 'Login',
			forGuess: true,
			redirectTo: '/home'
		}
	},
	{
		name: 'Home',
		path: '/ktool',
		component:Home,
		meta:{
			title: 'Home',
			forGuess: false
		}
	},
	{
		name: 'Fca',
		path: '/ktool/fca',
		component:Fca,
		meta:{
			title: 'Fca - Uploads',
			requiresAuth: true,
			roles:['arl','director','admin']
		}
	},
	{
		name: 'fca_dashboard',
		path: '/ktool/fca_dashboard',
		component: FcaDashboard,

		meta:{
			title: 'Fca Dashboard',
			requiresAuth: true,
			roles:['director','owner','admin']
		}
	},
	{
		name: 'exempt_store',
		path: '/ktool/exempt_store',
		component: ExemptStore,
		meta: {
			title: 'Fca - Exempt Store',
			requiresAuth: true,
			roles: ['director', 'owner', 'admin']
		}
	},
	{
		name: 'fca_settings',
		path: '/ktool/fca_settings',
		component: Settings,
		meta:{
			title: 'Settings',
			requiresAuth: true,
			roles:['director','arl','admin']
		}
	},
	{
		name: 'error_page',
		path: '/ktool/error_page',
		component:ErrorPage,
		meta:{
			title: 'Error 404'
		}
	},
	{ path: "*", redirect: '/ktool/error_page' }
]

export default new VueRouter({
	routes,
	mode:'history'
});