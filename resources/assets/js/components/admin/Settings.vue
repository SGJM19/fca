<template>
	<div id="settings">
		<v-container grid-list-md>
			<v-layout row wrap>
				<v-dialog v-model="v_edit_arl" max-width="600">
		      <v-card>
		        <v-card-title class="subheading">Allow 
		        <v-chip label small color="red accent-4 white--text">
		        	{{full_name}} 
		      	</v-chip>
		      	of the following months.</v-card-title>
		        <v-divider></v-divider>
		        <v-card-text>

		        </v-card-text>
		        <v-divider></v-divider>
		        <v-card-actions>
		          <v-spacer></v-spacer>
		         
		          <v-btn color="red accent-4" flat="flat" outline @click.native="v_edit_arl = false;">Close</v-btn>
		        </v-card-actions>
		      </v-card>
		    </v-dialog>
				<v-flex xs12 sm8 sm8>
					<v-card class="mt-0">
						<v-card-title class="subheading">{{$t('Upon ARL submission of reports, notify these users via email.')}}</v-card-title>
						<v-divider></v-divider>
						<v-layout row wrap>
							<v-flex xs12 sm4 sm4 offset-sm8>
								<v-text-field
									v-model="search_arl"
									:label="$t('Search user')+'...'"
								>
								</v-text-field>		
							</v-flex>
						</v-layout>
						
						<v-data-table
							:headers="headers"
							:items="items_arls"
							:no-data-text="$t('No data available')"
							:rows-per-page-text="$t('Rows per page')"
							:search="search_arl"
						>
							<template slot="items" slot-scope="props">
								<tr>
									<td>{{props.item.name}} {{props.item.last_name}} - ({{props.item.roles[0].name}})</td>
									<td class="text-xs-left">
										{{props.item.email}}
									</td>
									<td class="text-xs-right">
										<v-flex style="float:right;">
											<!-- v-on:change="changeAllowThisToSend(props.item.id)" -->
											<v-switch
												v-model="allowThisTosendemail"
												right
												hide-details
												color="red accent-4"
												:value="props.item.id"
												v-on:change="ifThischecked(props.item.id, $event, props.index)"
											>
											</v-switch>
										</v-flex>


									</td>
								</tr>

							</template>
							
						</v-data-table>
					</v-card>
					<v-card class="mt-3">
						<v-layout row wrap>
							<v-flex xs12 sm4 sm4 class="ml-2">
								<v-select
									:items="item_year"
									v-model="v_year"
									v-on:change="oc_year"
									:label="$t('Select year')+'. .'"
									single-line
									prepend-icon="date_range"
									hint="Select year"
									persistent-hint
								>
								</v-select>
							</v-flex>
						</v-layout>
						<v-layout row wrap>
						  <v-flex xs12 sm6 sm6 style="border-right:1px solid #E0E0E0;">
						    <v-card-title primary-title>
						    	OFI
						    </v-card-title>
						    <v-divider></v-divider>
						    <v-card-text>
						    	<template v-for="(item,i) in ofi_item">
						    		<v-layout row wrap>
						    			<v-flex xs12 sm3 sm3 class="text-xs-right mt-4" >
						    				<div style="">
							    		  {{item.text}} : 
							    		  </div>
							    		</v-flex>
							    		<v-flex xs12 sm9 sm9>
							    			<v-text-field
								    		  name="name"
								    		  label="Amount of days to be extended"
								    		  type="number"
								    		  prepend-icon=""
								    		  id="id"
								    		  v-model="item.value"
								    		></v-text-field>
							    		</v-flex>  
						    		</v-layout>
						    	</template>
						    </v-card-text>
						  </v-flex>
						  <v-flex xs12 sm6 sm6>
						    <v-card-title primary-title>
						      HFI
						    </v-card-title>
						    <v-divider></v-divider>
						    <v-card-text>
						      <template v-for="(item,i) in hfi_item">
						    		<v-layout row wrap>
						    			<v-flex xs12 sm3 sm3 class="text-xs-right mt-4" >
						    				<div style="">
							    		  {{item.text}} : 
							    		  </div>
							    		</v-flex>
							    		<v-flex xs12 sm9 sm9>
							    			<v-text-field
								    		  name="name"
								    		  label="Amount of days to be extended"
								    		  type="number"
								    		  prepend-icon=""
								    		  id="id"
								    		  v-model="item.value"
								    		></v-text-field>
							    		</v-flex>  
						    		</v-layout>
						    	</template>
						    </v-card-text>
						  </v-flex>
						</v-layout>
						<v-divider></v-divider>
						<v-card-actions>
						 <v-flex class="text-xs-center">
						   <v-btn color="primary" @click="saveAccessSettings"><v-icon>save</v-icon>&nbsp; Save changes</v-btn>
						 </v-flex>
						</v-card-actions>
					</v-card>
				</v-flex>
				<v-flex xs12 sm4 sm4>
					<v-card>
						<v-alert :color="alert_exp_error" v-model="alert_exp_date" dismissible>
							{{alert_exp_msg}}
						</v-alert>
						<v-card-title>
							{{$t('Default Expiration date')}} 
							<v-spacer></v-spacer>
							<v-tooltip bottom>
								<span>{{$t('This means that the uploaded report won\'t be allowed to be edited anymore upon this set expiration date.')}}</span>
								<v-icon style="cursor:pointer;" slot="activator">help</v-icon>
							</v-tooltip>
						</v-card-title>
						<v-divider></v-divider>
						<v-card-text>
							<v-text-field
								v-model="exp_days"
								:label="$t('Enter days')"
								append-icon="close"
								clearable
								prepend-icon="event"
							>
							</v-text-field>
						</v-card-text>
						<v-divider></v-divider>
						<v-card-actions>
							<v-flex class="text-xs-center">
								<v-tooltip top>
									<span>{{$t('Save Changes')}}</span>
									<v-btn slot="activator" @click="saveExpiration" small flat outline color="red accent-4"><v-icon>save</v-icon> {{$t('Save Changes') | subString(18,'...')}} </v-btn>
								</v-tooltip>
							</v-flex>
						</v-card-actions>
					</v-card>
					<br>
					<v-card>
						<v-alert :color="alert_thold_color" v-model="alert_thold" dismissible>
							{{alert_thold_msg}}
						</v-alert>
						<v-card-title class="">
						 	{{$t('Threshold of percentage')}} 
							<v-spacer></v-spacer>
							<v-tooltip bottom>
								<span>{{$t('They will required a second audit if score not passing to this setting. This will affect globally.')}}</span>
								<v-icon style="cursor:pointer;" slot="activator">help</v-icon>
							</v-tooltip>
						</v-card-title>
						<v-divider></v-divider>
						<v-card-text>
							<v-text-field 
								v-model="v_txt_percentage"
								:label="$t('Enter percent')"
								prepend-icon="%"
								:hint="$t('This is only allow')+'0 - 100'"
								persistent-hint
								clearable
								append-icon="close"
								single-line
								v-on:keypress="isNumber"
							>
								
							</v-text-field>
						</v-card-text>
						<v-divider></v-divider>
						<v-card-actions>
							<v-flex class="text-xs-center">
								<v-tooltip top>
									<span>{{$t('Save Changes')}}</span>
									<v-btn color="red accent-4" slot="activator" small @click="saveThresholdPercent" outline><v-icon>save</v-icon>{{$t('Save Changes') | subString(18,'...') }}</v-btn>
								</v-tooltip>
							</v-flex>
						</v-card-actions>
						
					</v-card>
				</v-flex>
			</v-layout>
		</v-container>
	</div>
