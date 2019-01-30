import Vue from 'vue'
import Vuex from 'vuex'
import VueAxios from 'vue-axios'
import Axios from 'axios'


const state = {

}

const actions = {
	GET_ARL_DASHBOARD:({commit,dispatch},obj)=>{
		return new Promise((resolve, reject)=>{
			axios({
				url:'/ktool/api/getarl_dashboard',
				method:'post',
				data: obj,
				config:'JSON'
			})
			.then(response=>{
				if(response.status == 200){
					resolve(response.data);
				}
			})
			.catch(error=>{
				console.log(error);
			})
		})
	},
	GET_ARLS:({commit,dispatch},obj)=>{
		return new Promise((resolve, reject)=>{
			axios({
				url:'/ktool/api/get_arls',
				method:'post',
				data: obj,
				config:'JSON'
			})
			.then(response=>{
				if(response.status == 200){
					resolve(response.data);
				}
			})
			.catch(error=>{
				console.log(error);
			})
		})
	},
	GET_ARL_REPORTS:({commit,dispatch},obj)=>{
		return new Promise((resolve, reject)=>{
			axios({
				url:'/ktool/api/getReportDetails',
				method:'post',
				data: obj,
				config:'JSON'
			})
			.then(response=>{
				if(response.status == 200){
					resolve(response.data);
				}
			})
			.catch(error=>{
				console.log(error);
			})
		})
	},
	SUBMIT_PENDING:({commit,dispatch},obj)=>{
		return new Promise((resolve, reject)=>{
			axios({
				url:'/ktool/api/submit_pending',
				method:'post',
				data: obj,
				config:'JSON'
			})
			.then(response=>{
				if(response.status == 200){
					resolve(response.data);
				}
			})
			.catch(error=>{
				console.log(error);
			})
		})
	},
	SEND_FEEDBACK:({commit,dispatch},obj)=>{
		return new Promise((resolve, reject)=>{
			axios({
				url:'/ktool/api/send_feedback',
				method:'post',
				data: obj,
				config:'JSON'
			})
			.then(response=>{
				if(response.status == 200){
					resolve(response.data);
				}
			})
			.catch(error=>{
				console.log(error);
			})
		})
	},
	getFeedBacks:({commit,dispatch},obj)=>{
		return new Promise((resolve, reject)=>{
			axios({
				url:'/ktool/api/getFeedBacks',
				method:'post',
				data: obj,
				config:'JSON'
			})
			.then(response=>{
				if(response.status == 200){
					resolve(response.data);
				}
			})
			.catch(error=>{
				reject(error)
				console.log(error);
			})
		})
	}
}

const mutations=  {

}

const getters = {

}

export default { state, actions, mutations, getters }