import Vue from 'vue'
import Vuex from 'vuex'
import VueAxios from 'vue-axios'
import Axios from 'axios'


const state = {

}

const actions = {
	GET_ARL:({commit,dispatch},obj)=>{
		return new Promise((resolve,reject)=>{
			axios({
				url: '/ktool/api/getarls',
				method:'POST',
				data: {},
				config:'JSON'
			})
			.then(response=>{
				if(response.status == 200){
					resolve(response.data)
				}
			})
			.catch(error=>{
				reject(error)
			})
		})
	},
	SET_MONTH_SETTINGS:({commit,dispatch}, obj)=>{
		return new Promise((resolve,reject)=>{
			axios({
				url: '/ktool/api/set_arl_settings',
				method:'POST',
				data: obj,
				config:'JSON'
			})
			.then(response=>{
				if(response.status == 200){
					resolve(response.data)
				}
			})
			.catch(error=>{
				reject(error)
			})
		})
	},
	GET_CURRENT_ALLOWED:({commit,dispatch},obj)=>{
		return new Promise((resolve,reject)=>{
			axios({
				url: '/ktool/api/getCurrentMonths',
				method:'POST',
				data: obj,
				config:'JSON'
			})
			.then(response=>{
				if(response.status == 200){
					resolve(response.data)
				}
			})
			.catch(error=>{
				reject(error)
			})
		})
	},
	STORE_EXP_DATE:({commit,dispatch},obj)=>{
		return new Promise((resolve,reject)=>{
			axios({
				url: '/ktool/api/saveExpSettings',
				method:'POST',
				data: obj,
				config:'JSON'
			})
			.then(response=>{
				if(response.status == 200){
					resolve(response.data)
				}
			})
			.catch(error=>{
				reject(error)
			})
		})
	},
	GET_EXP_DATE:({commit,dispatch},obj)=>{
		return new Promise((resolve,reject)=>{
			axios({
				url: '/ktool/api/get_exp_date',
				method:'POST',
				data: obj,
				config:'JSON'
			})
			.then(response=>{
				if(response.status == 200){
					resolve(response.data)
				}
			})
			.catch(error=>{
				reject(error)
			})
		})
	},
	STORE_THRESHOLD_PERCENT:({commit,dispatch},obj)=>{
		return new Promise((resolve,reject)=>{
			axios({
				url: '/ktool/api/store_threshold_percent',
				method:'POST',
				data: obj,
				config:'JSON'
			})
			.then(response=>{
				if(response.status == 200){
					resolve(response.data)
				}
			})
			.catch(error=>{
				reject(error)
			})
		})
	},
	GET_THRESHOLD_PERCENT:({commit,dispatch},obj)=>{
		return new Promise((resolve,reject)=>{
			axios({
				url: '/ktool/api/get_store_threshold_percent',
				method:'get',
				data: obj,
				config:'JSON'
			})
			.then(response=>{
				if(response.status == 200){
					resolve(response.data)
				}
			})
			.catch(error=>{
				reject(error)
			})
		})
	},
	GET_USERS:({commit,dispatch},obj)=>{
		return new Promise((resolve,reject)=>{
			axios({
				url: '/ktool/api/get_users',
				method:'post',
				data: obj,
				config:'JSON'
			})
			.then(response=>{
				if(response.status == 200){
					resolve(response.data)
				}
			})
			.catch(error=>{
				reject(error)
			})
		})
	},
	STORE_EMAIL_TO:({commit,dispatch},obj)=>{
		return new Promise((resolve,reject)=>{
			axios({
				url: '/ktool/api/store_email_to',
				method:'post',
				data: obj,
				config:'JSON'
			})
			.then(response=>{
				if(response.status == 200){
					resolve(response.data)
				}
			})
			.catch(error=>{
				reject(error)
			})
		})
	}
}

const mutations = {

}

const getters = {

}

export default { state, actions, mutations, getters}