</template>
<script>
	import _ from 'lodash';
	export default{
		data:()=>({
			item_year: [],
			v_year: null,
			headers:[
				{
					text: 'ARL '+ (localStorage.getItem('flang') === 'fr' ? 'nom' : 'name'),
					align: 'left',
					sortable: true,
					value: 'name'
				},
				{
					text: 'Email',
					align: 'left',
					sortable: true,
					value: 'email'
				},
				{
					text: (localStorage.getItem('flang') === 'fr' ? 'Paramètres' : 'Settings'), 
					align: 'right',
					sortable: true,
					value: 'arl_name'
				}
			],
			items_arls:[],
			pagination:{
			},
			v_edit_arl: false,
			search_arl: null,
			user_id: null,
			full_name: '',
			jan: null,
			feb: null,
			mar: null,
			apr: null,
			may: null,
			jun: null,
			jul: null,
			aug: null,
			sep: null,
			oct: null,
			nov: null,
			dec: null,
			v_switchAll: null,
			exp_date: null,
			modal: false,
			alert_exp_date: false,
			alert_exp_msg: 'Please select date time',
			exp_days: '',
			alert_exp_error: 'error',
			v_txt_percentage: null,
			alert_thold_color: 'error',
			alert_thold: false,
			alert_thold_msg: 'Please provide details below.',
			allowThisTosendemail: [],
			ofi_item:[
				{id:'0', value: '', text:'January'},
				{id:'1', value: '', text :'February'},
				{id:'2', value: '', text:'March'},
				{id:'3', value: '', text: 'April'},
				{id:'4', value: '', text: 'May'},
				{id:'5', value: '', text: 'June'},
				{id:'6', value: '', text: 'July'},
				{id:'7', value: '', text:'August'},
				{id:'8', value: '', text:'September'},
				{id:'9', value: '', text:'October'},
				{id:'10', value: '', text:'November'},
				{id:'11', value: '', text :'December'},
			],
			hfi_item:[
				{id:'0', value: '',text:'January'},
				{id:'1', value: '', text :'February'},
				{id:'2', value: '', text:'March'},
				{id:'3', value: '', text: 'April'},
				{id:'4', value: '', text: 'May'},
				{id:'5', value: '', text: 'June'},
				{id:'6', value: '', text: 'July'},
				{id:'7', value: '', text:'August'},
				{id:'8', value: '', text:'September'},
				{id:'9', value: '', text:'October'},
				{id:'10', value: '', text:'November'},
				{id:'11', value: '', text :'December'},
			]
		}),
		computed:{
		},
		watch:{

		},
		methods:{
			oc_year:function(val){
				//console.log(val)
				this.getCurrentLimitAccess(val)
			},
			saveAccessSettings(){
				const ofi = this.ofi_item
				const hfi = this.hfi_item
				const obj = {
					url: '/ktool/api/saveAccessSettings',
					method:'POST',
					data : {
						ofi_item: ofi,
						hfi_item: hfi,
						year: this.v_year
					}
				}


				this.$store.dispatch('COMMIT_ACTION_NO_FILE',obj)
					.then(response=>{
						if(response.status === 'success'){
							alert('Successfully updated');
							return false;
						}
					})
					.catch(error=>{
						console.log(error)
					})
			},
			ifThischecked(val,event,index){
				let isRemove = 0;
				let check = Object.values(event);
				if(_.includes(check, val)){
					isRemove = 0;
				}else{
					isRemove = 1;
				}
				console.log(isRemove)

				const data = {
					user_id : val,
					isRemove: isRemove
				}

				this.$store.dispatch('STORE_EMAIL_TO',data)
					.then(response=>{

					})
					.catch(error=>{
						console.log(error);
					})
			},
			changeAllowThisToSend:function(val){
				console.log(val)
			},
			switchAll:function(data){
				if(this.v_switchAll == 1){
					this.jan = '1',
					this.feb = '2',
					this.mar = '3',
					this.apr = '4',
					this.may = '5',
					this.jun = '6',
					this.jul = '7',
					this.aug = '8',
					this.sep = '9',
					this.oct = '10',
					this.nov = '11',
					this.dec = '12'
				}else{
					this.jan = null,
					this.feb = null,
					this.mar = null,
					this.apr = null,
					this.may = null,
					this.jun = null,
					this.jul = null,
					this.aug = null,
					this.sep = null,
					this.oct = null,
					this.nov = null,
					this.dec = null
				}
			},
			predefData:function(data){
				this.full_name = data.name
				this.user_id = data.user_id
				const obj = {
					user_id: data.user_id 
				}
				this.jan = null,
				this.feb = null,
				this.mar = null,
				this.apr = null,
				this.may = null,
				this.jun = null,
				this.jul = null,
				this.aug = null,
				this.sep = null,
				this.oct = null,
				this.nov = null,
				this.dec = null

				const self = this
				this.$store.dispatch('GET_CURRENT_ALLOWED',obj)
					.then(response=>{
						response.data.map(function(item) {
							if(item == 1){
								self.jan = '1'
							}
							if(item == 2){
								self.feb = '2'
							}
							if(item == 3){
								self.mar = '3'
							}
							if(item == 4){
								self.apr = '4'
							}
							if(item == 5){
								self.may = '5'
							}
							if(item == 6){
								self.jun = '6'
							}
							if(item == 7){
								self.jul = '7'
							}
							if(item == 8){
								self.aug = '8'
							}
							if(item == 9){
								self.sep = '9'
							}
							if(item == 10){
								self.oct = '10'
							}
							if(item == 11){
								self.nov = '11'
							}
							if(item == 12){
								self.dec = '12'
							}
						})
					})	
					.catch(error=>{
						console.log(error);
					})
			},
			setMonth(data){
				const obj = {
					month: data.month,
					user_id : this.user_id,
					val : data.model
				}
				this.$store.dispatch('SET_MONTH_SETTINGS',obj)
					.then(response=>{
						console.log(response)
					})
					.catch(error=>{

					});
			},
			saveExpiration(){
				if(this.exp_days === ''){
					this.alert_exp_date = true
					return false;
				}
				const data = {
					days: this.exp_days
				}

				this.$store.dispatch('STORE_EXP_DATE',data)
					.then(response=>{
						console.log(response);	
					})
					.catch(error=>{
						console.log(error);
					})
			},
			isNumber: function(evt) {
	      evt = (evt) ? evt : window.event;
	      var charCode = (evt.which) ? evt.which : evt.keyCode;
	      if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
	        evt.preventDefault();;
	      } else {
	        return true;
	      }
	    },
	    saveThresholdPercent:function(){
	    	this.alert_thold_color
				this.alert_thold = false
				this.alert_thold_msg

				if(!this.v_txt_percentage){
					this.alert_thold = true;
					this.alert_thold_msg = 'Please provide details below.'
					this.alert_thold_color = 'error'
					return false;
				}else if (this.v_txt_percentage < 0 || this.v_txt_percentage > 100){
					this.alert_thold = true;
					this.alert_thold_msg = 'Cannot be less than 0 and greater than 100'
					this.alert_thold_color = 'error'
					return false;
				}

	    	const data = {
	    		percentage : this.v_txt_percentage
	    	}

	    	this.$store.dispatch('STORE_THRESHOLD_PERCENT', data)
	    		.then(response=>{
	    			if(response.data === 'success'){
	    				this.alert_thold = true;
	    				this.alert_thold_msg = 'Success'
	    				this.alert_thold_color = 'success'
	    			}
	    			setTimeout(()=>{
	    				this.alert_thold = false;
	    			},5000)
	    		})
	    		.catch(error=>{
	    			console.log(error)
	    		})
	    },
	    getCurrentLimitAccess(year = null){
	    	let set_year;
	    	if(!year){
	    		set_year = (new Date()).getFullYear()	
	    	}else{
	    		set_year = year	
	    	}
	    	const data = {
					year: set_year
				}
				
				this.$store.dispatch('GET_EXP_DATE',data)
					.then(response=>{
						this.exp_days = response.data
						//console.log(response.hfi_item)

						if(response.hfi_item.length <= 0){
							this.hfi_item = [
																{id:'0', value: '',text:'January'},
																{id:'1', value: '', text :'February'},
																{id:'2', value: '', text:'March'},
																{id:'3', value: '', text: 'April'},
																{id:'4', value: '', text: 'May'},
																{id:'5', value: '', text: 'June'},
																{id:'6', value: '', text: 'July'},
																{id:'7', value: '', text:'August'},
																{id:'8', value: '', text:'September'},
																{id:'9', value: '', text:'October'},
																{id:'10', value: '', text:'November'},
																{id:'11', value: '', text :'December'},
															]
						}
						if(response.ofi_item <= 0){
							this.ofi_item = [
									{id:'0', value: '', text:'January'},
									{id:'1', value: '', text :'February'},
									{id:'2', value: '', text:'March'},
									{id:'3', value: '', text: 'April'},
									{id:'4', value: '', text: 'May'},
									{id:'5', value: '', text: 'June'},
									{id:'6', value: '', text: 'July'},
									{id:'7', value: '', text:'August'},
									{id:'8', value: '', text:'September'},
									{id:'9', value: '', text:'October'},
									{id:'10', value: '', text:'November'},
									{id:'11', value: '', text :'December'},
								];
						}
						const arr_obj = response.hfi_item
						for (var key in arr_obj) {
							this.hfi_item[key].value = arr_obj[key].value
						}	

						const arr_obj_ofi = response.ofi_item
						for (var key in arr_obj_ofi) {
							this.ofi_item[key].value = arr_obj_ofi[key].value
						}	

						this.v_txt_percentage = response.percentage
					})
					.catch(error=>{
						console.log(error);
					})
	    }
		},
		filters:{
			subString(text,length,clamp){
				clamp = clamp || '...';
		    var node = document.createElement('div');
		    node.innerHTML = text;
		    var content = node.textContent;
		    return content.length > length ? content.slice(0, length) + clamp : content;
			}
		},
		mounted(){
			this.v_year = (new Date()).getFullYear()
			var years = [];
      var currentYear = new Date().getFullYear(), years = [];
      var startYear = startYear || 2010;

      while ( startYear <= currentYear ) {
              years.push(startYear++);
      } 
      this.item_year = years.reverse()
		},
		created(){
			const self = this
			this.$store.dispatch('GET_USERS')
				.then(response=>{
					this.items_arls = response.data
					self.allowThisTosendemail = response.data_upload
					//console.log(this.allowThisTosendemail);
				})
				.catch(error=>{
					console.log(error);
				})

			//this.getCurrentLimitAccess();
			

			


		}
	}
</script>
<style scoped>
	div>>>.chip__content{
		cursor: pointer;
	}
</style>