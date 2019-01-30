<template>
<div id="home" style="height:100%; margin-top:-86px; border:1px solid #000;" class="red accent-4">
	<v-container fill-height style="height:100%;">
		<v-layout row wrap>
			<v-flex class="text-xs-center" style="display: flex; justify-content: center; flex-direction: column;">

				<div style="color:white;" class="display-3">{{$t('Financial Control Audit')}} </div>
				<div style="color:white;">
					{{$t('Efficiently allocate the company\'s limited resources towards achieving its organizational objective of maximizing profits.')}}

					
				</div>
				<br><br>
				<div>
					<v-btn dark router :to="{name: redirectory(UserInfo.role ? UserInfo.role : []) }" outline> {{$t('Get Started')}}</v-btn>
				</div>
			</v-flex>
		</v-layout>
	</v-container>
</div>
</template>
<script>
	import {mapState} from 'vuex'
	import _ from 'lodash'
	export default{
		data(){
			return{

			}
		},
		computed:{
			...mapState({
				UserInfo: state => state.Authentication.currentUserInfo
			})
		},
		methods:{
			redirectory($roles){
				console.log($roles.length)
				let if_arr_admin = ['owner','director','admin','president'];
				if(_.intersection($roles, if_arr_admin).length > 0){ // if this is admin redirect to admin dashboard shit
					return 'fca_dashboard'
				}else if(!$roles.length){
					return 'Login'
				} else{ //else if this is not admin
					return 'Fca'
				}
			}
		},
		created(){

		},
		mounted(){
			
		}
	}
</script>
<style scoped>

</style>