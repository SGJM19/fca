<template>
	<div id="login">
		<v-container fluid fill-height>
			<v-layout align-center justify-center>
				<v-flex xs12 sm8 md4>
					<v-card class="elevation-1">
							<v-alert color="error" icon="warning" v-model="login_alert" outline dismissible>
								Invalid Credentials
							</v-alert>
              <v-toolbar dark color="white accent-4" class="elevation-0">
                <v-toolbar-title class="grey--text text--darken-2" style="width:100%;">
						    	<v-flex  class="text-xs-center">
							    	{{$t('Login')}}  
						    	</v-flex>
						   	</v-toolbar-title>
              
              </v-toolbar>
              <v-card-text>
                <v-form>
                  <v-text-field 
                  	prepend-icon="person" 
                  	v-model="email"
						        label="E-mail"
						        v-on:keyup.enter="authenticate"
						        :error-messages="$t(errors.collect('email'))"
						        v-validate="'required|email'"
						        data-vv-name="email"
						        required
                  ></v-text-field>
                  <v-text-field 
                  	prepend-icon="lock" 
                  	name="password" 
                  	:label="$t('Password')" 
                  	v-on:keyup.enter="authenticate"
                  	id="password" 
                  	type="password"
                  	min="3"
                  	v-model="password"
                  	:append-icon="e2 ? 'visibility' : 'visibility_off'"
                  	:append-icon-cb="()=>{e2 = !e2}"
		                :type="e2 ? 'text' : 'password'"
		                :error-messages="$t(errors.collect('password'))"
		            		data-vv-name="password"
		            		v-validate="'required'"
		            		required
                  ></v-text-field>
                  <!-- <v-checkbox
                  	color="red accent-4"
		            		v-model="remember_me"
		            		:value="true"
		            		label="Remember me"
		            	>
		            	</v-checkbox> -->
                </v-form>
              </v-card-text>
              <v-card-actions class="text-xs-center">
              	<v-flex class="text-xs-center">
	                <v-spacer></v-spacer>
	                <v-btn 
	                	color="red accent-4"
	                	dark
	                	outline
	                	@keyup="authenticate"
	                	@click="authenticate"
	                	:disabled="loading"
	                	:loading="loading"
	                >
	                	<v-icon left>lock_open</v-icon>
	                	{{$t('Login')}}
	                </v-btn>
	                <br><br>
		                <!-- <blockquote>
					            Authenticate all across the applications.          	
				            </blockquote> -->
                </v-flex>

              </v-card-actions>
            </v-card>
				</v-flex>

			</v-layout>
		</v-container>
	</div>
</template>
<script>
	import {mapState} from 'vuex'
	export default{
		$_veeValidate: {
      validator: 'new'
    },
		data:() => ({
				e2: false,
				email:'',
				password: '',
				remember_me: false,
				dictionary: {
					attributes: {
						email: '',
						password : 'Password'
					},
					custom: {
						email:{
							required: () => (localStorage.getItem('flang') ==='fr' ? 'Le nom ne peut pas être vide' : 'Name cannot be empty'),
							max: (localStorage.getItem('flang') ==='fr' ? 'Il devrait être plus grand que' : 'It should be greater than')
						},
						password:{
							required:()=>'Required'
						}
					}
				},
				login_alert: false,
				loading: false
		}),
		computed:{
			...mapState({
				UserDetails: state => state.Authentication.currentUserInfo
			})
		},
		methods:{
			authenticate(){
				this.login_alert = false
				this.$validator.validateAll().then((result)=>{
					if(result){
						const self = this;
						const authUser = {}
						try{
							const data = {
								username: this.email,
								password: this.password,
								remember: this.remember_me,
								client_id: '2',
								client_secret: 'Byf8kULcpiLgqSexYj0HYcp46CTYD0wtI98iT8MJ',
								grant_type : 'password',
								scope : ''
							}
							this.$store.dispatch('AUTH_REQUEST',data)
								.then(response=>{
									authUser.access_token = response.access_token
									authUser.refresh_token = response.refresh_token
									authUser.expires_in = response.expires_in
									window.localStorage.setItem('ktool_token',JSON.stringify(authUser))

									/*LOGIN*/
									this.login_alert = false
									this.loading = false

									window.location.reload()
								})
								.catch(error=>{
									this.login_alert = true
									window.localStorage.removeItem('ktool_token')
									this.loading = false
								})
						}catch(err){
							console.log(err);
						}
					}
				})
			
			}
		},
		mounted(){
			this.$validator.localize('en', this.dictionary)
		}
	}
</script>
<style>
	
</style>