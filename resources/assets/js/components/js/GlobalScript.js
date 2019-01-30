import Vue from 'vue';
import VueAxios from 'vue-axios';
import axios from 'axios';
import store from '../../store'
import _ from 'lodash';
import Vuex from 'vuex'


//Vue.use(Auth);
Vue.use(Vuex);
Vue.use(VueAxios, axios);

export default function (Vue) {
	Vue.gs = {
		formatNum(num) {
			var p = num.toFixed(2).split(".");
			return "" + p[0].split("").reverse().reduce(function (acc, num, i, orig) {
				return num == "-" ? acc : num + (i && !(i % 3) ? "," : "") + acc;
			}, "") + "." + p[1];
		},
		checkRoles(arr) { //parameter 1: array to check
			var roles = store.state.Global.roles
			var isHave = _.intersection(arr, roles).length;
			if (isHave > 0) {
				return true;
			} else {
				return false;
			}
		},
		callTest($wew) {
			return $wew;
		},
		translate(callback) {
			callback(arguments[1]);
		},
		date_replace($currentDate) {
			try {
				let curDate = $currentDate
					.replace(/\d+|^\s+|\s+$/g, '')
					.replace(',', '')
					.replace(':', '')
					.replace('pm', '')
					.replace('am', '')
					.trim();

				var convertedDate = '';
				switch (curDate) {
					case 'January':
						convertedDate = 'Janvier'
						break;
					case 'February':
						convertedDate = 'Février'
						break;
					case 'March':
						convertedDate = 'Mars'
						break;
					case 'April':
						convertedDate = 'Avril'
						break;
					case 'May':
						convertedDate = 'Mai'
						break;
					case 'June':
						convertedDate = 'Juin'
						break;
					case 'July':
						convertedDate = 'Juillet'
						break;
					case 'August':
						convertedDate = 'Août'
						break;
					case 'September':
						convertedDate = 'Septembre'
						break;
					case 'October':
						convertedDate = 'Octobre'
						break;
					case 'November':
						convertedDate = 'November'
						break;
					case 'December':
						convertedDate = 'Décembre'
						break;
					default:
						convertedDate = curDate // statements_def
						break;
				}


				if (localStorage.getItem('flang') === 'fr') {
					return convertedDate + $currentDate.replace(curDate, '');
				} else {
					return $currentDate
				}
			} catch (e) {

			}
		},
		getCurrentDate(){
			var today = new Date();
			var dd = today.getDate();
			var mm = today.getMonth()+1; //January is 0!
			var yyyy = today.getFullYear();

			if(dd<10) {
				dd = '0'+dd
			} 

			if(mm<10) {
				mm = '0'+mm
			} 

			today = yyyy + '-' + mm + '-' + dd;
			return today
		},
		getPredef_date($a){
			if($a === 'action'){
				if(localStorage.getItem('predef_date_range') !== null){
					return localStorage.getItem('predef_date_range')
				}else{
					return 0
				}
			}else if ($a === 'repeat_offender'){
				if(localStorage.getItem('predef_date_range_ro') !== null){
					return localStorage.getItem('predef_date_range_ro')
				}else{
					return 0
				}
			}else{
				return 0
			}
				
		}
	}
	Object.defineProperties(Vue.prototype, {
		$gs: {
			get: () => {
				return Vue.gs;
			}
		}
	});
}