import Vue from 'vue';
import Vuex from 'vuex';
import Global from './components/js/vuex/Global.js'
import Authentication from './components/js/vuex/Authentication.js'
import Fca from './components/js/vuex/Fca.js'
import Settings from './components/js/vuex/Settings.js'
import FcaDashboard from './components/js/vuex/FcaDashboard.js'

Vue.use(Vuex);
const debug = process.env.NODE_ENV !== 'production'
export default new Vuex.Store({
	modules:{
		Global,
		Authentication,
		Fca,
		Settings,
		FcaDashboard
	},
	strict:debug

})
