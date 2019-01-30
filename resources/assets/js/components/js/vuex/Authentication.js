import Vue from 'vue'
import Vuex from 'vuex'
import VueAxios from 'vue-axios'
import Axios from 'axios'

const state = {
	isLoggedIn: !!localStorage.getItem('ktool_token'),
	currentUserInfo: [],
	isLoggedInProofed: false
}

const actions = {
	/*LOGIN, REQUEST FOR AUTHENTICATIOn*/
	AUTH_REQUEST:({commit,dispatch},obj)=>{
		return new Promise((resolve,reject) => {
			axios({
					url: '/ktool/oauth/token',
					data: obj,
					method:'post',
					config:'JSON'
				})
				.then(response=>{
					if(response.status == 200){
						resolve(response.data);
					}
				})
				.catch(error=>{
					reject(error);
					localStorage.removeItem('ktool_token');
					commit('AUTH_ERROR',error);
				})
		
		})
	},
	/*GET THE USER DETAILS*/
	USER_DETAILS:({commit,dispatch},obj)=>{
		return new Promise((resolve,reject)=>{
			axios({
				url: '/ktool/api/user',
				method:'get',
				data:{},
				config:'JSON'
			})
			.then(response=>{
				if(response.status == 200){
					const userdata = {
						name: response.data.name,
						last_name: response.data.last_name,
						email: response.data.email,
						role: response.data.roles,
						permission: response.data.permissions,
						user_id: response.data.user_id 
					}

					//set to localstorage
					if(localStorage.getItem('ktool_token') !== null){
						var l = JSON.parse(localStorage.getItem('ktool_token'));
						l.roles = response.data.roles
						l.permissions = response.data.permissions
						localStorage.setItem('ktool_token', JSON.stringify(l))
					}
					

	
					commit('SET_USER_DETAILS',userdata)
					resolve(response.data)
					if(response.data.status === true){
						commit('SET_AUTH_PROOFED', response.data.status)
					}
				}
			})
			.catch(error=>{
				reject(error.response)
				alert('You are force to log out due to security issue.')
				localStorage.removeItem('ktool_token');
				commit('AUTH_ERROR',error);
				console.log(error);
			})
		})
	},
	LOG_OUT:({commit, dispatch}, obj)=>{

			if(window.localStorage.removeItem('ktool_token'))
				commit('CLEAR_AUTH')
		
		
	
	}
}

const mutations = {
	AUTH_ERROR:(state,obj)=>{

	},
	SET_USER_DETAILS:(state,obj)=>{
		state.currentUserInfo = obj
	},
	SET_AUTH_PROOFED:(state,obj)=>{
		state.isLoggedInProofed = obj
	},
	CLEAR_AUTH:(state,obj)=>{
		window.localStorage.removeItem('ktool_token')
		state.isLoggedIn = null,
		state.isLoggedInProofed = false,
		state.currentUserInfo = []
	}
}

const getters = {

}

export default {state, actions, mutations, getters}