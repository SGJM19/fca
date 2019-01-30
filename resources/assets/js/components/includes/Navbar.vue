<template>
	<div id="navbar">
		<v-navigation-drawer
    	v-model="sideNav.model"
    	absolute
    	overflow
    	disable-resize-watcher
    	disable-route-watcher
    	left
    	temporary
    	:app="sideNav.type = ''"
      class="red lighten-3"
    >
      <v-toolbar flat color="red accent-4">
        <v-list>
          <v-list-tile router to="/prod">
            <v-list-tile-avatar>
              <img src="/prod/storage/app/public/images/logo.png" height="20px" width="20px" style="margin-right:10px;">
            </v-list-tile-avatar >
            <v-list-tile-content style="color:white;">
            	FCA
            </v-list-tile-content>
          </v-list-tile>
          <v-list-group >
            <v-list-tile slot="activator" >
              <v-list-tile-content>
                <v-list-tile-title>
         
                </v-list-tile-title>
              </v-list-tile-content>
            </v-list-tile>

              <v-list-tile  router :to="'/prod/admin'" @click=""> <!-- v-for="item in items" :key="item.title" -->
                <v-list-tile-action>
                  <v-icon>dashboard</v-icon>
                </v-list-tile-action>
                <v-list-tile-content>
                
                </v-list-tile-content>
              </v-list-tile>
              <v-list-tile router :to="'/prod/profile'"> <!-- v-for="item in items" :key="item.title" -->
                <v-list-tile-action>
                  <v-icon>people</v-icon>
                </v-list-tile-action>
                <v-list-tile-content></v-list-tile-content>
              </v-list-tile>
              <v-list-tile @click=""> <!-- v-for="item in items" :key="item.title" -->
                <v-list-tile-action>
                  <v-icon>exit_to_app</v-icon>
                </v-list-tile-action>
                <v-list-tile-content>Logout</v-list-tile-content>
              </v-list-tile>
          </v-list-group>
        </v-list>
      </v-toolbar>
      <v-divider></v-divider>
      <v-list dense class="pt-0">
        <v-list-tile @click="" >
          <v-list-tile-action>
            <v-icon>lock_outline</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>

          </v-list-tile-content>
        </v-list-tile>

        
      </v-list>
    </v-navigation-drawer>

		 <v-toolbar 
			 	dark 
			 	color="red accent-4" 
			 	extended 
			 	dense
			 	flat
			>
	    <v-toolbar-side-icon @click.stop="sideNav.model = !sideNav.model" class="hidden-sm-and-up"></v-toolbar-side-icon>
	    <v-toolbar-title>
	    	<v-btn 
	    		flat 
	    		outline 
	    		router
	    		:to="{name: 'Home'}"
	    	>
	    		Fca
	    	</v-btn>
	    </v-toolbar-title>

	    <v-toolbar-title class="white--text" style="width:100%;" slot="extension">
	    	<v-flex  class="text-xs-center">
          <div v-if="$route.name === 'Fca'">
            [FCA] {{$t('Financial Control Audit')}} - {{$t('Upload')}} <v-icon>cloud_upload</v-icon>
          </div>
          <div v-if="$route.name === 'exempt_store'">
            [FCA] - Exempt Certain Store
          </div>
          <div v-if="$route.name === 'fca_dashboard'">
            [FCA] {{$t('Financial Control Audit')}}  - {{$t('Admin')}} 
          </div>
          <div v-if="$route.name === 'fca_settings'">
            [FCA] {{$t('Financial Control Audit')}} - {{$t('Settings')}}
          </div>
	    	</v-flex>
	   	</v-toolbar-title>
	    <v-spacer></v-spacer>

	    <v-btn flat v-if="!isAuth" router :to="{name: 'Login'}">
	    	<v-icon left dark>lock_open</v-icon>
	    	{{$t('Login')}}
	    </v-btn>
	    <!-- dropdown -->
	    <v-menu :nudge-width="60" :nudge-left="80" :nudge-bottom="40" v-if="isAuth"> <!-- :nudge-width="100" -->
        <v-btn flat slot="activator" v-cloak>
          <v-avatar size="36px" class="blue lighten-2">
            <!-- <img :src="imageURL !== '' ? imageURL : ImageProfile" v-if="imageURL !== '' || ImageProfile !== ''"> -->
            <span class="white--text">{{name.slice(0,1).toUpperCase()}} </span>
          </v-avatar> 
          <div style="margin-left:10px;">
           {{ name }} <v-icon dark>arrow_drop_down</v-icon> <!-- UserStore.authUser.name -->
          </div>
        </v-btn>

        <v-list>
        	<!--  v-if="_.intersection(Role, ['admin', 'owner']).length > 0" -->
          <v-list-tile router v-if="checkRole(Role)" :to="{name:'fca_settings'}" v-cloak @click=""> <!-- v-for="item in items" :key="item.title" -->
            <v-list-tile-action>
              <v-icon>settings</v-icon>
            </v-list-tile-action>
            <v-list-tile-content>{{$t('Settings')}}</v-list-tile-content>
          </v-list-tile>

          <v-list-tile router v-if="checkRole(Role)" :to="{name:'exempt_store'}" @click=""> <!-- v-for="item in items" :key="item.title" -->
            <v-list-tile-action>
              <v-icon>dashboard</v-icon>
            </v-list-tile-action>
            <v-list-tile-content>{{$t('Exempt Store')}}</v-list-tile-content>
          </v-list-tile>

          <v-list-tile router v-if="checkRole(Role)" :to="{name:'fca_dashboard'}" @click=""> <!-- v-for="item in items" :key="item.title" -->
            <v-list-tile-action>
              <v-icon>dashboard</v-icon>
            </v-list-tile-action>
            <v-list-tile-content>{{$t('FCA Dashboard')}}</v-list-tile-content>
          </v-list-tile>

          <v-list-tile router :to="{name:'Fca'}" @click=""> <!-- v-for="item in items" :key="item.title" -->
            <v-list-tile-action>
              <v-icon>cloud_upload</v-icon>
            </v-list-tile-action>
            <v-list-tile-content>{{$t('FCA Upload')}}</v-list-tile-content>
          </v-list-tile>

          <v-list-tile @click="handleLogout()"> <!-- v-for="item in items" :key="item.title" -->
            <v-list-tile-action>
              <v-icon>exit_to_app</v-icon>
            </v-list-tile-action>
            <v-list-tile-content>{{$t('Logout')}} </v-list-tile-content>
          </v-list-tile>
        </v-list>
      </v-menu>
      <v-menu offset-y>
        <v-btn flat slot="activator" icon dark>
          <v-icon>language</v-icon>
        </v-btn>
        <v-list>
          <v-list-tile @click="language('en')">
            <v-list-tile-action>
              EN
            </v-list-tile-action>
            <v-list-tile-content>
              Hi-Flyer
            </v-list-tile-content>
          </v-list-tile>
          <v-list-tile @click="language('fr')">
            <v-list-tile-action>
              FR
            </v-list-tile-action>
            <v-list-tile-content>
              Olympus
            </v-list-tile-content>
          </v-list-tile>
        </v-list>
      </v-menu>
	    
	  </v-toolbar>
	</div>
</template>
<script>
	import {mapState} from 'vuex'
	import _ from 'lodash'
	export default{
		data(){
			return{
				sideNav:{
					model:false,
					type:'temporary',
					clipped: false,
					floating: false,
					mini: false,
					stateless: true
				},
				isMobile: false
			}
		},
		computed:{
			...mapState({
				name: state => state.Authentication.currentUserInfo.name,
				Role: state=> state.Authentication.currentUserInfo.role,
				Permission: state => state.Authentication.currentUserInfo.permission
			}),
			isAuth:function(){
				return this.$store.state.Authentication.isLoggedInProofed
			}
		},
		methods:{
			handleLogout:function(){
        this.$store.commit('CLEAR_AUTH')
        this.$router.push('/ktool');
			},
      checkRole(Roles){
        if(_.intersection(Roles, ['admin','director','owner']).length > 0){
          return true;
        }else{
          return false;
        }
      },
      language($lang){
        localStorage.setItem('flang',$lang);
        window.location.reload();
      }
		},
		created(){

		},
		mounted(){

		}
	}
</script>