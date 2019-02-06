<template>	
	<div id="fca">
		<v-container grid-list-md  fluid >
			<v-layout row wrap>
				<v-flex xs12 sm9 sm9>
					<v-card width="100%">
						<v-layout row wrap class="pl-3 pr-3">
							<v-flex xs12 sm3 sm3 left>
								<v-select
									:items="years"
									v-model="getYears"
									item-value="year"
									item-text="year"
									prepend-icon="date_range"
									:label="$t('Select Year')"
									single-line
									v-on:change="oc_year"
									:hint="$t('Select year')"
									persistent-hint
								>
								</v-select>
							</v-flex>
							<v-flex xs12 sm3 sm3  class="text-xs-left">
								<v-text-field
									v-model="search"
									:label="$t('Search')"
									append-icon="search"
									single-line
									hide-details
								>
							</v-text-field>
							</v-flex>
							<v-flex xs12 sm2 sm2 offset-sm4 class="text-xs-right">
								<v-tooltip top>
									<span>{{$t('Export to excel')}}</span>
									<v-btn @click="exportFca()" :loading="loadExport" slot="activator" color="red accent-4" outline><i class="fa fa-file-excel-o"></i> {{$t('Export')}}</v-btn>
								</v-tooltip>
							</v-flex>
							
							
						
							<!--dialog -->
							<v-dialog id="dialogContainer" ref="dialogContainer" persistent v-model="fca_dialog" max-width="700">
					      <v-card>
					        <v-card-title class="subheading pt-0 pb-0">
					        	 {{$t('Store')}}: <code>{{store_name}}</code> &nbsp; | {{$t('Year')}} : {{year}} | {{$t('Month of')}} : {{month}}
 					        	<v-spacer></v-spacer>

					        	<div class="caption" v-if="current_expiration !== null && disabled_field === false" text-xs-center>
					        		{{$t('You are allowed to edit until')}} : <code>{{current_expiration}}</code>
					        	</div>
					        	<div v-else-if="!v_ratings" >
					        		
					        	</div>
					        	<div v-else style="color:blue;">
					        		
					        	</div>

					        	<v-btn color="blue white--text" :loading="loadBtn" fab @click="fca_dialog=false" small >x</v-btn>
					        	
					       	</v-card-title>
					        <v-divider></v-divider>
					        <v-card-text>
					        	<v-layout row wrap>
				        			<v-flex xs12 sm5 sm5 pr-2>
				        				<v-text-field
								          name="input-1-3"
								          :label="$t('Ratings')"
								          single-line
								          prepend-icon="%"
								          clearable
								          :hint="$t('Enter ratings') + ' 1 - 100 ' + '<br><span style=\'color:red;\'>('+$t('Required')+') </span>'"
								          v-model="v_ratings"
								          :disabled="disabled_field"
								          persistent-hint
								          v-on:keypress="isNumber()"
								          v-on:keyup="v_ratings = sp_keyup(v_ratings, 0, 100)"
								          v-on:change="handleChange(v_ratings)"
								        ></v-text-field>
								        <v-card height="150" hover style="overflow-y: auto;">
								        	<v-card-title><v-icon>equalizer</v-icon>{{$t('Previous Ratings')}}</v-card-title>
								        	<v-divider></v-divider>
								        		<v-list v-if="prev_score.length > 0">
								        			<template v-for="(item, i) in prev_score">
								        				<v-list-tile
								        					:key="item.id"
								        					ripple
								        					@click=""
								        				>
								        				<v-list-tile-content>
								        					<v-list-tile-title>% {{item.percentage}}</v-list-tile-title>
								        					<v-list-tile-sub-title>{{item.created_at}}</v-list-tile-sub-title>
								        				</v-list-tile-content>
								        				<v-list-tile-action class="pa-0 ma-0">
								        					<v-list-tile-action-text v-if="item.audit_by_name">{{item.audit_by_name.charAt(0).toUpperCase() + item.audit_by_name.substr(1)}} </v-list-tile-action-text>
								        					<v-list-tile-action-text v-else>N/A</v-list-tile-action-text>
                									<v-list-tile-action-text></v-list-tile-action-text>
								        				</v-list-tile-action>
								        				</v-list-tile>
								        				<v-divider></v-divider>

								        			</template>
								        		</v-list>
								        		<div v-else>
								        			<v-flex text-xs-center>
								        				No previous record.
								        			</v-flex>
								        		</div> 
								        </v-card>

				        			</v-flex>
				        			<v-flex xs12 sm7 sm7 pl-2>
				        				<v-text-field
					                name="input-1"
					                :label="$t('Remarks')"
					               	v-model="v_remarks"
					                :hint="'<span style=\'color:blue\'>('+$t('Optional')+')</span>'"
					                persistent-hint
					                :disabled="disabled_field"
					                textarea
					                row-height="16"
					                v-on:keypress="changeRemarks"
					              ></v-text-field>
				        			</v-flex>

			        			</v-layout>

			        			<v-layout row wrap>
			        				<v-flex xs12 sm6 sm6>
				        				<v-dialog
									        ref="dialog"
									        persistent
									        v-model="v_audit_date"
									        lazy
									        full-width
									        width="290px"
									        :return-value.sync="audt_date"
									      >
									        <v-text-field
									          slot="activator"
									          :label="$t('Audit Date')"
									          single-line
									          clearable
									          :hint="$t('Audit Date') + '<span style=\'color:red;\'>('+$t('Required')+')</span>'"
									          persistent-hint
									          v-model="audt_date"
									          :disabled="disabled_field"
									          prepend-icon="event"
									          readonly
									          style="cursor: pointer;"
						
									        ></v-text-field>
									        <v-date-picker :readonly="disabled_field" v-model="audt_date" scrollable>
									          <v-spacer></v-spacer>
									          <v-btn flat color="primary" @click="v_audit_date = false">Cancel</v-btn>
									          <v-btn flat color="primary" @click="$refs.dialog.save(audt_date); hasChangedDate()">{{$t('Ok')}}</v-btn>
									        </v-date-picker>
									      </v-dialog>
								      </v-flex>
								      <v-flex xs12 sm6 sm6 pl-2>
								      	<v-text-field
								      		:label="$t('Audit by')"
								      		v-model="audit_by"
								      		clearable
								      		:disabled="disabled_field"
								      		:hint="$t('Enter the name')+'<span style=\'color:red;\'>('+$t('Required')+')</span>'"
								      		v-on:keypress="changeAuditBy"
								      		persistent-hint
								      	>
								      	</v-text-field>
								      </v-flex>
			        			</v-layout>
					        </v-card-text>

					        <v-card-actions>
					        	
					        </v-card-actions>
					       	<v-divider></v-divider>
					       	<v-flex>
					       		<v-alert :color="fca_alert_color" dismissible v-model="fca_alert">
					      		{{$t(fca_alert_msg)}}
					      	</v-alert>
					       	</v-flex>
					        <v-card-text>
					        	<span v-if="disabled_field === false">
					        	{{$t('IMPORTANT')}}: {{$t('This report will be not valid without a file.')}}
					        	</span>	
					        	<!-- v-on:vdropzone-success="onSuccessUpload" -->
					        	<vue-dropzone 
					        		v-if="disabled_field === false"
					        		style="width:650px;" 
					        		ref="myVueDropzone" 
					        		id="dropzone" 
					        		:options="dropzoneOptions"
					        		v-on:vdropzone-removed-file="removeThisFile"
					        		v-on:vdropzone-success-multiple="onUploadCompleted"
					        		v-on:vdropzone-file-added="onSuccessAdded"
					        	></vue-dropzone>

					        	<v-flex class="text-xs-center">

					        		<div v-if="disabled_field === false">
					          		<v-btn color="red accent-4" outline flat="flat" :disabled="disabledBtnUntil" @click="saveOtherDetails" :loading="loadBtn"><v-icon left>save</v-icon>{{$t('Save Changes')}} 
					          		</v-btn>
					          	</div>
					        	</v-flex>

					        	<v-data-table
					        		:items="item_files"
					        		:headers="header_files"
					        		must-sort
					        		:disable-intitial-sort="true"
					        		:no-data-text="$t('No data available')"
					        		:rows-per-page-text="$t('Rows per page')"
					        		:loading="item_files_loading"
					        	>
					        		<template slot="items" slot-scope="props">
					        			<tr>
					        				<td>{{props.item.original_file_name}}</td>
					        				<td>{{props.item.size}}KB</td>
					        				<td>{{props.item.ext}}</td>
					        				 <td class="justify-center layout px-0">

					        				 <v-tooltip left v-if="disabled_field === false">
					        				 	<span>{{$t('Delete this file.')}}.</span>
									          <v-btn slot="activator" icon class="mx-0" @click="deleteFile(props.item.id, props.item)">
									            <v-icon color="pink">delete</v-icon>
									          </v-btn>
									         </v-tooltip>

									        	<v-tooltip left>
					        						<span>{{$t('Download this file.')}}</span>
										         	<v-btn slot="activator" icon class="mx-0" @click="downloadFile(props.item.id, props.item)">
										            <v-icon color="pink">file_download</v-icon>
										          </v-btn>
									        	</v-tooltip>
									        </td>
					        			</tr>
					        		</template>
					        	</v-data-table>
					        </v-card-text>
					        <v-card-actions>
					     
					        </v-card-actions>
					        
					      </v-card>
					    </v-dialog>


						</v-layout>
						<v-data-table
							:items="items"
							:headers="headers"
							:search="search"
							:loading="loading"
							:rows-per-page-text="$t('Rows per page')"
							:no-data-text="$t('No data available')"
							must-sort
							style="max-height:600px; overflow-y: auto;"
						>
							<template slot="items" slot-scope="props">
								<tr>
									<td>{{props.item.store_id}}

									</td>
									<!-- January -->
									<td class="text-xs-center">
										<div v-if="Date.parse(props.item.exp_Jan) <= Date.parse(new Date().toLocaleString()) || (Date.parse(props.item.exp_Jan) <= Date.parse(new Date().toLocaleString()) && props.item.Jan_ispassed == 1)">
											<v-tooltip right>
												<div>{{$t('Click to view details.')}}</div>
												<v-chip slot="activator" label small color="green white--text" @click="storeAdditionalParam({
						          			store_id : props.item.store_id_real,
						          			year : props.item.year,
						          			store : props.item.store_id,
						          			month : 'Jan',
						          			fca_id : props.item.Jan_id, //fca_id in main table
						          			fca_exp_date : props.item.exp_Jan, // expiration date
						          			ratings: props.item.Jan_percent,
						          			remarks: props.item.Jan_remarks,
						          			audit_date: props.item.Jan_ad,
						          			audit_by: props.item.Jan_ad_by,
						          			isClosed: 1,
						          			isPassed: props.item.Jan_ispassed,
						          			isReeval: props.item.Jan_reeval
						          		}); fca_dialog = true; " >
													{{props.item.Jan_percent}} %
												</v-chip>
											</v-tooltip>
										</div>
										<div v-else-if="props.item.Jan === ''">
											<v-tooltip right v-if="
														checkExempted(props.item.store_id_real,0,'Jan') ? false : 
															(		0 == ((new Date().getMonth())) || (props.item.company_code == 1 ? 
															(  (daysIntoYear(new Date('2018,01,31')) + parseInt(hfi_items[0].value)) > getCurrentDayOfYear()  ) :
															(  (daysIntoYear(new Date('2018,01,31')) + parseInt(ofi_items[0].value)) > getCurrentDayOfYear()  )
														))">
												<div>{{$t('No file uploaded')}}.</div>
					          		<v-chip slot="activator" small @click="storeAdditionalParam({
					          			store_id : props.item.store_id_real,
					          			year : props.item.year,
					          			store : props.item.store_id,
					          			month : 'Jan',
					          			fca_id: '',
					          			fca_exp_date: null,
					          			ratings: props.item.Jan_percent,
					          			remarks: props.item.Jan_remarks,
					          			audit_date: props.item.Jan_ad,
					          			audit_by: props.item.Jan_ad_by,
					          			isPassed: props.item.Jan_ispassed,
						          		isReeval: props.item.Jan_reeval
					          		}); fca_dialog = true;" color="grey lighten-1 white--text" style="cursor:pointer;" label>
					          			<div>
						          			{{$t('Empty')}}
					          			</div>
					          		</v-chip>
					          		<span color="grey caption"></span>
				          		</v-tooltip>
				          		<div v-else>
												<v-tooltip right>
													<div>{{$t('You cannot upload here until end of this month.')}}</div>
													<v-chip v-if="!checkExempted(props.item.store_id_real,0,'Jan')" slot="activator" small label color="white--text">
														{{$t('Not available')}}
													</v-chip>
													<v-chip v-else slot="activator" small label color="yellow white--text">
														{{$t('Exempted')}}
													</v-chip>
												</v-tooltip>				          			
				          		</div>
				          	</div>
			          		<v-tooltip v-else right>
			          			<span v-if="Date.parse(props.item.exp_Jan) <= Date.parse(new Date().toLocaleString()) && props.item.Jan_ispassed == 1">
			          				{{$t('Click to view more details.')}}.
			          			</span>
			          			<span v-else>
			          				{{$t('Click to view more details.')}}.
			          				<span class="subheading red--text text--lighten-4" v-if="props.item.Jan_percent < percentage_threshold && props.item.Jan_ispassed == 0">
			          					<br>
			          					<v-icon color="red">warning</v-icon> {{$t('Ratings threshold should be greater than')}} {{percentage_threshold}} % <br>
			          				</span>
			   								
			   								<div v-if="props.item.Jan_percent > percentage_threshold && props.item.Jan_ispassed == 0">
			   									{{$t('This means, this is passsed but there\'s requirements that you still need to change.')}}
			   								</div>
			   								
			   								<div v-else>

			          				</div>

			          				<div >
				          				{{$t('Remaining time to edit')}}: <Countdown  :deadline="props.item.exp_Jan"></Countdown>
				          				{{$t('You will not be allowed to edit upon expiration of editting period (Unless its a request from admin)')}} <br>
			          				</div>
			          				
			          			</span>
				          		<v-chip slot="activator" @click="storeAdditionalParam({
					          			store_id : props.item.store_id_real,
					          			year : props.item.year,
					          			store : props.item.store_id,
					          			month : 'Jan',
					          			fca_id : props.item.Jan_id, //fca_id in main table
					          			fca_exp_date : props.item.exp_Jan, // expiration date
					          			ratings: props.item.Jan_percent,
					          			remarks: props.item.Jan_remarks,
					          			audit_date: props.item.Jan_ad,
					          			audit_by: props.item.Jan_ad_by,
					          			isPassed: props.item.Jan_ispassed,
						          		isReeval: props.item.Jan_reeval

					          		}); fca_dialog = true" label small  :color="(Date.parse(props.item.exp_Jan) <= Date.parse(new Date().toLocaleString()) && props.item.Jan_ispassed ==  1) || (props.item.Jan_percent < percentage_threshold && props.item.Jan_ispassed == 1) ? 'green accent-4 white--text' : 
				          				//else new condition
					          			props.item.Jan_percent < percentage_threshold ? 'red white--text' : 
					          			//else new condition
					          			props.item.Jan_percent > percentage_threshold && props.item.Jan_ispassed == 0 ? 'yellow darken-3 white--text' : 'blue accent-4 white--text'">
				          			{{props.item.Jan_percent}} %
				          		</v-chip>
			          		</v-tooltip>
			          		<v-progress-linear 
			          			v-if="props.item.Jan !== ''" 
			          			style="margin-top:0px; margin-bottom:0px;" 
			          			v-model="valueDeterminate = props.item.Jan_percent"
			          			:color="Date.parse(props.item.exp_Jan) <= Date.parse(new Date().toLocaleString()) || (Date.parse(props.item.exp_Jan) <= Date.parse(new Date().toLocaleString()) && props.item.Jan_ispassed ==  1) || (props.item.Jan_percent < percentage_threshold && props.item.Jan_ispassed == 1) ? 'green accent-4 white--text' : //else
			          				props.item.Jan_percent < percentage_threshold ? 'red white--text' : 
			          				//else ne wcondition 
			          				props.item.Jan_percent > percentage_threshold && props.item.Jan_ispassed == 0 ? 'yellow darken-3 white--text' : 'blue accent-4 white--text'
			          			"
			          		></v-progress-linear>
			          		<div v-if="props.item.Jan !== ''">
				          		<span style="color:red;" class="caption" v-if="!props.item.Jan_files">
				          			{{$t('No file')}}
				          		</span>
				          		<span v-else style="color:blue; font-size:10px;">
					          			{{$t('File(s) included')}}
					          		</span>
			          		</div>
									</td>
									<!-- end of January -->

									<!--STart of feb -->
									<td class="text-xs-center">
			          		<div v-if="Date.parse(props.item.exp_Feb) <= Date.parse(new Date().toLocaleString()) || (Date.parse(props.item.exp_Feb) <= Date.parse(new Date().toLocaleString()) && props.item.Feb_ispassed == 1) ">
											<v-tooltip right>
												<div>{{$t('Click to view details.')}}</div>
												<v-chip slot="activator" label small color="green white--text" @click="storeAdditionalParam({
						          			store_id : props.item.store_id_real,
						          			year : props.item.year,
						          			store : props.item.store_id,
						          			month : 'Feb',
						          			fca_id : props.item.Feb_id, //fca_id in main table
						          			fca_exp_date : props.item.exp_Feb, // expiration date
						          			ratings: props.item.Feb_percent,
						          			remarks: props.item.Feb_remarks,
						          			audit_date: props.item.Feb_ad,
						          			audit_by: props.item.Feb_ad_by,
						          			isClosed: 1,
						          			isPassed: props.item.Febspassed,
						          			isReeval: props.item.Feb_reeval
						          		}); fca_dialog = true" >
													{{props.item.Feb_percent}} %
												</v-chip>
											</v-tooltip>
										</div>
										<div v-else-if="props.item.Feb === ''">
											<v-tooltip right v-if="
													checkExempted(props.item.store_id_real,1,'Feb') ? false : 
														(		1 == ((new Date().getMonth())) || (props.item.company_code == 1 ? 
														(  (daysIntoYear(new Date('2019,02,28')) + parseInt(hfi_items[1].value)) > getCurrentDayOfYear()  ) :
														(  (daysIntoYear(new Date('2019,02,28')) + parseInt(ofi_items[1].value)) > getCurrentDayOfYear()  )
													)) && (new Date()).getFullYear() == getYears">
												<div>{{$t('No file uploaded')}}.</div>
					          		<v-chip slot="activator" small @click="storeAdditionalParam({
					          			store_id : props.item.store_id_real,
					          			year : props.item.year,
					          			store : props.item.store_id,
					          			month : 'Feb',
					          			fca_id: '',
					          			fca_exp_date: null,
					          			ratings: props.item.Feb_percent,
					          			remarks: props.item.Feb_remarks,
					          			audit_date: props.item.Feb_ad,
					          			audit_by: props.item.Feb_ad_by,
					          			isPassed: props.item.Feb_ispassed,
					          			isReeval: props.item.Feb_reeval
					          		}); fca_dialog = true" color="grey lighten-1 white--text" style="cursor:pointer;" label>{{$t('Empty')}}</v-chip>
					          		<span color="grey caption"></span>
				          		</v-tooltip>
				          		<div v-else>
												<v-tooltip right>
													<div>{{$t('You cannot upload here until end of this month.')}}</div>
													<v-chip v-if="!checkExempted(props.item.store_id_real,1,'Feb')" slot="activator" small label color="white--text">
														{{$t('Not available')}}
													</v-chip>
													<v-chip v-else slot="activator" small label color="yellow white--text">
														{{$t('Exempted')}}
													</v-chip>
												</v-tooltip>				          			
				          		</div>

				          	</div>
			          		<v-tooltip v-else right>
			          			<span v-if="Date.parse(props.item.exp_Feb) <= Date.parse(new Date().toLocaleString()) && props.item.Feb_ispassed == 1">
			          				{{$t('Click to view more details.')}}.
			          			</span>
			          			<span v-else>
			          				{{$t('Click to view more details.')}}.
			          				<span class="subheading red--text text--lighten-4" v-if="props.item.Feb_percent < percentage_threshold && props.item.Feb_ispassed == 0">
			          					<br>
			          					<v-icon color="red">warning</v-icon> {{$t('Ratings threshold should be greater than')}} {{percentage_threshold}} % <br>
			          				</span>
			   								
			   								<div v-if="props.item.Feb_percent > percentage_threshold && props.item.Feb_ispassed == 0">
			   									{{$t('This means, this is passsed but there\'s requirements that you still need to change.')}}
			   								</div>
			   								<div v-else>
			          					
			          				</div>
			          				<div >
				          				{{$t('Remaining time to edit')}}: <Countdown  :deadline="props.item.exp_Feb"></Countdown>
				          				{{$t('You will not be allowed to edit upon expiration of editting period (Unless its a request from admin)')}} <br>
			          				</div>
			          				
			          			</span>
				          		<v-chip slot="activator" @click="storeAdditionalParam({
					          			store_id : props.item.store_id_real,
					          			year : props.item.year,
					          			store : props.item.store_id,
					          			month : 'Feb',
					          			fca_id : props.item.Feb_id, //fca_id in main table
					          			fca_exp_date : props.item.exp_Feb, // expiration date
					          			ratings: props.item.Feb_percent,
					          			remarks: props.item.Feb_remarks,
					          			audit_date: props.item.Feb_ad,
					          			audit_by: props.item.Feb_ad_by,
					          			isPassed: props.item.Feb_ispassed,
					          			isReeval: props.item.Feb_reeval
					          		}); fca_dialog = true" label small  :color="(Date.parse(props.item.exp_Feb) <= Date.parse(new Date().toLocaleString()) && props.item.Feb_ispassed ==  1) || (props.item.Feb_percent < percentage_threshold && props.item.Feb_ispassed == 1) ? 'green accent-4 white--text' : 
				          				//else new condition
					          			props.item.Feb_percent < percentage_threshold ? 'red white--text' : 
					          			//else new condition
					          			props.item.Feb_percent > percentage_threshold && props.item.Feb_ispassed == 0 ? 'yellow darken-3 white--text' : 'blue accent-4 white--text'">
				          			{{props.item.Feb_percent}} %
				          		</v-chip>
			          		</v-tooltip>
			          		<v-progress-linear 
			          			v-if="props.item.Feb !== ''" 
			          			style="margin-top:0px; margin-bottom:0px;" 
			          			v-model="valueDeterminate = props.item.Feb_percent"
			          			:color="Date.parse(props.item.exp_Feb) <= Date.parse(new Date().toLocaleString()) || (Date.parse(props.item.exp_Feb) <= Date.parse(new Date().toLocaleString()) && props.item.Feb_ispassed ==  1) || (props.item.Feb_percent < percentage_threshold && props.item.Feb_ispassed == 1) ? 'green accent-4 white--text' : //else
			          				props.item.Feb_percent < percentage_threshold ? 'red white--text' : 
			          				//else ne wcondition 
			          				props.item.Feb_percent > percentage_threshold && props.item.Feb_ispassed == 0 ? 'yellow darken-3 white--text' : 'blue accent-4 white--text'
			          			"
			          		></v-progress-linear>
			          		<div v-if="props.item.Feb !== ''">
				          		<span style="color:red;" class="caption" v-if="!props.item.Feb_files">
				          			{{$t('No file')}}
				          		</span>
				          		<span v-else style="color:blue; font-size:10px;">
					          			{{$t('File(s) included')}}
					          		</span>
			          		</div>
									</td>
									<!-- end of feb -->
									
									<!-- start of march -->
									<td class="text-xs-center">
			          		<div v-if="Date.parse(props.item.exp_Mar) <= Date.parse(new Date().toLocaleString()) || (Date.parse(props.item.exp_Mar) <= Date.parse(new Date().toLocaleString()) && props.item.Mar_ispassed == 1)">
												<v-tooltip right>
													<div>{{$t('Click to view details.')}}</div>
													<v-chip slot="activator" label small color="green white--text" @click="storeAdditionalParam({
							          			store_id : props.item.store_id_real,
							          			year : props.item.year,
							          			store : props.item.store_id,
							          			month : 'Mar',
							          			fca_id : props.item.Mar_id, //fca_id in main table
							          			fca_exp_date : props.item.exp_Mar, // expiration date
							          			ratings: props.item.Mar_percent,
							          			remarks: props.item.Mar_remarks,
							          			audit_date: props.item.Mar_ad,
							          			audit_by: props.item.Mar_ad_by,
							          			isClosed: 1,
							          			isPassed: props.item.Mar_ispassed,
							          			isReeval: props.item.Mar_reeval
							          		}); fca_dialog = true" >
														{{props.item.Mar_percent}} %
													</v-chip>
												</v-tooltip>
											</div>
											<div v-else-if="props.item.Mar === ''">
												<v-tooltip right v-if="
													checkExempted(props.item.store_id_real,2,'Mar') ? false : 
														(		2 == ((new Date().getMonth())) || (props.item.company_code == 1 ? 
														(  (daysIntoYear(new Date('2018,03,31')) + parseInt(hfi_items[2].value)) > getCurrentDayOfYear()  ) :
														(  (daysIntoYear(new Date('2018,03,31')) + parseInt(ofi_items[2].value)) > getCurrentDayOfYear()  )
													)) && (new Date()).getFullYear() == getYears">
													<div>{{$t('No file uploaded')}}.</div>
						          		<v-chip slot="activator" small @click="storeAdditionalParam({
						          			store_id : props.item.store_id_real,
						          			year : props.item.year,
						          			store : props.item.store_id,
						          			month : 'Mar',
						          			fca_id: '',
						          			fca_exp_date: null,
						          			ratings: props.item.Mar_percent,
						          			remarks: props.item.Mar_remarks,
						          			audit_date: props.item.Mar_ad,
						          			audit_by: props.item.Mar_ad_by,
						          			isPassed: props.item.Mar_ispassed,
						          			isReeval: props.item.Mar_reeval
						          		}); fca_dialog = true" color="grey lighten-1 white--text" style="cursor:pointer;" label>{{$t('Empty')}}</v-chip>
						          		<span color="grey caption"></span>
					          		</v-tooltip>
					          		<div v-else>
													<v-tooltip right>
														<div>{{$t('You cannot upload here until end of this month.')}}</div>
														<v-chip v-if="!checkExempted(props.item.store_id_real,2,'Mar')" slot="activator" small label color="white--text">
															{{$t('Not available')}}
														</v-chip>
														<v-chip v-else slot="activator" small label color="yellow white--text">
															{{$t('Exempted')}}
														</v-chip>
													</v-tooltip>				          			
					          		</div>

					          	</div>
				          		<v-tooltip v-else right>
				          			<span v-if="Date.parse(props.item.exp_Mar) <= Date.parse(new Date().toLocaleString()) && props.item.Mar_ispassed == 1">
				          				{{$t('Click to view more details.')}}.
				          			</span>
				          			<span v-else>
				          				{{$t('Click to view more details.')}}.
				          				<span class="subheading red--text text--lighten-4" v-if="props.item.Mar_percent < percentage_threshold && props.item.Mar_ispassed == 0">
				          					<br>
				          					<v-icon color="red">warning</v-icon> {{$t('Ratings threshold should be greater than')}} {{percentage_threshold}} % <br>
				          				</span>
				   								
				   								<div v-if="props.item.Mar_percent > percentage_threshold && props.item.Mar_ispassed == 0">
				   									{{$t('This means, this is passsed but there\'s requirements that you still need to change.')}}
				   								</div>
				   								<div v-else>
				          					
				          				</div>
				          				<div>
					          				{{$t('Remaining time to edit')}}: <Countdown  :deadline="props.item.exp_Mar"></Countdown>
					          				{{$t('You will not be allowed to edit upon expiration of editting period (Unless its a request from admin)')}} <br>
				          				</div>
				          				
				          			</span>
					          		<v-chip slot="activator" @click="storeAdditionalParam({
						          			store_id : props.item.store_id_real,
						          			year : props.item.year,
						          			store : props.item.store_id,
						          			month : 'Mar',
						          			fca_id : props.item.Mar_id, //fca_id in main table
						          			fca_exp_date : props.item.exp_Mar, // expiration date
						          			ratings: props.item.Mar_percent,
						          			remarks: props.item.Mar_remarks,
						          			audit_date: props.item.Mar_ad,
						          			audit_by: props.item.Mar_ad_by,
						          			isPassed: props.item.Mar_ispassed,
						          			isReeval: props.item.Mar_reeval
						          		}); fca_dialog = true" label small  :color="(Date.parse(props.item.exp_Mar) <= Date.parse(new Date().toLocaleString()) && props.item.Mar_ispassed ==  1) || (props.item.Mar_percent < percentage_threshold && props.item.Mar_ispassed == 1) ? 'green accent-4 white--text' : 
					          				//else new condition
						          			props.item.Mar_percent < percentage_threshold ? 'red white--text' : 
						          			//else new condition
						          			props.item.Mar_percent > percentage_threshold && props.item.Mar_ispassed == 0 ? 'yellow darken-3 white--text' : 'blue accent-4 white--text'">
					          			{{props.item.Mar_percent}} %
					          		</v-chip>
				          		</v-tooltip>
				          		<v-progress-linear 
				          			v-if="props.item.Mar !== ''" 
				          			style="margin-top:0px; margin-bottom:0px;" 
				          			v-model="valueDeterminate = props.item.Mar_percent"
				          			:color="Date.parse(props.item.exp_Mar) <= Date.parse(new Date().toLocaleString()) || (Date.parse(props.item.exp_Mar) <= Date.parse(new Date().toLocaleString()) && props.item.Mar_ispassed ==  1) || (props.item.Mar_percent < percentage_threshold && props.item.Mar_ispassed == 1) ? 'green accent-4 white--text' : //else
				          				props.item.Mar_percent < percentage_threshold ? 'red white--text' : 
				          				//else ne wcondition 
				          				props.item.Mar_percent > percentage_threshold && props.item.Mar_ispassed == 0 ? 'yellow darken-3 white--text' : 'blue accent-4 white--text'
				          			"
				          		></v-progress-linear>
				          		<div v-if="props.item.Mar !== ''">
					          		<span style="color:red;" class="caption" v-if="!props.item.Mar_files">
					          			{{$t('No file')}}
					          		</span>
					          		<span v-else style="color:blue; font-size:10px;">
					          			{{$t('File(s) included')}}
					          		</span>
				          		</div>
									</td>
									<!-- end of march -->


									<!-- start of april -->
									<td>
										<div v-if="Date.parse(props.item.exp_Apr) <= Date.parse(new Date().toLocaleString()) || (Date.parse(props.item.exp_Apr) <= Date.parse(new Date().toLocaleString()) && props.item.Apr_ispassed == 1) ">
												<v-tooltip right>
													<div>{{$t('Click to view details.')}}</div>
													<v-chip slot="activator" label small color="green white--text" @click="storeAdditionalParam({
							          			store_id : props.item.store_id_real,
							          			year : props.item.year,
							          			store : props.item.store_id,
							          			month : 'Apr',
							          			fca_id : props.item.Apr_id, //fca_id in main table
							          			fca_exp_date : props.item.exp_Apr, // expiration date
							          			ratings: props.item.Apr_percent,
							          			remarks: props.item.Apr_remarks,
							          			audit_date: props.item.Apr_ad,
							          			audit_by: props.item.Apr_ad_by,
							          			isClosed: 1,
							          			isPassed: props.item.Apr_ispassed,
							          			isReeval: props.item.Apr_reeval
							          		}); fca_dialog = true" >
														{{props.item.Apr_percent}} %
													</v-chip>
												</v-tooltip>
											</div>
											<div v-else-if="props.item.Apr === ''">
												<v-tooltip right v-if="
													checkExempted(props.item.store_id_real,3,'Apr') ? false : 
														(		3 == ((new Date().getMonth())) || (props.item.company_code == 1 ? 
														(  (daysIntoYear(new Date('2018,04,30')) + parseInt(hfi_items[3].value)) > getCurrentDayOfYear()  ) :
														(  (daysIntoYear(new Date('2018,04,30')) + parseInt(ofi_items[3].value)) > getCurrentDayOfYear()  )
													)) && (new Date()).getFullYear() == getYears">
													 <!-- check muna natin yung company -->
													<div>{{$t('No file uploaded')}}.</div>
						          		<v-chip slot="activator" small @click="storeAdditionalParam({
						          			store_id : props.item.store_id_real,
						          			year : props.item.year,
						          			store : props.item.store_id,
						          			month : 'Apr',
						          			fca_id: '',
						          			fca_exp_date: null,
						          			ratings: props.item.Apr_percent,
						          			remarks: props.item.Apr_remarks,
						          			audit_date: props.item.Apr_ad,
						          			audit_by: props.item.Apr_ad_by,
						          			isPassed: props.item.Apr_ispassed,
						          			isReeval: props.item.Apr_reeval
						          		}); fca_dialog = true" color="grey lighten-1 white--text" style="cursor:pointer;" label>{{$t('Empty')}}</v-chip>
						          		<span color="grey caption"></span>
					          		</v-tooltip>
					          		<div v-else>
													<v-tooltip right>
														<div>{{$t('You cannot upload here until end of this month.')}}</div>
														<v-chip v-if="!checkExempted(props.item.store_id_real,3,'Apr')" slot="activator" small label color="white--text">
															{{$t('Not available')}} 
														</v-chip>
														<v-chip v-else slot="activator" small label color="yellow white--text">
															{{$t('Exempted')}} 
														</v-chip>
													</v-tooltip>				          			
					          		</div>

					          	</div>
				          		<v-tooltip v-else right>
				          			<span v-if="Date.parse(props.item.exp_Apr) <= Date.parse(new Date().toLocaleString()) && props.item.Apr_ispassed == 1">
				          				{{$t('Click to view more details.')}}.
				          			</span>
				          			<span v-else>
				          				{{$t('Click to view more details.')}}.
				          				<span class="subheading red--text text--lighten-4" v-if="props.item.Apr_percent < percentage_threshold && props.item.Apr_ispassed == 0">
				          					<br>
				          					<v-icon color="red">warning</v-icon> {{$t('Ratings threshold should be greater than')}} {{percentage_threshold}} % <br>
				          				</span>
				   								
				   								<div v-if="props.item.Apr_percent > percentage_threshold && props.item.Apr_ispassed == 0">
				   									{{$t('This means, this is passsed but there\'s requirements that you still need to change.')}}
				   								</div>
				   								<div v-else>
				          				</div>
				          				<div>
					          				{{$t('Remaining time to edit')}}: <Countdown  :deadline="props.item.exp_Apr"></Countdown>
					          				{{$t('You will not be allowed to edit upon expiration of editting period (Unless its a request from admin)')}} <br>
				          				</div>
				          				

				          			</span>
					          		<v-chip slot="activator" @click="storeAdditionalParam({
						          			store_id : props.item.store_id_real,
						          			year : props.item.year,
						          			store : props.item.store_id,
						          			month : 'Apr',
						          			fca_id : props.item.Apr_id, //fca_id in main table
						          			fca_exp_date : props.item.exp_Apr, // expiration date
						          			ratings: props.item.Apr_percent,
						          			remarks: props.item.Apr_remarks,
						          			audit_date: props.item.Apr_ad,
						          			audit_by: props.item.Apr_ad_by,
						          			isPassed: props.item.Apr_ispassed,
						          			isReeval: props.item.Apr_reeval
						          		}); fca_dialog = true" label small  :color="(Date.parse(props.item.exp_Apr) <= Date.parse(new Date().toLocaleString()) && props.item.Apr_ispassed ==  1) || (props.item.Apr_percent < percentage_threshold && props.item.Apr_ispassed == 1) ? 'green accent-4 white--text' : 
					          				//else new condition
						          			props.item.Apr_percent < percentage_threshold ? 'red white--text' : 
						          			//else new condition
						          			props.item.Apr_percent > percentage_threshold && props.item.Apr_ispassed == 0 ? 'yellow darken-3 white--text' : 'blue accent-4 white--text'">
					          			{{props.item.Apr_percent}} %
					          		</v-chip>
				          		</v-tooltip>
				          		<v-progress-linear 
				          			v-if="props.item.Apr !== ''" 
				          			style="margin-top:0px; margin-bottom:0px;" 
				          			v-model="valueDeterminate = props.item.Apr_percent"
				          			:color="Date.parse(props.item.exp_Apr) <= Date.parse(new Date().toLocaleString()) || (Date.parse(props.item.exp_Apr) <= Date.parse(new Date().toLocaleString()) && props.item.Apr_ispassed ==  1) || (props.item.Apr_percent < percentage_threshold && props.item.Apr_ispassed == 1) ? 'green accent-4 white--text' : //else
				          				props.item.Apr_percent < percentage_threshold ? 'red white--text' : 
				          				//else ne wcondition 
				          				props.item.Apr_percent > percentage_threshold && props.item.Apr_ispassed == 0 ? 'yellow darken-3 white--text' : 'blue accent-4 white--text'
				          			"
				          		></v-progress-linear>
				          		<div v-if="props.item.Apr !== ''">
					          		<span style="color:red;" class="caption" v-if="!props.item.Apr_files">
					          			{{$t('No file')}}
					          		</span>
					          		<span v-else style="color:blue; font-size:10px;">
					          			{{$t('File(s) included')}}
					          		</span>
				          		</div>
									</td>
									<!-- end of april -->


									<!-- start of may -->
									<td>
										<div v-if="Date.parse(props.item.exp_May) <= Date.parse(new Date().toLocaleString()) || ( Date.parse(props.item.exp_May) <= Date.parse(new Date().toLocaleString()) && props.item.May_ispassed == 1)">
												<v-tooltip right>
													<div>{{$t('Click to view details.')}}</div>
													<v-chip slot="activator" label small color="green white--text" @click="storeAdditionalParam({
							          			store_id : props.item.store_id_real,
							          			year : props.item.year,
							          			store : props.item.store_id,
							          			month : 'May',
							          			fca_id : props.item.May_id, //fca_id in main table
							          			fca_exp_date : props.item.exp_May, // expiration date
							          			ratings: props.item.May_percent,
							          			remarks: props.item.May_remarks,
							          			audit_date: props.item.May_ad,
							          			audit_by: props.item.May_ad_by,
							          			isClosed: 1,
							          			isPassed: props.item.May_ispassed,
							          			isReeval: props.item.May_reeval
							          		}); fca_dialog = true" >
														{{props.item.May_percent}} %
													</v-chip>
												</v-tooltip>
											</div>
											<div v-else-if="props.item.May === ''"> <!-- gonna check this if this will be extended -->
												<v-tooltip right v-if="
													checkExempted(props.item.store_id_real,4,'May') ? false : 
														(		4 == ((new Date().getMonth())) || (props.item.company_code == 1 ? 
														(  (daysIntoYear(new Date('2019,05,31')) + parseInt(hfi_items[4].value)) > getCurrentDayOfYear()  ) :
														(  (daysIntoYear(new Date('2019,05,31')) + parseInt(ofi_items[4].value)) > getCurrentDayOfYear()  )
													)) && (new Date()).getFullYear() == getYears">
													
													<div>{{$t('No file uploaded')}}.</div>
						          		<v-chip slot="activator" small @click="storeAdditionalParam({
						          			store_id : props.item.store_id_real,
						          			year : props.item.year,
						          			store : props.item.store_id,
						          			month : 'May',
						          			fca_id: '',
						          			fca_exp_date: null,
						          			ratings: props.item.May_percent,
						          			remarks: props.item.May_remarks,
						          			audit_date: props.item.May_ad,
						          			audit_by: props.item.May_ad_by,
						          			isPassed: props.item.May_ispassed,
						          			isReeval: props.item.May_reeval
						          		}); fca_dialog = true" color="grey lighten-1 white--text" style="cursor:pointer;" label>{{$t('Empty')}}</v-chip>
						          		<span color="grey caption"></span>
					          		</v-tooltip>
					          		<div v-else>
													<v-tooltip right>
														<div>{{$t('You cannot upload here until end of this month.')}}</div>
														<v-chip v-if="!checkExempted(props.item.store_id_real,4,'May')" slot="activator" small label color="white--text">
															{{$t('Not available')}} 
														</v-chip>
														<v-chip v-else slot="activator" small label color="yellow white--text">
															{{$t('Exempted')}}
														</v-chip>
													</v-tooltip>				          			
					          		</div>
					          	</div>
				          		<v-tooltip v-else right>
				          			<span v-if="Date.parse(props.item.exp_May) <= Date.parse(new Date().toLocaleString()) && props.item.May_ispassed == 1">
				          				{{$t('Click to view more details.')}}.
				          			</span>
				          			<span v-else>
				          				{{$t('Click to view more details.')}}.
				          				<span class="subheading red--text text--lighten-4" v-if="props.item.May_percent < percentage_threshold && props.item.May_ispassed == 0">
				          					<br>
				          					<v-icon color="red">warning</v-icon> {{$t('Ratings threshold should be greater than')}} {{percentage_threshold}} % <br>
				          				</span>
				   								
				   								<div v-if="props.item.Apr_percent > percentage_threshold && props.item.Apr_ispassed == 0">
				   									{{$t('This means, this is passsed but there\'s requirements that you still need to change.')}}
				   								</div>
				   								<div v-else>
				          				</div>
				          				<div>
					          				{{$t('Remaining time to edit')}}: <Countdown  :deadline="props.item.exp_May"></Countdown>
					          				{{$t('You will not be allowed to edit upon expiration of editting period (Unless its a request from admin)')}} <br>
				          				</div>
				          			</span>
					          		<v-chip slot="activator" @click="storeAdditionalParam({
						          			store_id : props.item.store_id_real,
						          			year : props.item.year,
						          			store : props.item.store_id,
						          			month : 'May',
						          			fca_id : props.item.May_id, //fca_id in main table
						          			fca_exp_date : props.item.exp_May, // expiration date
						          			ratings: props.item.May_percent,
						          			remarks: props.item.May_remarks,
						          			audit_date: props.item.May_ad,
						          			audit_by: props.item.May_ad_by,
						          			isPassed: props.item.May_ispassed,
						          			isReeval: props.item.May_reeval
						          		}); fca_dialog = true" label small  :color="(Date.parse(props.item.exp_May) <= Date.parse(new Date().toLocaleString()) && props.item.May_ispassed ==  1) || (props.item.May_percent < percentage_threshold && props.item.May_ispassed == 1) ? 'green accent-4 white--text' : 
					          				//else new condition
						          			props.item.May_percent < percentage_threshold ? 'red white--text' : 
						          			//else new condition
						          			props.item.May_percent > percentage_threshold && props.item.May_ispassed == 0 ? 'yellow darken-3 white--text' : 'blue accent-4 white--text'">
					          			{{props.item.May_percent}} %
					          		</v-chip>
				          		</v-tooltip>
				          		<v-progress-linear 
				          			v-if="props.item.May !== ''" 
				          			style="margin-top:0px; margin-bottom:0px;" 
				          			v-model="valueDeterminate = props.item.May_percent"
				          			:color="Date.parse(props.item.exp_May) <= Date.parse(new Date().toLocaleString()) || (Date.parse(props.item.exp_May) <= Date.parse(new Date().toLocaleString()) && props.item.May_ispassed ==  1) || (props.item.May_percent < percentage_threshold && props.item.May_ispassed == 1) ? 'green accent-4 white--text' : //else
				          				props.item.May_percent < percentage_threshold ? 'red white--text' : 
				          				//else ne wcondition 
				          				props.item.May_percent > percentage_threshold && props.item.May_ispassed == 0 ? 'yellow darken-3 white--text' : 'blue accent-4 white--text'
				          			"
				          		></v-progress-linear>
				          		<div v-if="props.item.May !== ''">
					          		<span style="color:red;" class="caption" v-if="!props.item.May_files">
					          			{{$t('No file')}}
					          		</span>
					          		<span v-else style="color:blue; font-size:10px;">
					          			{{$t('File(s) included')}}
					          		</span>
				          		</div>
									</td>
									<!-- end of may -->

									<!-- start of june -->
									<td>
										<div v-if="Date.parse(props.item.exp_Jun) <= Date.parse(new Date().toLocaleString()) || (Date.parse(props.item.exp_Jun) <= Date.parse(new Date().toLocaleString()) && props.item.Jun_ispassed == 1)">
												<v-tooltip right>
													<div>{{$t('Click to view details.')}}</div>
													<v-chip slot="activator" label small color="green white--text" @click="storeAdditionalParam({
							          			store_id : props.item.store_id_real,
							          			year : props.item.year,
							          			store : props.item.store_id,
							          			month : 'Jun',
							          			fca_id : props.item.Jun_id, //fca_id in main table
							          			fca_exp_date : props.item.exp_Jun, // expiration date
							          			ratings: props.item.Jun_percent,
							          			remarks: props.item.Jun_remarks,
							          			audit_date: props.item.Jun_ad,
							          			audit_by: props.item.Jun_ad_by,
							          			isClosed: 1,
							          			isPassed: props.item.Jun_ispassed
							          		}); fca_dialog = true" >
														{{props.item.Jun_percent}} %
													</v-chip>
												</v-tooltip>
											</div>
											<div v-else-if="props.item.Jun === ''">
												<v-tooltip right v-if=" 
															checkExempted(props.item.store_id_real,5,'Jun') ? false : 
															(5 == ((new Date().getMonth())) || (props.item.company_code == 1 ? 
																(  (daysIntoYear(new Date('2018,06,30')) + parseInt(hfi_items[5].value)) > getCurrentDayOfYear()  ) :
																(  (daysIntoYear(new Date('2018,06,30')) + parseInt(ofi_items[5].value)) > getCurrentDayOfYear()  )
															)) && (new Date()).getFullYear() == getYears">

													<div>{{$t('No file uploaded')}}.</div>
						          		<v-chip slot="activator" small @click="storeAdditionalParam({
						          			store_id : props.item.store_id_real,
						          			year : props.item.year,
						          			store : props.item.store_id,
						          			month : 'Jun',
						          			fca_id: '',
						          			fca_exp_date: null,
						          			ratings: props.item.Jun_percent,
						          			remarks: props.item.Jun_remarks,
						          			audit_date: props.item.Jun_ad,
						          			audit_by: props.item.Jun_ad_by,
						          			isPassed: props.item.Jun_ispassed
						          		}); fca_dialog = true" color="grey lighten-1 white--text" style="cursor:pointer;" label>{{$t('Empty')}}</v-chip>
						          		<span color="grey caption"></span>
					          		</v-tooltip>
					          		<div v-else>
													<v-tooltip right>
														<div>{{$t('You cannot upload here until end of this month.')}}</div>
														<v-chip v-if="!checkExempted(props.item.store_id_real,5,'Jun')" slot="activator" small label color="white--text">
															{{$t('Not available')}}
														</v-chip>
														<v-chip v-else slot="activator" small label color="yellow white--text">
															{{$t('Exempted')}}
														</v-chip>
													</v-tooltip>				          			
					          		</div>

					          	</div>
				          		<v-tooltip v-else right>
				          			<span v-if="Date.parse(props.item.exp_Jun) <= Date.parse(new Date().toLocaleString()) && props.item.Jun_ispassed == 1">
				          				{{$t('Click to view more details.')}}.
				          			</span>
				          			<span v-else>
				          				{{$t('Click to view more details.')}}.
				          				<span class="subheading red--text text--lighten-4" v-if="props.item.Jun_percent < percentage_threshold && props.item.Jun_ispassed == 0">
				          					<br>
				          					<v-icon color="red">warning</v-icon> {{$t('Ratings threshold should be greater than')}} {{percentage_threshold}} % <br>
				          				</span>
				   								
				   								<div v-if="props.item.Jun_percent > percentage_threshold && props.item.Jun_ispassed == 0">
				   									{{$t('This means, this is passsed but there\'s requirements that you still need to change.')}}
				   								</div>
				   								<div v-else>
				          				</div>

				          				<div>
					          				{{$t('Remaining time to edit')}}: <Countdown  :deadline="props.item.exp_Jun"></Countdown>
					          				{{$t('You will not be allowed to edit upon expiration of editting period (Unless its a request from admin)')}} <br>
				          				</div>
				          				
				          			</span>
					          		<v-chip slot="activator" @click="storeAdditionalParam({
						          			store_id : props.item.store_id_real,
						          			year : props.item.year,
						          			store : props.item.store_id,
						          			month : 'Jun',
						          			fca_id : props.item.Jun_id, //fca_id in main table
						          			fca_exp_date : props.item.exp_Jun, // expiration date
						          			ratings: props.item.Jun_percent,
						          			remarks: props.item.Jun_remarks,
						          			audit_date: props.item.Jun_ad,
						          			audit_by: props.item.Jun_ad_by,
						          			isPassed: props.item.Jun_ispassed
						          		}); fca_dialog = true" label small  :color="(Date.parse(props.item.exp_Jun) <= Date.parse(new Date().toLocaleString()) && props.item.Jun_ispassed ==  1) || (props.item.Jun_percent < percentage_threshold && props.item.Jun_ispassed == 1) ? 'green accent-4 white--text' : 
					          				//else new condition
						          			props.item.Jun_percent < percentage_threshold ? 'red white--text' : 
						          			//else new condition
						          			props.item.Jun_percent > percentage_threshold && props.item.Jun_ispassed == 0 ? 'yellow darken-3 white--text' : 'blue accent-4 white--text'">
					          			{{props.item.Jun_percent}} %
					          		</v-chip>
				          		</v-tooltip>
				          		<v-progress-linear 
				          			v-if="props.item.Jun !== ''" 
				          			style="margin-top:0px; margin-bottom:0px;" 
				          			v-model="valueDeterminate = props.item.Jun_percent"
				          			:color="Date.parse(props.item.exp_Jun) <= Date.parse(new Date().toLocaleString()) || (Date.parse(props.item.exp_Jun) <= Date.parse(new Date().toLocaleString()) && props.item.Jun_ispassed ==  1) || (props.item.Jun_percent < percentage_threshold && props.item.Jun_ispassed == 1) ? 'green accent-4 white--text' : //else
				          				props.item.Jun_percent < percentage_threshold ? 'red white--text' : 
				          				//else ne wcondition 
				          				props.item.Jun_percent > percentage_threshold && props.item.Jun_ispassed == 0 ? 'yellow darken-3 white--text' : 'blue accent-4 white--text'
				          			"
				          		></v-progress-linear>
				          		<div v-if="props.item.Jun !== ''">
					          		<span style="color:red;" class="caption" v-if="!props.item.Jun_files">
					          			{{$t('No file')}}
					          		</span>
					          		<span v-else style="color:blue; font-size:10px;">
					          			{{$t('File(s) included')}}
					          		</span>
				          		</div>
									</td>
									<!-- end of june -->

									<!-- start of july -->
									<td>
										<div v-if="Date.parse(props.item.exp_Jul) <= Date.parse(new Date().toLocaleString()) || (Date.parse(props.item.exp_Jul) <= Date.parse(new Date().toLocaleString()) && props.item.Jul_ispassed == 1)">
												<v-tooltip right>
													<div>{{$t('Click to view details.')}}</div>
													<v-chip slot="activator" label small color="green white--text" @click="storeAdditionalParam({
							          			store_id : props.item.store_id_real,
							          			year : props.item.year,
							          			store : props.item.store_id,
							          			month : 'Jul',
							          			fca_id : props.item.Jul_id, //fca_id in main table
							          			fca_exp_date : props.item.exp_Jul, // expiration date
							          			ratings: props.item.Jul_percent,
							          			remarks: props.item.Jul_remarks,
							          			audit_date: props.item.Jul_ad,
							          			audit_by: props.item.Jul_ad_by,
							          			isClosed: 1,
							          			isPassed: props.item.Jul_ispassed
							          		}); fca_dialog = true" >
														{{props.item.Jul_percent}} %
													</v-chip>
												</v-tooltip>
											</div>
											<div v-else-if="props.item.Jul === ''">
											
												<v-tooltip right v-if="
													checkExempted(props.item.store_id_real,6,'Jul') ? false : 
														(		6 == ((new Date().getMonth())) || (props.item.company_code == 1 ? 
														(  (daysIntoYear(new Date('2018,07,31')) + parseInt(hfi_items[6].value)) > getCurrentDayOfYear()  ) :
														(  (daysIntoYear(new Date('2018,07,31')) + parseInt(ofi_items[6].value)) > getCurrentDayOfYear()  )
													)) && (new Date()).getFullYear() == getYears">
													<div>{{$t('No file uploaded')}}.</div>
						          		<v-chip slot="activator" small @click="storeAdditionalParam({
						          			store_id : props.item.store_id_real,
						          			year : props.item.year,
						          			store : props.item.store_id,
						          			month : 'Jul',
						          			fca_id: '',
						          			fca_exp_date: null,
						          			ratings: props.item.Jul_percent,
						          			remarks: props.item.Jul_remarks,
						          			audit_date: props.item.Jul_ad,
						          			audit_by: props.item.Jul_ad_by,
						          			isPassed: props.item.Jul_ispassed
						          		}); fca_dialog = true" color="grey lighten-1 white--text" style="cursor:pointer;" label>{{$t('Empty')}}</v-chip>
						          		<span color="grey caption"></span>
					          		</v-tooltip>
					          		<div v-else>
													<v-tooltip right>
														<div>{{$t('You cannot upload here until end of this month.')}}</div>
														<v-chip v-if="!checkExempted(props.item.store_id_real,6,'Jul')" slot="activator" small label color="white--text">
															{{$t('Not available')}}
														</v-chip>
														<v-chip v-else slot="activator" small label color="yellow white--text">
															{{$t('Exempted')}}
														</v-chip>
													</v-tooltip>				          			
					          		</div>

					          	</div>
				          		<v-tooltip v-else right>
				          			<span v-if="Date.parse(props.item.exp_Jul) <= Date.parse(new Date().toLocaleString()) && props.item.Jul_ispassed == 1">
				          				{{$t('Click to view more details.')}}.
				          			</span>
				          			<span v-else>
				          				{{$t('Click to view more details.')}}.
				          				<span class="subheading red--text text--lighten-4" v-if="props.item.Jul_percent < percentage_threshold && props.item.Jul_ispassed == 0">
				          					<br>
				          					<v-icon color="red">warning</v-icon> {{$t('Ratings threshold should be greater than')}} {{percentage_threshold}} % <br>
				          				</span>
				   								
				   								<div v-if="props.item.Jul_percent > percentage_threshold && props.item.Jul_ispassed == 0">
				   									{{$t('This means, this is passsed but there\'s requirements that you still need to change.')}}
				   								</div>
				   								<div v-else>
				          					
				          				</div>
				          				<div  >
					          				{{$t('Remaining time to edit')}}: <Countdown  :deadline="props.item.exp_Jul"></Countdown>
					          				{{$t('You will not be allowed to edit upon expiration of editting period (Unless its a request from admin)')}} <br>
				          				</div>
				          				
				          			</span>
					          		<v-chip slot="activator" @click="storeAdditionalParam({
						          			store_id : props.item.store_id_real,
						          			year : props.item.year,
						          			store : props.item.store_id,
						          			month : 'Jul',
						          			fca_id : props.item.Jul_id, //fca_id in main table
						          			fca_exp_date : props.item.exp_Jul, // expiration date
						          			ratings: props.item.Jul_percent,
						          			remarks: props.item.Jul_remarks,
						          			audit_date: props.item.Jul_ad,
						          			audit_by: props.item.Jul_ad_by,
						          			isPassed: props.item.Jul_ispassed
						          		}); fca_dialog = true" label small  :color="(Date.parse(props.item.exp_Jul) <= Date.parse(new Date().toLocaleString()) && props.item.Jul_ispassed ==  1) || (props.item.Jul_percent < percentage_threshold && props.item.Jul_ispassed == 1) ? 'green accent-4 white--text' : 
					          				//else new condition
						          			props.item.Jul_percent < percentage_threshold ? 'red white--text' : 
						          			//else new condition
						          			props.item.Jul_percent > percentage_threshold && props.item.Jul_ispassed == 0 ? 'yellow darken-3 white--text' : 'blue accent-4 white--text'">
					          			{{props.item.Jul_percent}} %
					          		</v-chip>
				          		</v-tooltip>
				          		<v-progress-linear 
				          			v-if="props.item.Jul !== ''" 
				          			style="margin-top:0px; margin-bottom:0px;" 
				          			v-model="valueDeterminate = props.item.Jul_percent"
				          			:color="Date.parse(props.item.exp_Jul) <= Date.parse(new Date().toLocaleString()) || (Date.parse(props.item.exp_Jul) <= Date.parse(new Date().toLocaleString()) && props.item.Jul_ispassed ==  1) || (props.item.Jul_percent < percentage_threshold && props.item.Jul_ispassed == 1) ? 'green accent-4 white--text' : //else
				          				props.item.Jul_percent < percentage_threshold ? 'red white--text' : 
				          				//else ne wcondition 
				          				props.item.Jul_percent > percentage_threshold && props.item.Jul_ispassed == 0 ? 'yellow darken-3 white--text' : 'blue accent-4 white--text'
				          			"
				          		></v-progress-linear>
				          		<div v-if="props.item.Jul !== ''">
					          		<span style="color:red;" class="caption" v-if="!props.item.Jul_files">
					          			{{$t('No file')}}
					          		</span>
					          		<span v-else style="color:blue; font-size:10px;">
					          			{{$t('File(s) included')}}
					          		</span>
				          		</div>
									</td>
									<!-- end of july -->

									<!-- start of august -->
									<td>
										<div v-if="Date.parse(props.item.exp_Aug) <= Date.parse(new Date().toLocaleString()) || (Date.parse(props.item.exp_Aug) <= Date.parse(new Date().toLocaleString()) && props.item.Aug_ispassed == 1)">
												<v-tooltip right>
													<div>{{$t('Click to view details.')}}</div>
													<v-chip slot="activator" label small color="green white--text" @click="storeAdditionalParam({
							          			store_id : props.item.store_id_real,
							          			year : props.item.year,
							          			store : props.item.store_id,
							          			month : 'Aug',
							          			fca_id : props.item.Aug_id, //fca_id in main table
							          			fca_exp_date : props.item.exp_Aug, // expiration date
							          			ratings: props.item.Aug_percent,
							          			remarks: props.item.Aug_remarks,
							          			audit_date: props.item.Aug_ad,
							          			audit_by: props.item.Aug_ad_by,
							          			isClosed: 1,
							          			isPassed: props.item.Aug_ispassed
							          		}); fca_dialog = true" >
														{{props.item.Aug_percent}} %
													</v-chip>
												</v-tooltip>
											</div>
											<div v-else-if="props.item.Aug === ''">
												<v-tooltip right v-if="
													checkExempted(props.item.store_id_real,7,'Aug') ? false : 
														(		7 == ((new Date().getMonth())) || (props.item.company_code == 1 ? 
														(  (daysIntoYear(new Date('2018,08,31')) + parseInt(hfi_items[7].value)) > getCurrentDayOfYear()  ) :
														(  (daysIntoYear(new Date('2018,08,31')) + parseInt(ofi_items[7].value)) > getCurrentDayOfYear()  )
													)) && (new Date()).getFullYear() == getYears">
													<div>{{$t('No file uploaded')}}.</div>
						          		<v-chip slot="activator" small @click="storeAdditionalParam({
						          			store_id : props.item.store_id_real,
						          			year : props.item.year,
						          			store : props.item.store_id,
						          			month : 'Aug',
						          			fca_id: '',
						          			fca_exp_date: null,
						          			ratings: props.item.Aug_percent,
						          			remarks: props.item.Aug_remarks,
						          			audit_date: props.item.Aug_ad,
						          			audit_by: props.item.Aug_ad_by,
						          			isPassed: props.item.Aug_ispassed
						          		}); fca_dialog = true" color="grey lighten-1 white--text" style="cursor:pointer;" label>{{$t('Empty')}}</v-chip>
						          		<span color="grey caption"></span>
					          		</v-tooltip>
					          		<div v-else>
													<v-tooltip right>
														<div>{{$t('You cannot upload here until end of this month.')}}</div>
														<v-chip v-if="!checkExempted(props.item.store_id_real,7,'Aug')" slot="activator" small label color="white--text">
															{{$t('Not available')}}
														</v-chip>
														<v-chip v-else slot="activator" small label color="yellow white--text">
															{{$t('Exempted')}}
														</v-chip>

													</v-tooltip>				          			
					          		</div>

					          	</div>
				          		<v-tooltip v-else right>
				          			<span v-if="Date.parse(props.item.exp_Aug) <= Date.parse(new Date().toLocaleString()) && props.item.Aug_ispassed == 1">
				          				{{$t('Click to view more details.')}}.
				          			</span>
				          			<span v-else>
				          				{{$t('Click to view more details.')}}.
				          				<span class="subheading red--text text--lighten-4" v-if="props.item.Aug_percent < percentage_threshold && props.item.Aug_ispassed == 0">
				          					<br>
				          					<v-icon color="red">warning</v-icon> {{$t('Ratings threshold should be greater than')}} {{percentage_threshold}} % <br>
				          				</span>
				   								
				   								<div v-if="props.item.Aug_percent > percentage_threshold && props.item.Aug_ispassed == 0">
				   									{{$t('This means, this is passsed but there\'s requirements that you still need to change.')}}
				   								</div>
				   								<div v-else>
				          					
				          				</div>
				          				<div>
					          				{{$t('Remaining time to edit')}}: <Countdown  :deadline="props.item.exp_Aug"></Countdown>
					          				{{$t('You will not be allowed to edit upon expiration of editting period (Unless its a request from admin)')}} <br>
				          				</div>
				          				
				          			</span>
					          		<v-chip slot="activator" @click="storeAdditionalParam({
						          			store_id : props.item.store_id_real,
						          			year : props.item.year,
						          			store : props.item.store_id,
						          			month : 'Aug',
						          			fca_id : props.item.Aug_id, //fca_id in main table
						          			fca_exp_date : props.item.exp_Aug, // expiration date
						          			ratings: props.item.Aug_percent,
						          			remarks: props.item.Aug_remarks,
						          			audit_date: props.item.Aug_ad,
						          			audit_by: props.item.Aug_ad_by,
						          			isPassed: props.item.Aug_ispassed
						          		}); fca_dialog = true" label small  :color="(Date.parse(props.item.exp_Aug) <= Date.parse(new Date().toLocaleString()) && props.item.Aug_ispassed ==  1) || (props.item.Aug_percent < percentage_threshold && props.item.Aug_ispassed == 1) ? 'green accent-4 white--text' : 
					          				//else new condition
						          			props.item.Aug_percent < percentage_threshold ? 'red white--text' : 
						          			//else new condition
						          			props.item.Aug_percent > percentage_threshold && props.item.Aug_ispassed == 0 ? 'yellow darken-3 white--text' : 'blue accent-4 white--text'">
					          			{{props.item.Aug_percent}} %
					          		</v-chip>
				          		</v-tooltip>
				          		<v-progress-linear 
				          			v-if="props.item.Aug !== ''" 
				          			style="margin-top:0px; margin-bottom:0px;" 
				          			v-model="valueDeterminate = props.item.Aug_percent"
				          			:color="Date.parse(props.item.exp_Aug) <= Date.parse(new Date().toLocaleString()) || (Date.parse(props.item.exp_Aug) <= Date.parse(new Date().toLocaleString()) && props.item.Aug_ispassed ==  1) || (props.item.Aug_percent < percentage_threshold && props.item.Aug_ispassed == 1) ? 'green accent-4 white--text' : //else
				          				props.item.Aug_percent < percentage_threshold ? 'red white--text' : 
				          				//else ne wcondition 
				          				props.item.Aug_percent > percentage_threshold && props.item.Aug_ispassed == 0 ? 'yellow darken-3 white--text' : 'blue accent-4 white--text'
				          			"
				          		></v-progress-linear>
				          		<div v-if="props.item.Aug !== ''">
					          		<span style="color:red;" class="caption" v-if="!props.item.Aug_files">
					          			{{$t('No file')}}
					          		</span>
					          		<span v-else style="color:blue; font-size:10px;">
					          			{{$t('File(s) included')}}
					          		</span>
				          		</div>
									</td>
									<!-- end of august -->

									<!-- start of september -->
									<td>
										<div v-if="Date.parse(props.item.exp_Sep) <= Date.parse(new Date().toLocaleString()) || (Date.parse(props.item.exp_Sep) <= Date.parse(new Date().toLocaleString()) && props.item.Sep_ispassed == 1)">
												<v-tooltip right>
													<div>{{$t('Click to view details.')}}</div>
													<v-chip slot="activator" label small color="green white--text" @click="storeAdditionalParam({
							          			store_id : props.item.store_id_real,
							          			year : props.item.year,
							          			store : props.item.store_id,
							          			month : 'Sep',
							          			fca_id : props.item.Sep_id, //fca_id in main table
							          			fca_exp_date : props.item.exp_Sep, // expiration date
							          			ratings: props.item.Sep_percent,
							          			remarks: props.item.Sep_remarks,
							          			audit_date: props.item.Sep_ad,
							          			audit_by: props.item.Sep_ad_by,
							          			isClosed: 1,
							          			isPassed: props.item.Sep_ispassed
							          		}); fca_dialog = true" >
														{{props.item.Sep_percent}} %
													</v-chip>
												</v-tooltip>
											</div>
											<div v-else-if="props.item.Sep === ''">
												<v-tooltip right v-if="
													checkExempted(props.item.store_id_real,8,'Sep') ? false : 
														(		8 == ((new Date().getMonth())) || (props.item.company_code == 1 ? 
														(  (daysIntoYear(new Date('2018,09,30')) + parseInt(hfi_items[8].value)) > getCurrentDayOfYear()  ) :
														(  (daysIntoYear(new Date('2018,09,30')) + parseInt(ofi_items[8].value)) > getCurrentDayOfYear()  )
													)) && (new Date()).getFullYear() == getYears">
													<div>{{$t('No file uploaded')}}.</div>
						          		<v-chip slot="activator" small @click="storeAdditionalParam({
						          			store_id : props.item.store_id_real,
						          			year : props.item.year,
						          			store : props.item.store_id,
						          			month : 'Sep',
						          			fca_id: '',
						          			fca_exp_date: null,
						          			ratings: props.item.Sep_percent,
						          			remarks: props.item.Sep_remarks,
						          			audit_date: props.item.Sep_ad,
						          			audit_by: props.item.Sep_ad_by,
						          			isPassed: props.item.Sep_ispassed
						          		}); fca_dialog = true" color="grey lighten-1 white--text" style="cursor:pointer;" label>{{$t('Empty')}}</v-chip>
						          		<span color="grey caption"></span>
					          		</v-tooltip>
					          		<div v-else>
													<v-tooltip right>
														<div>{{$t('You cannot upload here until end of this month.')}}</div>
														<v-chip v-if="!checkExempted(props.item.store_id_real,8,'Sep')" slot="activator" small label color="white--text">
															{{$t('Not available')}}
														</v-chip>
														<v-chip v-else slot="activator" small label color="Yellow white--text">
															{{$t('Exempted')}}
														</v-chip>
													</v-tooltip>				          			
					          		</div>

					          	</div>
				          		<v-tooltip v-else right>
				          			<span v-if="Date.parse(props.item.exp_Sep) <= Date.parse(new Date().toLocaleString()) && props.item.Sep_ispassed == 1">
				          				{{$t('Click to view more details.')}}.
				          			</span>
				          			<span v-else>
				          				{{$t('Click to view more details.')}}.
				          				<span class="subheading red--text text--lighten-4" v-if="props.item.Sep_percent < percentage_threshold && props.item.Sep_ispassed == 0">
				          					<br>
				          					<v-icon color="red">warning</v-icon> {{$t('Ratings threshold should be greater than')}} {{percentage_threshold}} % <br>
				          				</span>
				   								
				   								<div v-if="props.item.Sep_percent > percentage_threshold && props.item.Sep_ispassed == 0">
				   									{{$t('This means, this is passsed but there\'s requirements that you still need to change.')}}
				   								</div>
				   								<div v-else>
				          					
				          				</div>
				   								<!-- v-else-if="props.item.Sep_ispassed == 1" -->
				          				<div  >
					          				{{$t('Remaining time to edit')}}: <Countdown  :deadline="props.item.exp_Sep"></Countdown>
					          				{{$t('You will not be allowed to edit upon expiration of editting period (Unless its a request from admin)')}} <br>
				          				</div>
				          				
				          			</span>
					          		<v-chip slot="activator" @click="storeAdditionalParam({
						          			store_id : props.item.store_id_real,
						          			year : props.item.year,
						          			store : props.item.store_id,
						          			month : 'Sep',
						          			fca_id : props.item.Sep_id, //fca_id in main table
						          			fca_exp_date : props.item.exp_Sep, // expiration date
						          			ratings: props.item.Sep_percent,
						          			remarks: props.item.Sep_remarks,
						          			audit_date: props.item.Sep_ad,
						          			audit_by: props.item.Sep_ad_by,
						          			isPassed: props.item.Sep_ispassed
						          		}); fca_dialog = true" label small  :color="(Date.parse(props.item.exp_Sep) <= Date.parse(new Date().toLocaleString()) && props.item.Sep_ispassed ==  1) || (props.item.Sep_percent < percentage_threshold && props.item.Sep_ispassed == 1) ? 'green accent-4 white--text' : 
					          				//else new condition
						          			props.item.Sep_percent < percentage_threshold ? 'red white--text' : 
						          			//else new condition
						          			props.item.Sep_percent > percentage_threshold && props.item.Sep_ispassed == 0 ? 'yellow darken-3 white--text' : 'blue accent-4 white--text'">
					          			{{props.item.Sep_percent}} %
					          		</v-chip>
				          		</v-tooltip>
				          		<v-progress-linear 
				          			v-if="props.item.Sep !== ''" 
				          			style="margin-top:0px; margin-bottom:0px;" 
				          			v-model="valueDeterminate = props.item.Sep_percent"
				          			:color="Date.parse(props.item.exp_Sep) <= Date.parse(new Date().toLocaleString()) || (Date.parse(props.item.exp_Sep) <= Date.parse(new Date().toLocaleString()) && props.item.Sep_ispassed ==  1) || (props.item.Sep_percent < percentage_threshold && props.item.Sep_ispassed == 1) ? 'green accent-4 white--text' : //else
				          				props.item.Sep_percent < percentage_threshold ? 'red white--text' : 
				          				//else ne wcondition 
				          				props.item.Sep_percent > percentage_threshold && props.item.Sep_ispassed == 0 ? 'yellow darken-3 white--text' : 'blue accent-4 white--text'
				          			"
				          		></v-progress-linear>
				          		<div v-if="props.item.Sep !== ''">
					          		<span style="color:red;" class="caption" v-if="!props.item.Sep_files">
					          			{{$t('No file')}}
					          		</span>
					          		<span v-else style="color:blue; font-size:10px;">
					          			{{$t('File(s) included')}}
					          		</span>
				          		</div>
									</td>
									<!-- end of september -->

									<!-- start of october -->
									<td>
										<div v-if="Date.parse(props.item.exp_Oct) <= Date.parse(new Date().toLocaleString()) || (Date.parse(props.item.exp_Oct) <= Date.parse(new Date().toLocaleString()) && props.item.Oct_ispassed == 1) ">
												<v-tooltip right>
													<div>{{$t('Click to view details.')}}</div>
													<v-chip slot="activator" label small color="green white--text" @click="storeAdditionalParam({
							          			store_id : props.item.store_id_real,
							          			year : props.item.year,
							          			store : props.item.store_id,
							          			month : 'Oct',
							          			fca_id : props.item.Oct_id, //fca_id in main table
							          			fca_exp_date : props.item.exp_Oct, // expiration date
							          			ratings: props.item.Oct_percent,
							          			remarks: props.item.Oct_remarks,
							          			audit_date: props.item.Oct_ad,
							          			audit_by: props.item.Oct_ad_by,
							          			isClosed: 1,
							          			isPassed: props.item.Oct_ispassed
							          		}); fca_dialog = true" >
														{{props.item.Oct_percent}} %
													</v-chip>
												</v-tooltip>
											</div>
											<div v-else-if="props.item.Oct === ''">
												<v-tooltip right v-if="
													checkExempted(props.item.store_id_real,9,'Oct') ? false : 
														(		9 == ((new Date().getMonth())) || (props.item.company_code == 1 ? 
														(  (daysIntoYear(new Date('2018,10,31')) + parseInt(hfi_items[9].value)) > getCurrentDayOfYear()  ) :
														(  (daysIntoYear(new Date('2018,10,31')) + parseInt(ofi_items[9].value)) > getCurrentDayOfYear()  )
													)) && (new Date()).getFullYear() == getYears">
													<div>{{$t('No file uploaded')}}.</div>
						          		<v-chip slot="activator" small @click="storeAdditionalParam({
						          			store_id : props.item.store_id_real,
						          			year : props.item.year,
						          			store : props.item.store_id,
						          			month : 'Oct',
						          			fca_id: '',
						          			fca_exp_date: null,
						          			ratings: props.item.Oct_percent,
						          			remarks: props.item.Oct_remarks,
						          			audit_date: props.item.Oct_ad,
						          			audit_by: props.item.Oct_ad_by,
						          			isPassed: props.item.Oct_ispassed
						          		}); fca_dialog = true" color="grey lighten-1 white--text" style="cursor:pointer;" label>{{$t('Empty')}}</v-chip>
						          		<span color="grey caption"></span>
					          		</v-tooltip>
					          		<div v-else>
													<v-tooltip right>
														<div>{{$t('You cannot upload here until end of this month.')}}</div>
														<v-chip v-if="!checkExempted(props.item.store_id_real,9,'Oct')" slot="activator" small label color="white--text">
															{{$t('Not available')}}
														</v-chip>
														<v-chip v-else slot="activator" small label color="yellow white--text">
															{{$t('Exempted')}}
														</v-chip>
													</v-tooltip>				          			
					          		</div>

					          	</div>
				          		<v-tooltip v-else right>
				          			<span v-if="Date.parse(props.item.exp_Oct) <= Date.parse(new Date().toLocaleString()) && props.item.Oct_ispassed == 1">
				          				{{$t('Click to view more details.')}}.
				          			</span>
				          			<span v-else>
				          				{{$t('Click to view more details.')}}.
				          				<span class="subheading red--text text--lighten-4" v-if="props.item.Oct_percent < percentage_threshold && props.item.Oct_ispassed == 0">
				          					<br>
				          					<v-icon color="red">warning</v-icon> {{$t('Ratings threshold should be greater than')}} {{percentage_threshold}} % <br>
				          				</span>
				   								
				   								<div v-if="props.item.Oct_percent > percentage_threshold && props.item.Oct_ispassed == 0">
				   									{{$t('This means, this is passsed but there\'s requirements that you still need to change.')}}
				   								</div>
				   								<div v-else>
				          				</div>

				          				<div>
					          				{{$t('Remaining time to edit')}}: <Countdown  :deadline="props.item.exp_Oct"></Countdown>
					          				{{$t('You will not be allowed to edit upon expiration of editting period (Unless its a request from admin)')}} <br>
				          				</div>
				          				
				          			</span>
					          		<v-chip slot="activator" @click="storeAdditionalParam({
						          			store_id : props.item.store_id_real,
						          			year : props.item.year,
						          			store : props.item.store_id,
						          			month : 'Oct',
						          			fca_id : props.item.Oct_id, //fca_id in main table
						          			fca_exp_date : props.item.exp_Oct, // expiration date
						          			ratings: props.item.Oct_percent,
						          			remarks: props.item.Oct_remarks,
						          			audit_date: props.item.Oct_ad,
						          			audit_by: props.item.Oct_ad_by,
						          			isPassed: props.item.Oct_ispassed
						          		}); fca_dialog = true" label small  :color="(Date.parse(props.item.exp_Oct) <= Date.parse(new Date().toLocaleString()) && props.item.Oct_ispassed ==  1) || (props.item.Oct_percent < percentage_threshold && props.item.Oct_ispassed == 1) ? 'green accent-4 white--text' : 
					          				//else new condition
						          			props.item.Oct_percent < percentage_threshold ? 'red white--text' : 
						          			//else new condition
						          			props.item.Oct_percent > percentage_threshold && props.item.Oct_ispassed == 0 ? 'yellow darken-3 white--text' : 'blue accent-4 white--text'">
					          			{{props.item.Oct_percent}} %
					          		</v-chip>
				          		</v-tooltip>
				          		<v-progress-linear 
				          			v-if="props.item.Oct !== ''" 
				          			style="margin-top:0px; margin-bottom:0px;" 
				          			v-model="valueDeterminate = props.item.Oct_percent"
				          			:color="Date.parse(props.item.exp_Oct) <= Date.parse(new Date().toLocaleString()) || (Date.parse(props.item.exp_Oct) <= Date.parse(new Date().toLocaleString()) && props.item.Oct_ispassed ==  1) || (props.item.Oct_percent < percentage_threshold && props.item.Oct_ispassed == 1) ? 'green accent-4 white--text' : //else
				          				props.item.Oct_percent < percentage_threshold ? 'red white--text' : 
				          				//else ne wcondition 
				          				props.item.Oct_percent > percentage_threshold && props.item.Oct_ispassed == 0 ? 'yellow darken-3 white--text' : 'blue accent-4 white--text'
				          			"
				          		></v-progress-linear>
				          		<div v-if="props.item.Oct !== ''">
					          		<span style="color:red;" class="caption" v-if="!props.item.Oct_files">
					          			{{$t('No file')}}
					          		</span>
					          		<span v-else style="color:blue; font-size:10px;">
					          			{{$t('File(s) included')}}
					          		</span>
				          		</div>
									</td>
									<!-- end of october -->

									<!-- start of nov -->
									<td>
										<div v-if="Date.parse(props.item.exp_Nov) <= Date.parse(new Date().toLocaleString()) || (Date.parse(props.item.exp_Nov) <= Date.parse(new Date().toLocaleString()) && props.item.Nov_ispassed == 1)">
												<v-tooltip right>
													<div>{{$t('Click to view details.')}}</div>
													<v-chip slot="activator" label small color="green white--text" @click="storeAdditionalParam({
							          			store_id : props.item.store_id_real,
							          			year : props.item.year,
							          			store : props.item.store_id,
							          			month : 'Nov',
							          			fca_id : props.item.Nov_id, //fca_id in main table
							          			fca_exp_date : props.item.exp_Nov, // expiration date
							          			ratings: props.item.Nov_percent,
							          			remarks: props.item.Nov_remarks,
							          			audit_date: props.item.Nov_ad,
							          			audit_by: props.item.Nov_ad_by,
							          			isClosed: 1,
							          			isPassed: props.item.Nov_ispassed
							          		}); fca_dialog = true" >
														{{props.item.Nov_percent}} %
													</v-chip>
												</v-tooltip>
											</div>
											<div v-else-if="props.item.Nov === ''">
												<v-tooltip right v-if="
													checkExempted(props.item.store_id_real,10,'Nov') ? false : 
														(		10 == ((new Date().getMonth())) || (props.item.company_code == 1 ? 
														(  (daysIntoYear(new Date('2018,11,31')) + parseInt(hfi_items[10].value)) > getCurrentDayOfYear()  ) :
														(  (daysIntoYear(new Date('2018,11,31')) + parseInt(ofi_items[10].value)) > getCurrentDayOfYear()  )
													)) && (new Date()).getFullYear() == getYears">
													<div>{{$t('No file uploaded')}}.</div>
						          		<v-chip slot="activator" small @click="storeAdditionalParam({
						          			store_id : props.item.store_id_real,
						          			year : props.item.year,
						          			store : props.item.store_id,
						          			month : 'Nov',
						          			fca_id: '',
						          			fca_exp_date: null,
						          			ratings: props.item.Nov_percent,
						          			remarks: props.item.Nov_remarks,
						          			audit_date: props.item.Nov_ad,
						          			audit_by: props.item.Nov_ad_by,
						          			isPassed: props.item.Nov_ispassed
						          		}); fca_dialog = true" color="grey lighten-1 white--text" style="cursor:pointer;" label>{{$t('Empty')}}</v-chip>
						          		<span color="grey caption"></span>
					          		</v-tooltip>
					          		<div v-else>
													<v-tooltip right>
														<div>{{$t('You cannot upload here until end of this month.')}}</div>
														<v-chip v-if="!checkExempted(props.item.store_id_real,10,'Nov')" slot="activator" small label color="white--text">
															{{$t('Not available')}}
														</v-chip>
														<v-chip v-else slot="activator" small label color="yellow white--text">
															{{$t('Exempted')}}
														</v-chip>
													</v-tooltip>				          			
					          		</div>

					          	</div>
				          		<v-tooltip v-else right>
				          			<span v-if="Date.parse(props.item.exp_Nov) <= Date.parse(new Date().toLocaleString()) && props.item.Nov_ispassed == 1">
				          				{{$t('Click to view more details.')}}.
				          			</span>
				          			<span v-else>
				          				{{$t('Click to view more details.')}}.
				          				<span class="subheading red--text text--lighten-4" v-if="props.item.Nov_percent < percentage_threshold && props.item.Nov_ispassed == 0">
				          					<br>
				          					<v-icon color="red">warning</v-icon> {{$t('Ratings threshold should be greater than')}} {{percentage_threshold}} % <br>
				          				</span>
				   								
				   								<div v-if="props.item.Nov_percent > percentage_threshold && props.item.Nov_ispassed == 0">
				   									{{$t('This means, this is passsed but there\'s requirements that you still need to change.')}}
				   								</div>
				   								<div v-else>
				          				</div>

				          				<div>
					          				{{$t('Remaining time to edit')}}: <Countdown  :deadline="props.item.exp_Nov"></Countdown>
					          				{{$t('You will not be allowed to edit upon expiration of editting period (Unless its a request from admin)')}} <br>
				          				</div>
				          				
				          			</span>
					          		<v-chip slot="activator" @click="storeAdditionalParam({
						          			store_id : props.item.store_id_real,
						          			year : props.item.year,
						          			store : props.item.store_id,
						          			month : 'Nov',
						          			fca_id : props.item.Nov_id, //fca_id in main table
						          			fca_exp_date : props.item.exp_Nov, // expiration date
						          			ratings: props.item.Nov_percent,
						          			remarks: props.item.Nov_remarks,
						          			audit_date: props.item.Nov_ad,
						          			audit_by: props.item.Nov_ad_by,
						          			isPassed: props.item.Nov_ispassed
						          		}); fca_dialog = true" label small  :color="(Date.parse(props.item.exp_Nov) <= Date.parse(new Date().toLocaleString()) && props.item.Nov_ispassed ==  1) || (props.item.Nov_percent < percentage_threshold && props.item.Nov_ispassed == 1) ? 'green accent-4 white--text' : 
					          				//else new condition
						          			props.item.Nov_percent < percentage_threshold ? 'red white--text' : 
						          			//else new condition
						          			props.item.Nov_percent > percentage_threshold && props.item.Nov_ispassed == 0 ? 'yellow darken-3 white--text' : 'blue accent-4 white--text'">
					          			{{props.item.Nov_percent}} %
					          		</v-chip>
				          		</v-tooltip>
				          		<v-progress-linear 
				          			v-if="props.item.Nov !== ''" 
				          			style="margin-top:0px; margin-bottom:0px;" 
				          			v-model="valueDeterminate = props.item.Nov_percent"
				          			:color="Date.parse(props.item.exp_Nov) <= Date.parse(new Date().toLocaleString()) || (Date.parse(props.item.exp_Nov) <= Date.parse(new Date().toLocaleString()) && props.item.Nov_ispassed ==  1) || (props.item.Nov_percent < percentage_threshold && props.item.Nov_ispassed == 1) ? 'green accent-4 white--text' : //else
				          				props.item.Nov_percent < percentage_threshold ? 'red white--text' : 
				          				//else ne wcondition 
				          				props.item.Nov_percent > percentage_threshold && props.item.Nov_ispassed == 0 ? 'yellow darken-3 white--text' : 'blue accent-4 white--text'
				          			"
				          		></v-progress-linear>
				          		<div v-if="props.item.Nov !== ''">
					          		<span style="color:red;" class="caption" v-if="!props.item.Nov_files">
					          			{{$t('No file')}}
					          		</span>
					          		<span v-else style="color:blue; font-size:10px;">
					          			{{$t('File(s) included')}}
					          		</span>
				          		</div>
									</td>
									<!-- end of nov -->

									<!-- start of dec -->
									<td>
										<div v-if="Date.parse(props.item.exp_Dec) <= Date.parse(new Date().toLocaleString()) || (Date.parse(props.item.exp_Dec) <= Date.parse(new Date().toLocaleString()) && props.item.Dec_ispassed == 1)">
												<v-tooltip right>
													<div>{{$t('Click to view details.')}}</div>
													<v-chip slot="activator" label small color="green white--text" @click="storeAdditionalParam({
							          			store_id : props.item.store_id_real,
							          			year : props.item.year,
							          			store : props.item.store_id,
							          			month : 'Dec',
							          			fca_id : props.item.Dec_id, //fca_id in main table
							          			fca_exp_date : props.item.exp_Dec, // expiration date
							          			ratings: props.item.Dec_percent,
							          			remarks: props.item.Dec_remarks,
							          			audit_date: props.item.Dec_ad,
							          			audit_by: props.item.Dec_ad_by,
							          			isClosed: 1,
							          			isPassed: props.item.Dec_ispassed
							          		}); fca_dialog = true" >
														{{props.item.Dec_percent}} %
													</v-chip>
												</v-tooltip>
											</div>
											<div v-else-if="props.item.Dec === ''">
												<v-tooltip right v-if="
													checkExempted(props.item.store_id_real,11,'Dec') ? false : 
														(		11 == ((new Date().getMonth())) || (props.item.company_code == 1 ? 
														(  (daysIntoYear(new Date('2018,12,31')) + parseInt(hfi_items[11].value)) > getCurrentDayOfYear()  ) :
														(  (daysIntoYear(new Date('2018,12,31')) + parseInt(ofi_items[11].value)) > getCurrentDayOfYear()  )
													)) && (new Date()).getFullYear() == getYears">
													<div>{{$t('No file uploaded')}}.</div>
						          		<v-chip slot="activator" small @click="storeAdditionalParam({
						          			store_id : props.item.store_id_real,
						          			year : props.item.year,
						          			store : props.item.store_id,
						          			month : 'Dec',
						          			fca_id: '',
						          			fca_exp_date: null,
						          			ratings: props.item.Dec_percent,
						          			remarks: props.item.Dec_remarks,
						          			audit_date: props.item.Dec_ad,
						          			audit_by: props.item.Dec_ad_by,
						          			isPassed: props.item.Dec_ispassed
						          		}); fca_dialog = true" color="grey lighten-1 white--text" style="cursor:pointer;" label>{{$t('Empty')}}</v-chip>
						          		<span color="grey caption"></span>
					          		</v-tooltip>
					          		<div v-else>
													<v-tooltip right>
														<div>{{$t('You cannot upload here until end of this month.')}}</div>
														<v-chip v-if="!checkExempted(props.item.store_id_real,11,'Dec')" slot="activator" small label color="white--text">
															{{$t('Not available')}}
														</v-chip>
														<v-chip v-else slot="activator" small label color="yellow white--text">
															{{$t('Exempted')}}
														</v-chip>
													</v-tooltip>				          			
					          		</div>

					          	</div>
				          		<v-tooltip v-else right>
				          			<span v-if="Date.parse(props.item.exp_Dec) <= Date.parse(new Date().toLocaleString()) && props.item.Dec_ispassed == 1">
				          				{{$t('Click to view more details.')}}.
				          			</span>
				          			<span v-else>
				          				{{$t('Click to view more details.')}}.
				          				<span class="subheading red--text text--lighten-4" v-if="props.item.Dec_percent < percentage_threshold && props.item.Dec_ispassed == 0">
				          					<br>
				          					<v-icon color="red">warning</v-icon> {{$t('Ratings threshold should be greater than')}} {{percentage_threshold}} % <br>
				          				</span>
				   								
				   								<div v-if="props.item.Dec_percent > percentage_threshold && props.item.Dec_ispassed == 0">
				   									{{$t('This means, this is passsed but there\'s requirements that you still need to change.')}}
				   								</div>
				   								<div v-else>
				          					
				          				</div>
				          				<div >
					          				{{$t('Remaining time to edit')}}: <Countdown  :deadline="props.item.exp_Dec"></Countdown>
					          				{{$t('You will not be allowed to edit upon expiration of editting period (Unless its a request from admin)')}} <br>
				          				</div>
				          				
				          			</span>
					          		<v-chip slot="activator" @click="storeAdditionalParam({
						          			store_id : props.item.store_id_real,
						          			year : props.item.year,
						          			store : props.item.store_id,
						          			month : 'Dec',
						          			fca_id : props.item.Dec_id, //fca_id in main table
						          			fca_exp_date : props.item.exp_Dec, // expiration date
						          			ratings: props.item.Dec_percent,
						          			remarks: props.item.Dec_remarks,
						          			audit_date: props.item.Dec_ad,
						          			audit_by: props.item.Dec_ad_by,
						          			isPassed: props.item.Dec_ispassed
						          		}); fca_dialog = true" label small  :color="(Date.parse(props.item.exp_Dec) <= Date.parse(new Date().toLocaleString()) && props.item.Dec_ispassed ==  1) || (props.item.Dec_percent < percentage_threshold && props.item.Dec_ispassed == 1) ? 'green accent-4 white--text' : 
					          				//else new condition
						          			props.item.Dec_percent < percentage_threshold ? 'red white--text' : 
						          			//else new condition
						          			props.item.Dec_percent > percentage_threshold && props.item.Dec_ispassed == 0 ? 'yellow darken-3 white--text' : 'blue accent-4 white--text'">
					          			{{props.item.Dec_percent}} %
					          		</v-chip>
				          		</v-tooltip>
				          		<v-progress-linear 
				          			v-if="props.item.Dec !== ''" 
				          			style="margin-top:0px; margin-bottom:0px;" 
				          			v-model="valueDeterminate = props.item.Dec_percent"
				          			:color="Date.parse(props.item.exp_Dec) <= Date.parse(new Date().toLocaleString()) || (Date.parse(props.item.exp_Dec) <= Date.parse(new Date().toLocaleString()) && props.item.Dec_ispassed ==  1) || (props.item.Dec_percent < percentage_threshold && props.item.Dec_ispassed == 1) ? 'green accent-4 white--text' : //else
				          				props.item.Dec_percent < percentage_threshold ? 'red white--text' : 
				          				//else ne wcondition 
				          				props.item.Dec_percent > percentage_threshold && props.item.Dec_ispassed == 0 ? 'yellow darken-3 white--text' : 'blue accent-4 white--text'
				          			"
				          		></v-progress-linear>
				          		<div v-if="props.item.Dec !== ''">
					          		<span style="color:red;" class="caption" v-if="!props.item.Dec_files">
					          			{{$t('No file')}}
					          		</span>
					          		<span v-else style="color:blue; font-size:10px;">
					          			{{$t('File(s) included')}}
					          		</span>
				          		</div>
									</td>
									<!-- end of dec -->

								</tr>
							</template>
						</v-data-table>

					</v-card>
					<blockquote>
						{{$t('Click the empty field to submit report.')}} 
					</blockquote>
					<blockquote>
						{{$t('Note')}}: {{$t('There is a threshold settings for ratings. If ratings did not reach the minimum passing rate you can still modify/re-upload it.')}} 
					</blockquote>
				</v-flex>
				<v-flex xs12 sm3 sm3>
					<v-card>
						<v-card-title class="subheading">{{$t('Comments')}}/{{$t('Feedback')}}</v-card-title>
						<v-divider></v-divider>
							<v-dialog scrollable v-model="dialog_comment_section" max-width="650">
				      <v-card>
				        <v-card-title class="title d-flex-inline">
				        	<div>
				        	[{{comment_store}}] : {{comment_remarks}} 
				        	</div> 
				        	<br>
				        	<div class="caption" style="position: absolute; left: 0; padding-left: 15px; margin-top:20px">
					        	{{$t('Sender')}} : {{comment_name_sender}}
				        	</div>
				        	<v-spacer></v-spacer>
				        	<v-btn fab small color="red white--text" @click="dialog_comment_section = false">x</v-btn>
				        </v-card-title>
				        <v-divider></v-divider>
				        <v-card-text style="display:flex; flex-direction: column-reverse;" ref="setThistoBottom">
					       	<v-list three-line>
					          <template v-for="(item, index) in items_comment_section">
					            <v-divider v-if="index != 0" :key="index"></v-divider>
					            <v-list-tile avatar :key="item.title" @click="">
					              <v-list-tile-avatar style="">
					              	<v-avatar class="red white--text" :size="36">B</v-avatar>
					              </v-list-tile-avatar>
					              <v-list-tile-content>
					                <v-list-tile-title v-html="item.name"></v-list-tile-title>
					                <v-list-tile-sub-title v-html="item.fca_comment"></v-list-tile-sub-title>
					              </v-list-tile-content>
					              <v-list-tile-action>
					              	<v-list-tile-action-text>
