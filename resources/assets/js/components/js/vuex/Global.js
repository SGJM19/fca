import Vue from 'vue'
import axios from 'axios'
import VueAxios from 'vue-axios'
Vue.use(VueAxios,axios)

const state = {

}
const actions = {
	GET_STORES:({commit,dispatch},obj)=>{
		return new Promise((resolve,reject)=>{
			axios({
				url:'/ktool/api/get_stores',
				method:'get',
				data:{},
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
	COMMIT_ACTION_NO_FILE:({commit, dispatch}, obj)=>{
		return new Promise((resolve,reject)=>{
			axios({
				url:obj.url,
				method:obj.method,
				data:obj.data,
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

export default {state, actions, mutations, getters}