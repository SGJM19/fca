import Vue from 'vue'
import Vuex from 'vuex'
import VueAxios from 'vue-axios'
import Axios from 'axios'


const state = {

}

const actions = {
	ADD_YEAR:({commit,dispatch},obj)=>{
		return new Promise((resolve,reject)=>{
			axios({
				url: '/ktool/api/addYear',
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
	GET_FCA_API:({commit,dispatch},obj)=>{
		return new Promise((resolve,reject)=>{
			axios({
				url: '/ktool/api/get_fca_table',
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
	
	/*USE FOR STORING FCA DATA*/
	STORE_OTHER_DETAILS:({commit,dispatch},obj)=>{
		return new Promise((resolve,reject)=>{
			axios({
				url:'/ktool/api/saveOtherDetails',
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
	GET_FILES_UPLOADED:({commit,dispatch},obj)=>{
		return new Promise((resolve,reject)=>{
			axios({
				url:'/ktool/api/get_files_uploaded',
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
	REMOVE_FILE:({commit,dispatch},obj)=>{
		return new Promise((resolve,reject)=>{
			axios({
				url:'/ktool/api/removeFile',
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
	}
}

const mutations = {

}

const getters = {

}

export default { state, actions, mutations, getters }