<!-- 					              		<v-tooltip left>
					              			<span>Remaining time to remove </span>
					              			<v-btn slot="activator" fab small icon>X</v-btn>
					              		</v-tooltip> -->
					              	</v-list-tile-action-text>
					                <v-list-tile-action-text>{{$gs.date_replace(item.created_at)}}</v-list-tile-action-text> 
					           
					              </v-list-tile-action>
					            </v-list-tile>
					          </template>
				        	</v-list>
			        	</v-card-text>
				        <v-divider></v-divider>
				        <v-alert color="error" dismissible style="width:100%;" v-model="showReplyErrorMsg">
			        		{{$t('Please enter your text here.')}}
			        	</v-alert>
				        <v-card-actions>
				        	<v-text-field
				        		textarea
				        		:label="$t('Enter reply here.')"
				        		v-model="txt_reply"
				        		:hint="$t('Cannot be edited once it sent.')"
				        		persistent-hint
				        	>
				        	</v-text-field>
				        </v-card-actions>
				        <v-divider></v-divider>
				        <v-card-actions>
				          <v-flex class="text-xs-center">
				          	<div v-if="showSuccessReply">
				          		
				          	</div>
				          	<v-btn color="red accent-4 white--text" :loading="btn_reply_load" @click.native="submitReply();">{{$t('Submit')}}<v-icon>send</v-icon> </v-btn>
				       		</v-flex>
				        </v-card-actions>
				      </v-card>
				    </v-dialog>
						<!-- displaying comments here -->
						<v-list two>
							<v-list two-line>
			          <template v-for="(item, index) in comments_item">
			            <v-list-tile
			              avatar
			              ripple
			              @click="viewComments({
			              	store: item.store,
			              	remarks : item.remarks,
			              	name_sender: item.name_sender,
			              	fca_comments : item.fca_comments,
			              	audit_date: item.audit_date,
			              	fca_id: item.fca_id
			              }); dialog_comment_section = true"
			              :key="item.title"
			            >
			              <v-list-tile-content>
			                <v-list-tile-title>
			                	<span><b>[{{ item.store }}]:  {{item.remarks}} s</b></span>
			                </v-list-tile-title>
			                <v-list-tile-sub-title class="text--primary">{{ item.name_sender }}</v-list-tile-sub-title>
			                <v-list-tile-sub-title>Re: {{ item.fca_comments }}</v-list-tile-sub-title>
			              </v-list-tile-content>
			              <v-list-tile-action>
			                <v-list-tile-action-text>{{ $gs.date_replace(item.audit_date) }}</v-list-tile-action-text> 
			                <v-icon 
			                	v-if="item.isRead > 0"
			                >notifications</v-icon>
			                <v-icon
			                  color="yellow darken-2"
			                  v-else
			                >notifications_active</v-icon>
			              </v-list-tile-action>
			            </v-list-tile>
			            <v-divider v-if="index + 1 < items.length" :key="index"></v-divider>
			          </template>
			        </v-list>
						</v-list>

					</v-card>
				</v-flex>
			</v-layout>
		</v-container>
	</div>
</template>
<script>
	import vue2Dropzone from 'vue2-dropzone'
	import 'vue2-dropzone/dist/vue2Dropzone.css'
	import Countdown from 'vuejs-countdown'

	// Import Vue FilePond
	import FilePond, { registerPlugin } from 'vue-filepond';

	// Import FilePond styles
	import 'filepond/dist/filepond.min.css';

	// Register file type validation plugin
	import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
	registerPlugin(FilePondPluginFileValidateType);

	export default{
		components: {
	    vueDropzone: vue2Dropzone,
	    FilePond,
	    Countdown
	  },
		data:()=>({
			items: [],
			disabledBtnUntil: true,
			headers:[
				{
					text: (localStorage.getItem('flang') === 'fr' ? 'Restaurant' : 'Stores'),
					align:'left',
					sortable: true,
					value:'store_id'
				},
				{
					text: (localStorage.getItem('flang') === 'fr' ? 'Janvier' : 'Jan'),
					align: 'center',
					sortable: false
				},
				{
					text: (localStorage.getItem('flang') === 'fr' ? 'Février' : 'Feb'),
					align: 'center',
					sortable: false
				},
				{
					text: (localStorage.getItem('flang') === 'fr' ? 'Mars' : 'March'),
					align: 'center',
					sortable: false
				},
				{
					text: (localStorage.getItem('flang') === 'fr' ? 'Avril' : 'April'),
					align: 'center',
					sortable: false
				},
				{
					text: (localStorage.getItem('flang') === 'fr' ? 'Peut' : 'May'),
					align: 'center',
					sortable: false
				},
				{
					text: (localStorage.getItem('flang') === 'fr' ? 'Juin' : 'June'),
					align: 'center',
					sortable: false
				},
				{
					text: (localStorage.getItem('flang') === 'fr' ? 'Juillet' : 'July'),
					align: 'center',
					sortable: false
				},
				{
					text: (localStorage.getItem('flang') === 'fr' ? 'Août' : 'August'),
					align: 'center',
					sortable: false
				},
				{
					text: (localStorage.getItem('flang') === 'fr' ? 'Septembre' : 'September'),
					align: 'center',
					sortable: false
				},
				{
					text: (localStorage.getItem('flang') === 'fr' ? 'Octobre' : 'October'),
					align: 'center',
					sortable: false
				},
				{
					text: (localStorage.getItem('flang') === 'fr' ? 'Novembre' : 'November'),
					align: 'center',
					sortable: false
				},
				{
					text: (localStorage.getItem('flang') === 'fr' ? 'Décembre' : 'December'),
					align: 'center',
					sortable: false
				}
			],
			header_files:[
				{
					text:(localStorage.getItem('flang') === 'fr' ? 'Nom de fichier' : 'File name'),
					align: 'left',
					sortable:true,
					value: 'original_file_name'
				},
				{
					text:(localStorage.getItem('flang') === 'fr' ? 'La taille du fichier' : 'File size'),
					align: 'left',
					sortable:true,
					value: 'size'
				},
				{
					text: (localStorage.getItem('flang') === 'fr' ? 'L\'extension de fichier' : 'File extension'),
					align: 'left',
					sortable:true,
					value: 'ext'
				},
				{
					text:'Action',
					align: 'left',
					sortable: false,
					value: 'original_file_name'
				},
			],
			search: '',
			pagination:{
				page:1,
				descending: false
			},
			loading:true,
			totalItems: 0,
			years:[],
			getYears: null,
			stores: [],
			v_stores: '',
			fav: true,
      menu: false,
      message: false,
      hints: true,
      year_toupload: [],
      v_year_toupload: null,
      alert_addYear: false,
      alert_msg: '',
      alert_color: 'error',
      current_store: '',
      options:{},
      attrs:{
      	accept:'*'
      },
      editDialog: false,
      store_id: null,
      store_name: null,
      year: null,
      month: null,
      d_fca_id: null,
      item_files_loading: true,
      dmsg: null,
      dropzoneOptions: {
        url: '/ktool/api/uploadFcaFile',
        thumbnailWidth: 50,
        thumbnailHeight: 0,
        addRemoveLinks: true,
        createImageThumbnails: false,
        uploadMultiple: true,
        maxFilesize:25,
        parallelUploads: 25,
        autoProcessQueue: false,
        dictDefaultMessage: ( localStorage.getItem('flang') === 'fr' ?
        		`<br>Déposez ou recherchez à télécharger le fichier.<span style="color:red">(Requis)</span>`
          : `<br>Drop or browse to upload file..<span style="color:red">(Required)</span>`),
        headers:{
        	'Authorization' : 'Bearer ' + JSON.parse(window.localStorage.getItem('ktool_token')).access_token
        },
        params:{
        	store_id: this.store_id,
        	store_name: this.store_name,
        	year: this.year,
        	month: this.month,
        	fca_id: this.d_fca_id
        },
        init: function(){
        	this.on('maxfilesexceeded', function(file){
        		alert('You cannot upload multiple files at the same time.')
        	})
        }
      },
      v_audit_date: false,
			audt_date :null,
			myFiles: [],
			fca_dialog: false,
			audit_by: null,

			additionalData: {
				data1:'shit'
			},
			valueDeterminate: 0,
			current_expiration: null,
			fca_alert_color : null,
			fca_alert : false,
			fca_alert_msg : 'Please provide details below',
			v_ratings: null,
			v_remarks: null,
			item_files: [],
			removeLink: true,
			disabled_field: true,
			percentage_threshold: null,
			loadBtn: true,
			isShowThis: true,
			dialog_comment_section: false,
			comment_store: null,
			comment_remarks: null, 
			comment_name_sender: null,
			comment_fca_comments: null,
			comment_audit_date: null,
			comment_fca_id: null,
			items_comment_section: [],
			showReplyErrorMsg: null,
			txt_reply: null,
			showSuccessReply: null,
			btn_reply_load: false,
			comments_item: [],
			loadExport: false,
			isClosed: null,
			show_details: false,
			prev_score: [],
			hfi_items:[],
			ofi_items:[],
			exempted_stores:[]
		}),
		watch:{
			loadBtn:function(){
				console.log('test')
			}
		},
		computed:{

		},
		methods:{
			checkExempted:function(store_id,month_num,month_name){
				var status = false;
				//console.log(this.exempted_stores)
				for(var obj in this.exempted_stores){
					if(
						store_id == this.exempted_stores[obj].store_id &&
						month_num == this.exempted_stores[obj].month_num &&
						month_name == this.exempted_stores[obj].month_txt
					){
						status = true;
					}
				}
				return status;
			},
			changeAuditBy(){
				this.disabledBtnUntil =false
			},	
			hasChangedDate(){
				this.disabledBtnUntil = false;
			},
			changeRemarks(){
				this.disabledBtnUntil = false;
			},
			onSuccessAdded(file){
				this.loadBtn = false,
				this.disabledBtnUntil = false;
			},
			onUploadCompleted(files){
				if(this.d_fca_id){
					const obj = {
						fca_id: this.d_fca_id
					}
					this.$store.dispatch('GET_FILES_UPLOADED',obj)
						.then(response=>{
							this.item_files = response.data
							this.item_files_loading = false;
							this.getTableApi(this.getYears)
						})
						.catch(error=>{
							this.item_files_loading = false;
						})
				}
				this.$refs.myVueDropzone.removeAllFiles(true);
			},
			exportFca(){
				this.loadExport = true;
				const obj = {
					currentYear: this.getYears
				}
				if(!this.getYears){
					alert('Please select year');
					return false;
				}

				try{
					axios({
						url: '/ktool/api/exportFca',
						method:'post',
						data: obj,
						config:'JSON'
					})
					.then(response=>{
						if(response.status == 200){
							var a = document.createElement('a')
							a.href = '/ktool/' + response.data.exportedData.full
							a.download = response.data.exportedData.file
							document.body.appendChild(a)
							a.click()
							a.remove()
							this.loading = false
						}
						this.loadExport = false
					})
					.catch(error=>{
						console.log(error)
						this.loadExport = false
					})
				}catch(error){
					this.loadExport = false
				}
			},
			submitReply(){
				this.btn_reply_load = true;
				this.showReplyErrorMsg = false
				const self = this
				const obj = {
					fca_id : this.comment_fca_id,
					store: this.comment_store,
					remarks: this.comment_remarks,
					sender: this.comment_name_sender,
					audit_date : this.comment_audit_date,
					txt_reply: this.txt_reply
				}
				if(!this.txt_reply){
					this.showReplyErrorMsg = true
					this.btn_reply_load  = false
					return false;
				}

				/*send a reply*/
				try{
					axios({
						url:'/ktool/api/replyMessage',
						method:'post',
						data:obj,
						config: 'JSON'
					})
					.then(response=>{
						if(response.status == 200){
							self.btn_reply_load = false
							this.txt_reply = ''
						}
					})
					.catch(error=>{
						console.log(error);
						self.btn_reply_load = false;
					})
				}catch(error){
					console.log(error)
					self.btn_reply_load = false
				}

				const fca = {
					fca_id: this.comment_fca_id
				}
				//if reply success get the files
				try{
					axios({
						url:'/ktool/api/readComments',
						method:'post',
						data: fca,
						config:'JSON'
					})
					.then(response=>{
						if(response.status == 200){
							this.items_comment_section = response.data.data
							if(response.data.status){
								this.getFeedbacks()
							}
						}
					})
					.catch(error=>{
						console.log(error)
					})
				}catch(error){
					console.log(error)
				}
			},
			viewComments(data){
				this.comment_store = data.store
				this.comment_remarks = data.remarks
				this.comment_name_sender = data.name_sender
				this.comment_fca_comments = data.fca_comments
				this.comment_audit_date = data.audit_date
				this.comment_fca_id = data.fca_id
				const obj = {
					fca_id: data.fca_id
				}
				try{
					axios({
						url:'/ktool/api/readComments',
						method:'post',
						data:obj,
						config:'JSON'
					})
					.then(response=>{
						if(response.status == 200){
							this.items_comment_section = response.data.data
							if(response.data.status){
								this.getFeedbacks()
							}
						}
					})
					.catch(error=>{
						console.log(error)
					})
				}catch(error){
					console.log(error)
				}
			},
			getFeedbacks(){
				this.$store.dispatch('getFeedBacks')
					.then(response=>{
						this.comments_item = response.data
					})
					.catch(error=>{
						console.log(error);
					})
			},
			downloadFile(item,index){
				console.log(index);
				// lets check in the server if this file exists
				axios.get('/ktool/api/downloadFile/'+index.original_file_name+'/'+index.file_name)
					.then(response=>{
						console.log(index.original_file_name)
						if(response.status === 200){
							if(response.data.data === 'success'){	
								var a = document.createElement("a")
								a.href="/ktool/public/storage/fca_files/"+response.data.f_name
								a.download=index.original_file_name
								document.body.appendChild(a)
								a.click()
								a.remove()
							}
						}
					})
					.catch(error=>{
						console.log(error);
					})
				
			},
			removeFile:function($query){
				//console.log($query);
			},
			removeThisFile(file, error, xhr){
				const data = {
					orig_file_name : file.name
				}
				this.disabledBtnUntil = true;
			},
			onSuccessUpload(file,response){
				if(this.d_fca_id){
					const obj = {
						fca_id: this.d_fca_id
					}
					this.$store.dispatch('GET_FILES_UPLOADED',obj)
						.then(response=>{
							this.item_files = response.data
							this.item_files_loading = false;
							this.getTableApi(this.getYears)
						})
						.catch(error=>{
							this.item_files_loading = false;
						})
				}
				this.$refs.myVueDropzone.removeAllFiles(true);
			},
			handleFilePondInit:function(){
				//console.log('file has initialized')
				let test = this.$refs.pond.getFiles();
				//console.log(test);
			},
			deleteFile(item,index){
				const obj = {
					file_id : item
				}

				if(this.item_files.length == 1){
					this.fca_alert = true;
					this.fca_alert_msg = 'You cannot delete the remaining file. To replace, please upload another file to delete this.'
					this.fca_alert_color = 'error'
					this.loadBtn = false
					return false;	
				}

				const i = this.item_files.indexOf(index)
				if(confirm('Are you sure want to remove this file?')){
					this.$store.dispatch('REMOVE_FILE',obj)
						.then(response=>{
							//console.log(response);
							if(response.data === 'success'){
								this.item_files.splice(i, 1)
								this.getTableApi()
							}
						})
						.catch(error=>{
							console.log(error);
						})
				}
			},
			storeAdditionalParam:function(data){
				const self = this
				this.disabled_field = true;

				//console.log(data)
				if(data.isClosed == 1){
					this.disabled_field = true
				}else{
					/*check if this is expired*/
					if(Date.parse(data.fca_exp_date) <= Date.parse(new Date().toLocaleString()) && data.isReeval != 1){
						//true
						this.disabled_field = true
					}else{

						this.disabled_field = false
					}
				}


				this.item_files_loading = false
				this.dropzoneOptions.params.store_id = data.store_id
				this.dropzoneOptions.params.store_name = data.store
				this.dropzoneOptions.params.year = data.year
				this.dropzoneOptions.params.month = data.month
				this.dropzoneOptions.params.fca_id = data.fca_id

				this.current_expiration = data.fca_exp_date
				this.d_fca_id = data.fca_id

				//self.dropzoneOptions.dictDefaultMessage = 'Please enter details above before uploading.'
					const obj = {
						fca_id: data.fca_id
					}
					this.$store.dispatch('GET_FILES_UPLOADED',obj)
						.then(response=>{
							this.item_files = response.data
							this.item_files_loading = false;
							this.loadBtn = false
							this.disabled_field = false;
						})
						.catch(error=>{
							this.item_files_loading = false;
							this.disabled_field = false;
						})


				//lets get the previosu data
				this.getPreviousScore(obj)
				

				this.v_ratings = data.ratings
				this.v_remarks = data.remarks
				this.audt_date = data.audit_date

				this.audit_by = data.audit_by

				this.store_id = data.store_id;
				this.store_name = data.store,
				this.year = data.year
				this.month = data.month
			},
			/*save the report*/
			sendAudit:function(val){
				//console.log('test')
			},
			oc_year:function(val){
				this.getTableApi(val)
			},
			oc_stores:function(val){
				this.current_store = val.text
			},
			saveOtherDetails:function(){
				const self = this
				this.fca_alert = false;
				this.loadBtn = true;

				var count = this.$refs.myVueDropzone.dropzone.files.length
				if(this.item_files.length > 0){ // if there is a file we don't need to check

				}else if(count < 1 | count == 0){
					this.fca_alert = true;
					this.fca_alert_msg = 'Please submit a file.'
					this.fca_alert_color = 'error'
					this.loadBtn = false
					return false;
				}

				if(!this.v_ratings){
					this.fca_alert = true;
					this.fca_alert_msg = 'Ratings is required'
					this.fca_alert_color = 'error'
					this.loadBtn = false
					return false;
				}

				if(!this.audit_by){
					this.fca_alert = true;
					this.fca_alert_msg = 'Please provide the auditor of this report.'
					this.fca_alert_color = 'error'
					this.loadBtn = false
					return false;
				}

				if(!this.audt_date){
					this.fca_alert = true;
					this.fca_alert_msg = 'Audit date is required.'
					this.fca_alert_color = 'error'
					this.loadBtn = false
					return false;
				}
 
				if(this.v_ratings > 100 || this.v_ratings < 1){
					this.fca_alert = true;
					this.fca_alert_msg = 'Ratings can only be 1 to 100.'
					this.fca_alert_color = 'error'
					this.loadBtn = false
					return false;
				}

				const data = {
					ratings: this.v_ratings,
					audit_date: this.audt_date,
					audit_by: this.audit_by,
					remarks: this.v_remarks,
					fca_id: this.d_fca_id,
					store_id: this.store_id,
					store_name: this.store_name,
					year: this.year,
					month: this.month
				}
				// /console.log(data);
				//return false;

				this.$store.dispatch('STORE_OTHER_DETAILS',data)
					.then(response=>{
						if(response.data === 'success'){
							this.fca_alert = true;
							this.fca_alert_msg = 'Changes successfully saved.'
							this.fca_alert_color = 'success'
							this.d_fca_id = response.fca_id;
							this.loadBtn = false;
							this.$refs.myVueDropzone.setupEventListeners();
							this.$refs.myVueDropzone.processQueue();
							this.getTableApi(this.year);
							const obj_1 = {
								fca_id: this.d_fca_id
							}
							this.getPreviousScore(obj_1);

							setTimeout(()=>{
								this.fca_alert = false
							},5000)
						}else{
							alert('STORE_OTHER_DETAILS; Something went wrong');
							self.fca_alert = false;
							self.loadBtn = false;
							//return false;
						}
					})
					.catch(error=>{
						alert(error);
						self.loadBtn = false;
					})
					.then(()=>{
						self.loadBtn = false
					})
			},
			getPreviousScore(obj){
				try{
					axios({
						url: '/ktool/api/getPreviousScore',
						method:'POST',
						data: obj,
						config:'JSON'
					})
					.then(response=>{
						if(response.status == 200){
							this.prev_score = response.data.data
						}
					})
					.catch(error=>{
						console.log(error)
					})
				}catch(err){
					console.log(err)
				}
			},
			getStores(){
				this.$store.dispatch('GET_STORES')
					.then(response=>{
						this.stores = response
						this.v_stores = response[0]
						this.current_store = response[0].text
					})
					.catch(error=>{
						console.log(error)
					})
			},
			saveAddYear(){
				if(this.v_year_toupload === null ){
					this.alert_addYear = true
					this.alert_msg = 'Please select year'
					const self = this
					setTimeout(()=>{
						self.alert_addYear = false
					},3000)
					return false
				}
				const data ={ 
					year: this.v_year_toupload,
					store_id: this.v_stores.value,
					store_name: this.v_stores.text
				}
			
				this.$store.dispatch('ADD_YEAR',data)
					.then(response=>{
						if(response.status === true){
							getTableApi()
						}
					})
					.catch(error=>{
						this.alert_addYear = true;
						this.alert_msg = 'Something wen\'t happened'
						console.log(error)
					})
			},
			getTableApi(year = (new Date()).getFullYear()){
				this.loading = true
				if(this.getYears){
					year = this.getYears
				}
				const data ={ 
					year: year,
					store_id: this.v_stores.value,
					store_name: this.v_stores.text
				}

				//console.log(data)
				this.$store.dispatch('GET_FCA_API',data)
					.then(response=>{
						this.items = response.data

						if(response.hfi_items.length <= 0){
							this.hfi_items = [
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
						}else{
							this.hfi_items = response.hfi_items
						}

						if(response.ofi_item.length <= 0){
							this.ofi_items = [
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
															]
						}else{
							this.ofi_items = response.ofi_item
						}

						
						

						this.loading = false;
						this.percentage_threshold = response.threshold_percent
					})
					.catch(error=>{
						console.log(error)
						this.loading = false;
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
	    sp_keyup:function(value, min, max){
	    	this.disabledBtnUntil = false;
	    	if(parseInt(value) < min || isNaN(value)) 
		        return 0; 
		    else if(parseInt(value) > max) 
		        return 100;
		    else return value;

	    },
	    handleChange:function(input){
	    	if (input < 0) input = 0;
   			if (input > 100) input = 100;
   			this.disabledBtnUntil = false;
	    },
	   	getCurrentDayOfYear(){
	   		var now = new Date();
				var start = new Date(now.getFullYear(), 0, 0);
				var diff = now - start;
				var oneDay = 1000 * 60 * 60 * 24;
				var day = Math.floor(diff / oneDay);

				return day
	   	},
	   	daysIntoYear(date){
			    return (Date.UTC(date.getFullYear(), date.getMonth(), date.getDate()) - Date.UTC(date.getFullYear(), 0, 0)) / 24 / 60 / 60 / 1000;
			},
	   	getCurrentDayPerMonth(date){
	   		let days;
	   		var fixedDate = '2018,04,31'
	   		
	   		/*[new Date('2018,04,31')]
			    .forEach(d =>  
			        console.log(` ${daysIntoYear(d)} days into the year`)
				);*/
	   		return days
			 },
			getExemptedStore(){
				const obj ={
					url:"/ktool/api/getExemptStore",
					method:'POST',
					data:{

					},
					config:'JSON'
				}

				this.$store.dispatch('COMMIT_ACTION_NO_FILE',obj)
					.then(response=>{
						this.exempted_stores = response.data
					})
					.catch(error=>{
						console.log(error)
					})
			}
		},
		created(){
			this.getStores();
			//this.getTableApi();
			this.getFeedbacks();
			this.getExemptedStore();
			//console.log(this.getCurrentDayPerMonth('2018,03,31'))


		},	
		mounted(){
			var years = [];
      var currentYear = new Date().getFullYear(), years = [];
      var startYear = startYear || 2010;

      while ( startYear <= currentYear ) {
              years.push(startYear++);
      } 
      this.years = years.reverse()
      this.year_toupload = years
      this.getYears = years[0]
		}
	}
</script>
<style scoped>
	div>>>.datatable__actions {
		justify-content: center;
	}
	.uploader-example {
    width: 400px;
    padding: 15px;
    margin: 40px auto 0;
    font-size: 12px;
    box-shadow: 0 0 10px rgba(0, 0, 0, .4);
  }
  .uploader-example .uploader-btn {
    margin-right: 4px;
  }
  .uploader-example .uploader-list {
    max-height: 440px;
    overflow: auto;
    overflow-x: hidden;
    overflow-y: auto;
  }
  div>>>.chip__content{
  	cursor: pointer;
  }

  blockquote{
  	font-size: 1em;
	  width:auto;
	  margin:10px;
	  font-family:Open Sans;
	  font-style:italic;
	  color: #555555;
	  padding:1em 20px 1.2em 20px;
	  border-left:8px solid #F44336 ;
	  line-height:1.6;
	  position: relative;
	  background:#EDEDED;
	}

	blockquote::before{
	  font-family:Arial;
	  content: "";
	  color:#78C0A8;
	  font-size:4em;
	  position: absolute;
	  left: 10px;
	  top:-10px;
	}

	blockquote::after{
	  content: '';
	}

	blockquote span{
	  display:block;
	  color:#333333;
	  font-style: normal;
	  font-weight: bold;
	  margin-top:1em;
	}
</style>