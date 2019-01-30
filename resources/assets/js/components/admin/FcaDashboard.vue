<template>
	<div id="fca_dashboard">
		<v-container fluid grid-list-md>
			<v-layout>
				<v-flex xs12 sm9 sm9>
					<v-card>
						<v-dialog v-model="viewSummary" persistent max-width="680">
				      <v-card>
				        <v-card-title class="pt-2 pb-2">
					        {{$t('Store')}}: <v-chip label small color=" "> {{store}} </v-chip> | {{$t('Audit by')}}: <v-chip label small color="red accent4 white--text">{{audit_by}}</v-chip>  | {{$t('Audit date')}} : <v-chip label small color="">{{audit_date}}</v-chip>
					        <v-spacer></v-spacer>
					        <v-btn fab :loading="btn_respond" small @click="viewSummary = false" >X</v-btn>
					      </v-card-title>
				        <v-divider></v-divider>
				        <v-card-text>
				        	{{$t('Tell whether this report approved or not')}}.
				        	<v-radio-group v-model="row" v-on:change="approveOrNot" class="text-xs-center" row>
							      <!-- <v-radio :label="$t('Re-open')" :disabled="loadcheckbox" value="0" ></v-radio> -->
							      <v-radio :label="$t('Approve')" :disabled="loadcheckbox" value="1"></v-radio>
							      <v-tooltip top style="display:block; flex:1;">
							      	<span>{{$t('To re-evaluate, use the submit for re-evaluation below.')}}</span>
								      <v-radio slot="activator" disabled :label="$t('Re-evaluate')" value="2"></v-radio>
							      </v-tooltip>
							    </v-radio-group>

				        </v-card-text>
				        <v-divider></v-divider>

				      	<v-data-table
				      		:items="items_getDetails"
				      		hide-actions
				      		hide-headers
				      		item-key="percentage"
				      	>	
				      		<template slot="items" slot-scope="props">
				      			<tr @click="props.expanded = !props.expanded">
				      				<td class="text-xs-right" style="width:150px;">{{$t('Percentage')}} : </td>
				      				<td clss="text-xs-left">{{props.item.percentage}} %</td>
				      			</tr>
				      			<tr>
				      				<td class="text-xs-right" style="width:150px;">{{$t('Remarks')}}: </td>
				      				<td clss="text-xs-left">{{props.item.remarks}} </td>
				      			</tr>
				      			<tr>
				      				<td class="text-xs-right" style="width:150px;"> {{$t('Audit date')}} : </td>
				      				<td clss="text-xs-left">{{props.item.audit_date}} </td>
				      			</tr>
				      			<tr>
				      				<td class="text-xs-right" style="width:150px;">{{$t('Audit by')}}: </td>
				      				<td clss="text-xs-left">{{props.item.audit_by}} </td>
				      			</tr>
				      		</template>
				      	</v-data-table>
				      	<v-divider></v-divider>
				      	<v-card height="150" hover style="overflow-y: auto;">
				        	<v-card-title><v-icon>equalizer</v-icon>{{$t('Previous Ratings')}}s </v-card-title>
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
				        			</template>
				        		</v-list>
				        		<div v-else>
				        			<v-flex text-xs-center>
				        				{{$t('No previous record.')}} 
				        			</v-flex>
				        		</div> 
				        </v-card>
				     

				      	<v-data-table
					        		:items="item_files"
					        		:headers="header_files"
					        		:rows-per-page-text="$t('Rows per page')"
					        		:no-data-text="$t('No data available')"
					        		must-sort
					        		hide-headers
					        		:disable-intitial-sort="true"
					        		:loading="item_files_loading"
					        	>
			        		<template slot="items" slot-scope="props">
			        			<tr>
			        				<td>{{props.item.original_file_name}}</td>
			        				<td>{{props.item.size}}KB</td>
			        				<td>{{props.item.ext}}</td>
			        				 <td class="justify-center layout px-0">

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

			        	<v-divider></v-divider>
			        	<v-flex>
			        		<v-alert icon="check_circle" color="success" dismissible v-model="showSuccess_respond">
					        	Successfully Submitted.
					        </v-alert>
			        	</v-flex>
			        	<v-card-actions >
			        		
			        		<v-flex xs12 sm8 sm8 offset-sm2>

			        			<v-dialog
							        ref="dialog"
							        persistent
							        v-model="dialog_extendExpDate"
							        lazy
							        full-width
							        width="290px"
							        :return-value.sync="v_extendExpDate"
							      >
							        <v-text-field
							          slot="activator"
							          :label="$t('Extend date of submission')"
							          v-model="v_extendExpDate"
							          prepend-icon="event"
							          single-line
							          :hint="$t('Time range to re-submit the report.')"
							          persistent-hint
							          readonly
							        ></v-text-field>
							        <v-date-picker v-model="v_extendExpDate" scrollable :min="maxDate">
							          <v-spacer></v-spacer>
							          <v-btn flat color="primary" @click="dialog_extendExpDate = false">Cancel</v-btn>
							          <v-btn flat color="primary" @click="$refs.dialog.save(v_extendExpDate)">OK</v-btn>
							        </v-date-picker>
							      </v-dialog>
			        		</v-flex>
			        	</v-card-actions>
				        <v-card-actions>
				     			<v-flex xs12 offset-sm2 sm8 sm8 justify-center>
				     				{{$t('Check this to send pending request.')}}
										 <v-switch
												:label="``"
												v-model="chk_pending"
												class="mb-0 mt-0"
											>
										</v-switch>
				     				<v-text-field
				     					class="mb-0 mt-0 input-group--focused"
			                name="input-1"
			                :label="`. . . .`"
			                v-model="respondMsg"
			                textarea
											:hint="(chk_pending ? $t('Enter response message here.')+ '('+$t('Optional')+')' : $t('Check the switch button above to enter message here.') )"
											persistent-hint=""
			                :disabled="chk_pending != true"
			              ></v-text-field>
				     			</v-flex>
				     			<v-flex>
				     				
				     			</v-flex>
				        </v-card-actions>
				        <v-flex class="text-xs-center">
				        	
				        	<v-tooltip right>
				        		<span>{{$t('This will notify through email. User will also be allowed to edit.')}}</span>
						        <v-btn slot="activator" small :disabled="chk_pending != true" :loading="feedbackSuggestion"  color="red accent-4 white--text" @click="submitPending()">
					     				<v-icon left>send</v-icon> {{$t('Submit for re-evaluation.')}} 
					     			</v-btn>
					     		</v-tooltip>
			     			</v-flex>
				        <v-divider></v-divider>
				        <v-card-actions>
				        	<v-flex class="text-xs-center" @click=""></v-flex>
				        </v-card-actions>

				        <v-card-text>
				        	<v-layout row wrap>
				        	</v-layout>
				        	<v-layout row wrap>
				        		<v-alert color="success" dismissible style="width:100%;"  v-model="sohwLeaveRemarksSuccess">
					        		{{$t('Successfully Submitted.')}} 
					        	</v-alert>
				        		<v-flex xs12 class="text-xs-center">

					        		<div>
					        			<v-text-field
					        				:label="$t('Remarks')"
					        				textarea
					        				v-model="remarks_textarea"
					        				:rules="[rules.required]"
					        			>	
					        			</v-text-field>
					        		</div>
					        		<!-- <div class="green--text" v-if="">
					        			<v-icon color="green">check</v-icon> {{$t('Successfully submitted.')}} 
					        		</div> -->
					        		<v-btn small :loading="feedbackSuggestion" color="red accent-4 white--text" @click="submitRemarks()">
						     				<v-icon left>send</v-icon> {{$t('Submit')}}
						     			</v-btn>
				        		</v-flex>
				        	</v-layout>
				        </v-card-text>
				      </v-card>
				    </v-dialog>

						<v-layout row wrap pl-3 pr-3>
							<v-flex xs12 sm2 sm2>
								<v-select
									:items="item_company"
									v-model="v_company"
									v-on:change="oc_company"
									:label="$t('Select Company')"
									hint="Hi-flyer / Olympus"
									persistent-hint
									single-line
									
								>
								</v-select>
							</v-flex>
							<v-flex xs12 sm2 sm2>
								<v-select
									:items="item_year"
									v-model="v_year"
									v-on:change="oc_year"
									:label="$t('Select year')+'. .'"
									single-line
								>
								</v-select>
							</v-flex>
							<v-flex xs12 sm3 sm3>
								<v-select
									:items="item_arls"
									v-model="v_arl"
									:label="$t('Select')+'ARL'"
									v-on:change="oc_arl"
									autocomplete
								>
									
								</v-select>
							</v-flex>
							<v-flex xs12 sm3 sm3 class="text-xs-right" >
								<v-text-field
									v-model="search"
									:label="$t('Search')"
									prepend-icon="search"
									clearable
									append-icon="close"
								></v-text-field>
							</v-flex>
							<v-flex xs12 sm2 sm2 class="text-xs-right">
								<v-btn @click="exportToExcel()" class="text-xs-right" :loading="load_exportExcel" outline color="red white--text"> {{$t('Export')}}</v-btn>
							</v-flex>
						</v-layout>

						<v-data-table
							:headers="headers"
							:items="items"
							:rows-per-page-text="$t('Rows per page')"
							:no-data-text="$t('No data available')"
							:search="search"
							:disable-initial-sort="true"
							must-sort
						>
							<template slot="items" slot-scope="props">
								<tr>
									<td>
										<div v-if="props.item.arl.length > 0">
											<template v-for="v in props.item.arl">
												<v-tooltip right>
													<span>{{v}}</span>
													<v-chip small label color="green white--text" slot="activator">
														{{v | truncate(10)}}
													</v-chip>
												</v-tooltip>
											</template>
										</div>
										<div v-else>
											N/A
										</div>	
										
									</td>
									<td>{{props.item.store}}</td>
									<!-- Start of Jan -->
									<td class="text-xs-center">
										<div v-if="props.item.Jan.percentage">
											<v-tooltip right >
												<div>
													{{$t('Click to view details.')}}<br>
													{{$t('Sent')}}: {{props.item.Jan.created_at}}
													<div v-if="props.item.Jan.percentage < percentage_threshold">
														{{$t('This doesn\'t meet the percetage requirements of')}} {{ percentage_threshold }} %
													</div>
												</div>
												<v-chip 
													slot="activator" 
													small label 
													:color="props.item.Jan.percentage < percentage_threshold ? 'yellow darken-3 white--text': 'green accent-4 white--text'"
													@click="viewDetails({
														fca_id: props.item.Jan.id,
														arl: props.item.arl,
														store: props.item.store,
														date_creater: props.item.Jan.created_at,
														audit_by: props.item.Jan.audit_by,
														audit_date: props.item.Jan.audit_date
													}); viewSummary = true"
												>
													<v-icon left>warning</v-icon> {{props.item.Jan.percentage}} %
												</v-chip>
											</v-tooltip>
											<v-progress-linear style="margin-top:0px; margin-bottom:0px;"  v-model="progres_value = props.item.Jan.percentage" :color="props.item.Jan.percentage < percentage_threshold ? 'yellow darken-3': 'green accent-4'"></v-progress-linear>
											<span class="caption red--text" v-if="!props.item.Jan.isThereFile">
												{{$t('No Files')}}
											</span>
											<span v-else class="caption" style="color:blue;">
				          			{{$t('File(s) included')}}
				          		</span>
											<span v-else class="caption" style="color:blue;">
				          			{{$t('File(s) included')}}
				          		</span>
										</div>
										<v-tooltip v-else right>
											<span> {{$t('No sumitted report yet.')}} </span>
											<v-chip v-if="checkExempted(props.item.store, 0, 'Jan') == false" slot="activator" label small color="white--text">
												<v-icon left>hourglass_empty</v-icon>{{$t('Empty')}} 
											</v-chip>
											<v-chip v-else slot="activator" label small color="yellow white--text">
												<v-icon left>hourglass_empty</v-icon>{{$t('Exempted')}} 
											</v-chip>
										</v-tooltip>
									</td>
									<!-- start of Feb -->
									<td>
										<div v-if="props.item.Feb.percentage">
											<v-tooltip right >
												<div>
													{{$t('Click to view details.')}}<br>
													{{$t('Sent')}}: {{props.item.Feb.created_at}}
													<div v-if="props.item.Feb.percentage < percentage_threshold">
														{{$t('This doesn\'t meet the percetage requirements of')}} {{ percentage_threshold }} %
													</div>
												</div>
												<v-chip 
													slot="activator" 
													small label 
													:color="props.item.Feb.percentage < percentage_threshold ? 'yellow darken-3 white--text': 'green accent-4 white--text'"
													@click="viewDetails({
														fca_id: props.item.Feb.id,
														arl: props.item.arl,
														store: props.item.store,
														date_creater: props.item.Feb.created_at,
														audit_by: props.item.Feb.audit_by,
														audit_date: props.item.Feb.audit_date
													}); viewSummary = true"
												>
													<v-icon left>warning</v-icon> {{props.item.Feb.percentage}} %
												</v-chip>
											</v-tooltip>
											<v-progress-linear style="margin-top:0px; margin-bottom:0px;"  v-model="progres_value = props.item.Feb.percentage" :color="props.item.Feb.percentage < percentage_threshold ? 'yellow darken-3': 'green accent-4'"></v-progress-linear>
											<span class="caption red--text" v-if="!props.item.Feb.isThereFile">
												{{$t('No Files')}}
											</span>
											<span v-else class="caption" style="color:blue;">
				          			{{$t('File(s) included')}}
				          		</span>
										</div>
										<v-tooltip v-else right>
											<span>{{$t('No submitted report yet.')}}</span>
											<v-chip v-if="checkExempted(props.item.store, 1, 'Feb') == false" slot="activator" label small color="white--text">
												<v-icon left>hourglass_empty</v-icon>{{$t('Empty')}}
											</v-chip>
											<v-chip v-else slot="activator" label small color="yellow white--text">
												<v-icon left>hourglass_empty</v-icon>{{$t('Exempted')}}
											</v-chip>
										</v-tooltip>
									</td>

									<!-- Start of March -->
									<td>
										<div v-if="props.item.Mar.percentage">
											<v-tooltip right >
												<div>
													{{$t('Click to view details.')}}<br>
													{{$t('Sent')}}: {{props.item.Mar.created_at}}
													<div v-if="props.item.Mar.percentage < percentage_threshold">
														{{$t('This doesn\'t meet the percetage requirements of')}} {{ percentage_threshold }} %
													</div>
												</div>
												<v-chip 
													slot="activator" 
													small label 
													:color="props.item.Mar.percentage < percentage_threshold ? 'yellow darken-3 white--text': 'green accent-4 white--text'"
													@click="viewDetails({
														fca_id: props.item.Mar.id,
														arl: props.item.arl,
														store: props.item.store,
														date_creater: props.item.Mar.created_at,
														audit_by: props.item.Mar.audit_by,
														audit_date: props.item.Mar.audit_date
													}); viewSummary = true"
												>
													<v-icon left>warning</v-icon> {{props.item.Mar.percentage}} %
												</v-chip>
											</v-tooltip>
											<v-progress-linear style="margin-top:0px; margin-bottom:0px;"  v-model="progres_value = props.item.Mar.percentage" :color="props.item.Mar.percentage < percentage_threshold ? 'yellow darken-3': 'green accent-4'"></v-progress-linear>
											<span class="caption red--text" v-if="!props.item.Mar.isThereFile">
												<center>
													{{$t('No Files')}}
												</center>
											</span>
											<span v-else class="caption" style="color:blue;">
				          			{{$t('File(s) included')}}
				          		</span>
										</div>
										<v-tooltip v-else right>
											<span>{{$t('No submitted report yet.')}}</span>
											<v-chip v-if="checkExempted(props.item.store, 2, 'Mar') == false" slot="activator" label small color="white--text">
												<v-icon left>hourglass_empty</v-icon>{{$t('Empty')}}
											</v-chip>
											<v-chip v-else slot="activator" label small color="yellow white--text">
												<v-icon left>hourglass_empty</v-icon>{{$t('Exempted')}}
											</v-chip>
										</v-tooltip>

									</td>

									<!-- Start of apr -->
									<td>
										<div v-if="props.item.Apr.percentage">
											<v-tooltip right >
												<div>
													{{$t('Click to view details.')}}<br>
													{{$t('Sent')}}: {{props.item.Apr.created_at}}
													<div v-if="props.item.Apr.percentage < percentage_threshold">
														{{$t('This doesn\'t meet the percetage requirements of')}} {{ percentage_threshold }} %
													</div>
												</div>
												<v-chip 
													slot="activator" 
													small label 
													:color="props.item.Apr.percentage < percentage_threshold ? 'yellow darken-3 white--text': 'green accent-4 white--text'"
													@click="viewDetails({
														fca_id: props.item.Apr.id,
														arl: props.item.arl,
														store: props.item.store,
														date_creater: props.item.Apr.created_at,
														audit_by: props.item.Apr.audit_by,
														audit_date: props.item.Apr.audit_date
													}); viewSummary = true"
												>
													<v-icon left>warning</v-icon> {{props.item.Apr.percentage}} %
												</v-chip>
											</v-tooltip>
											<v-progress-linear style="margin-top:0px; margin-bottom:0px;"  v-model="progres_value = props.item.Apr.percentage" :color="props.item.Apr.percentage < percentage_threshold ? 'yellow darken-3': 'green accent-4'"></v-progress-linear>
											<span class="caption red--text" v-if="!props.item.Apr.isThereFile">
												{{$t('No Files')}}
											</span>
											<span v-else class="caption" style="color:blue;">
				          			{{$t('File(s) included')}}
				          		</span>
										</div>
										<v-tooltip v-else right>
											<span>{{$t('No submitted report yet.')}}</span>
											<v-chip v-if="checkExempted(props.item.store, 3, 'Apr') == false" slot="activator" label small color="white--text">
												<v-icon left>hourglass_empty</v-icon>{{$t('Empty')}}
											</v-chip>
											<v-chip v-else slot="activator" label small color="yellow white--text">
												<v-icon left>hourglass_empty</v-icon>{{$t('Exempted')}}
											</v-chip>
										</v-tooltip>
									</td>

									<!-- start of May -->
									<td>
										<div v-if="props.item.May.percentage">
											<v-tooltip right >
												<div>
													{{$t('Click to view details.')}}<br>
													{{$t('Sent')}}: {{props.item.May.created_at}}
													<div v-if="props.item.May.percentage < percentage_threshold">
														{{$t('This doesn\'t meet the percetage requirements of')}} {{ percentage_threshold }} %
													</div>
												</div>
												<v-chip 
													slot="activator" 
													small label 
													:color="props.item.May.percentage < percentage_threshold ? 'yellow darken-3 white--text': 'green accent-4 white--text'"
													@click="viewDetails({
														fca_id: props.item.May.id,
														arl: props.item.arl,
														store: props.item.store,
														date_creater: props.item.May.created_at,
														audit_by: props.item.May.audit_by,
														audit_date: props.item.May.audit_date
													}); viewSummary = true"
												>
													<v-icon left v-if="props.item.May.isPassed == 0">warning</v-icon>
													<v-icon left v-if="props.item.May.isPassed == 1">check_circle</v-icon>

													{{props.item.May.percentage}} %
												</v-chip>
											</v-tooltip>
											<v-progress-linear style="margin-top:0px; margin-bottom:0px;"  v-model="progres_value = props.item.May.percentage" :color="props.item.May.percentage < percentage_threshold ? 'yellow darken-3': 'green accent-4'"></v-progress-linear>
											
											<span class="caption red--text" v-if="!props.item.May.isThereFile">
												{{$t('No Files')}}
											</span>
											<span v-else class="caption" style="color:blue;">
				          			{{$t('File(s) included')}}
				          		</span>
										</div>
										<v-tooltip v-else right>
											<span>{{$t('No submitted report yet.')}}</span>
											<v-chip v-if="checkExempted(props.item.store, 4, 'May') == false" slot="activator" label small color="white--text">
												<v-icon left>hourglass_empty</v-icon>{{$t('Empty')}}
											</v-chip>
											<v-chip v-else slot="activator" label small color="yellow white--text">
												<v-icon left>hourglass_empty</v-icon>{{$t('Exempted')}}
											</v-chip>
										</v-tooltip>
									</td>

									<!-- start of june -->
									<td>
										<div v-if="props.item.Jun.percentage">
											<v-tooltip right >
												<div>
													{{$t('Click to view details.')}}<br>
													{{$t('Sent')}}: {{props.item.Jun.created_at}}
													<div v-if="props.item.Jun.percentage < percentage_threshold">
														{{$t('This doesn\'t meet the percetage requirements of')}} {{ percentage_threshold }} %
													</div>
												</div>
												<v-chip 
													slot="activator" 
													small label 
													:color="props.item.Jun.percentage < percentage_threshold ? 'yellow darken-3 white--text': 'green accent-4 white--text'"
													@click="viewDetails({
														fca_id: props.item.Jun.id,
														arl: props.item.arl,
														store: props.item.store,
														date_creater: props.item.Jun.created_at,
														audit_by: props.item.Jun.audit_by,
														audit_date: props.item.Jun.audit_date
													}); viewSummary = true"
												>
													<v-icon left v-if="props.item.Jun.isPassed == 0">warning</v-icon>
													<v-icon left v-if="props.item.Jun.isPassed == 1">check_circle</v-icon>
													 {{props.item.Jun.percentage}} %
												</v-chip>
											</v-tooltip>
											<v-progress-linear style="margin-top:0px; margin-bottom:0px;"  v-model="progres_value = props.item.Jun.percentage" :color="props.item.Jun.percentage < percentage_threshold ? 'yellow darken-3': 'green accent-4'"></v-progress-linear>
											<span class="caption red--text" v-if="!props.item.Jun.isThereFile">
												{{$t('No Files')}}
											</span>
											<span v-else class="caption" style="color:blue;">
				          			{{$t('File(s) included')}}
				          		</span>
										</div>
										<v-tooltip v-else right>
												<!-- {{checkExempted()}} -->
												<span>{{$t('No submitted report yet.')}}</span>
												<v-chip v-if="checkExempted(props.item.store,5,'Jun') == false" slot="activator" label small color="white--text">
													<v-icon left>hourglass_empty</v-icon>{{$t('Empty')}}
												</v-chip>
												<v-chip v-else slot="activator" label small color="yellow white--text">
													<v-icon left>hourglass_empty</v-icon>{{$t('Exempted')}}
												</v-chip>
										</v-tooltip>
									</td>

									<!-- start of july -->
									<td>
										<div v-if="props.item.Jul.percentage">
											<v-tooltip right >
												<div>
													{{$t('Click to view details.')}}<br>
													{{$t('Sent')}}: {{props.item.Jul.created_at}}
													<div v-if="props.item.Jul.percentage < percentage_threshold">
														{{$t('This doesn\'t meet the percetage requirements of')}} {{ percentage_threshold }} %
													</div>
												</div>
												<v-chip 
													slot="activator" 
													small label 
													:color="props.item.Jul.percentage < percentage_threshold ? 'yellow darken-3 white--text': 'green accent-4 white--text'"
													@click="viewDetails({
														fca_id: props.item.Jul.id,
														arl: props.item.arl,
														store: props.item.store,
														date_creater: props.item.Jul.created_at,
														audit_by: props.item.Jul.audit_by,
														audit_date: props.item.Jul.audit_date
													}); viewSummary = true"
												>
													<v-icon left v-if="props.item.Jul.isPassed == 0">warning</v-icon>
													<v-icon left v-if="props.item.Jul.isPassed == 1">check_circle</v-icon> 
													{{props.item.Jul.percentage}} %
												</v-chip>
											</v-tooltip>
											<v-progress-linear style="margin-top:0px; margin-bottom:0px;"  v-model="progres_value = props.item.Jul.percentage" :color="props.item.Jul.percentage < percentage_threshold ? 'yellow darken-3': 'green accent-4'"></v-progress-linear>
											<span class="caption red--text" v-if="!props.item.Jul.isThereFile">
												{{$t('No Files')}}
											</span>
											<span v-else class="caption" style="color:blue;">
				          			{{$t('File(s) included')}}
				          		</span>
										</div>
										<v-tooltip v-else right>
											<span>{{$t('No submitted report yet.')}}</span>
											<v-chip v-if="checkExempted(props.item.store, 6, 'Jul') == false" slot="activator" label small color="white--text">
												<v-icon left>hourglass_empty</v-icon>{{$t('Empty')}}
											</v-chip>
											<v-chip v-else slot="activator" label small color="yellow white--text">
												<v-icon left>hourglass_empty</v-icon>{{$t('Exempted')}}
											</v-chip>
										</v-tooltip>
									</td>

									<!-- Start of august -->
									<td>
										<div v-if="props.item.Aug.percentage">
											<v-tooltip right >
												<div>
													{{$t('Click to view details.')}}<br>
													{{$t('Sent')}}: {{props.item.Aug.created_at}}
													<div v-if="props.item.Aug.percentage < percentage_threshold">
														{{$t('This doesn\'t meet the percetage requirements of')}} {{ percentage_threshold }} %
													</div>
												</div>
												<v-chip 
													slot="activator" 
													small label 
													:color="props.item.Aug.percentage < percentage_threshold ? 'yellow darken-3 white--text': 'green accent-4 white--text'"
													@click="viewDetails({
														fca_id: props.item.Aug.id,
														arl: props.item.arl,
														store: props.item.store,
														date_creater: props.item.Aug.created_at,
														audit_by: props.item.Aug.audit_by,
														audit_date: props.item.Aug.audit_date
													}); viewSummary = true"
												>
													<v-icon left v-if="props.item.Aug.isPassed == 0">warning</v-icon>
													<v-icon left v-if="props.item.Aug.isPassed == 1">check_circle</v-icon>
													{{props.item.Aug.percentage}} %
												</v-chip>
											</v-tooltip>
											<v-progress-linear style="margin-top:0px; margin-bottom:0px;"  v-model="progres_value = props.item.Aug.percentage" :color="props.item.Aug.percentage < percentage_threshold ? 'yellow darken-3': 'green accent-4'"></v-progress-linear>
											<span class="caption red--text" v-if="!props.item.Aug.isThereFile">
												{{$t('No Files')}}
											</span>
											<span v-else class="caption" style="color:blue;">
				          			{{$t('File(s) included')}}
				          		</span>
										</div>
										<v-tooltip v-else right>
											<span>{{$t('No submitted report yet.')}}</span>
											<v-chip v-if="checkExempted(props.item.store, 7, 'Aug') == false" slot="activator" label small color="white--text">
												<v-icon left>hourglass_empty</v-icon>{{$t('Empty')}}
											</v-chip>
											<v-chip v-else slot="activator" label small color="yellow white--text">
												<v-icon left>hourglass_empty</v-icon>{{$t('Exempted')}}
											</v-chip>
										</v-tooltip>
									</td>

									<!-- Start of september -->
									<td>
										<div v-if="props.item.Sep.percentage">
											<v-tooltip right >
												<div>
													{{$t('Click to view details.')}}<br>
													{{$t('Sent')}}: {{props.item.Sep.created_at}}
													<div v-if="props.item.Sep.percentage < percentage_threshold">
														{{$t('This doesn\'t meet the percetage requirements of')}} {{ percentage_threshold }} %
													</div>
												</div>
												<v-chip 
													slot="activator" 
													small label 
													:color="props.item.Sep.percentage < percentage_threshold ? 'yellow darken-3 white--text': 'green accent-4 white--text'"
													@click="viewDetails({
														fca_id: props.item.Sep.id,
														arl: props.item.arl,
														store: props.item.store,
														date_creater: props.item.Sep.created_at,
														audit_by: props.item.Sep.audit_by,
														audit_date: props.item.Sep.audit_date
													}); viewSummary = true"
												>
													<v-icon left v-if="props.item.Sep.isPassed == 0">warning</v-icon>
													<v-icon left v-if="props.item.Sep.isPassed == 1">check_circle</v-icon> 
													{{props.item.Sep.percentage}} %
												</v-chip>
											</v-tooltip>
											<v-progress-linear style="margin-top:0px; margin-bottom:0px;"  v-model="progres_value = props.item.Sep.percentage" :color="props.item.Sep.percentage < percentage_threshold ? 'yellow darken-3': 'green accent-4'"></v-progress-linear>
											<span class="caption red--text" v-if="!props.item.Sep.isThereFile">
												{{$t('No Files')}}
											</span>
											<span v-else class="caption" style="color:blue;">
				          			{{$t('File(s) included')}}
				          		</span>
										</div>
										<v-tooltip v-else right>
											<span>{{$t('No submitted report yet.')}}</span>
											<v-chip v-if="checkExempted(props.item.store, 8, 'Sep') == false" slot="activator" label small color="white--text">
												<v-icon left>hourglass_empty</v-icon>{{$t('Empty')}}
											</v-chip>
											<v-chip v-else slot="activator" label small color="yellow white--text">
												<v-icon left>hourglass_empty</v-icon>{{$t('Exempted')}}
											</v-chip>
										</v-tooltip>
									</td>

									<!-- start of october -->
									<td>
										<div v-if="props.item.Oct.percentage">
											<v-tooltip right >
												<div>
													{{$t('Click to view details.')}}<br>
													{{$t('Sent')}}: {{props.item.Oct.created_at}}
													<div v-if="props.item.Oct.percentage < percentage_threshold">
														{{$t('This doesn\'t meet the percetage requirements of')}} {{ percentage_threshold }} %
													</div>
												</div>
												<v-chip 
													slot="activator" 
													small label 
													:color="props.item.Oct.percentage < percentage_threshold ? 'yellow darken-3 white--text': 'green accent-4 white--text'"
													@click="viewDetails({
														fca_id: props.item.Oct.id,
														arl: props.item.arl,
														store: props.item.store,
														date_creater: props.item.Oct.created_at,
														audit_by: props.item.Oct.audit_by,
														audit_date: props.item.Oct.audit_date
													}); viewSummary = true"
												>
													<v-icon left v-if="props.item.Oct.isPassed == 0">warning</v-icon>
													<v-icon left v-if="props.item.Oct.isPassed == 1">check_circle</v-icon> 
													{{props.item.Oct.percentage}} %
												</v-chip>
											</v-tooltip>
											<v-progress-linear style="margin-top:0px; margin-bottom:0px;"  v-model="progres_value = props.item.Oct.percentage" :color="props.item.Oct.percentage < percentage_threshold ? 'yellow darken-3': 'green accent-4'"></v-progress-linear>
											<span class="caption red--text" v-if="!props.item.Oct.isThereFile">
												{{$t('No Files')}}
											</span>
											<span v-else class="caption" style="color:blue;">
				          			{{$t('File(s) included')}}
				          		</span>
										</div>
										<v-tooltip v-else right>
											<span>{{$t('No submitted report yet.')}}</span>
											<v-chip v-if="checkExempted(props.item.store, 9, 'Oct') == false" slot="activator" label small color="white--text">
												<v-icon left>hourglass_empty</v-icon>{{$t('Empty')}}
											</v-chip>
											<v-chip v-else slot="activator" label small color="yellow white--text">
												<v-icon left>hourglass_empty</v-icon>{{$t('Exempted')}}
											</v-chip>
										</v-tooltip>
									</td>

									<!-- Start of november -->
									<td>
										<div v-if="props.item.Nov.percentage">
											<v-tooltip right >
												<div>
													{{$t('Click to view details.')}}<br>
													{{$t('Sent')}}: {{props.item.Nov.created_at}}
													<div v-if="props.item.Nov.percentage < percentage_threshold">
														{{$t('This doesn\'t meet the percetage requirements of')}} {{ percentage_threshold }} %
													</div>
												</div>
												<v-chip 
													slot="activator" 
													small label 
													:color="props.item.Nov.percentage < percentage_threshold ? 'yellow darken-3 white--text': 'green accent-4 white--text'"
													@click="viewDetails({
														fca_id: props.item.Nov.id,
														arl: props.item.arl,
														store: props.item.store,
														date_creater: props.item.Nov.created_at,
														audit_by: props.item.Nov.audit_by,
														audit_date: props.item.Nov.audit_date
													}); viewSummary = true"
												>
													<v-icon left v-if="props.item.Nov.isPassed == 0">warning</v-icon>
													<v-icon left v-if="props.item.Nov.isPassed == 1">check_circle</v-icon> 
													{{props.item.Nov.percentage}} %
												</v-chip>
											</v-tooltip>
											<v-progress-linear style="margin-top:0px; margin-bottom:0px;"  v-model="progres_value = props.item.Nov.percentage" :color="props.item.Nov.percentage < percentage_threshold ? 'yellow darken-3': 'green accent-4'"></v-progress-linear>
											<span class="caption red--text" v-if="!props.item.Nov.isThereFile">
												{{$t('No Files')}}
											</span>
											<span v-else class="caption" style="color:blue;">
				          			{{$t('File(s) included')}}
				          		</span>
										</div>
										<v-tooltip v-else right>
											<span>{{$t('No submitted report yet.')}}</span>
											<v-chip v-if="checkExempted(props.item.store, 10, 'Nov') == false" slot="activator" label small color="white--text">
												<v-icon left>hourglass_empty</v-icon>{{$t('Empty')}}
											</v-chip>
											<v-chip v-else slot="activator" label small color="yellow white--text">
												<v-icon left>hourglass_empty</v-icon>{{$t('Exempted')}}
											</v-chip>
										</v-tooltip>
									</td>

									<!-- start of december -->
									<td>
										<div v-if="props.item.Dec.percentage">
											<v-tooltip right >
												<div>
													{{$t('Click to view details.')}}<br>
													{{$t('Sent')}}: {{props.item.Dec.created_at}}
													<div v-if="props.item.Dec.percentage < percentage_threshold">
														{{$t('This doesn\'t meet the percetage requirements of')}} {{ percentage_threshold }} %
													</div>
												</div>
												<v-chip 
													slot="activator" 
													small label 
													:color="props.item.Dec.percentage < percentage_threshold ? 'yellow darken-3 white--text': 'green accent-4 white--text'"
													@click="viewDetails({
														fca_id: props.item.Dec.id,
														arl: props.item.arl,
														store: props.item.store,
														date_creater: props.item.Dec.created_at,
														audit_by: props.item.Dec.audit_by,
														audit_date: props.item.Dec.audit_date
													}); viewSummary = true"
												>
													<v-icon left v-if="props.item.Dec.isPassed == 0">warning</v-icon>
													<v-icon left v-if="props.item.Dec.isPassed == 1">check_circle</v-icon>
													{{props.item.Dec.percentage}} %
												</v-chip>
											</v-tooltip>
											<v-progress-linear style="margin-top:0px; margin-bottom:0px;"  v-model="progres_value = props.item.Dec.percentage" :color="props.item.Dec.percentage < percentage_threshold ? 'yellow darken-3': 'green accent-4'"></v-progress-linear>
											<span class="caption red--text" v-if="!props.item.Dec.isThereFile">
												{{$t('No Files')}}
											</span>	
											<span v-else class="caption" style="color:blue;">
				          			{{$t('File(s) included')}}
				          		</span>

										</div>
										<v-tooltip v-else right>
											<span>{{$t('No submitted report yet.')}}</span>
											<v-chip v-if="checkExempted(props.item.store, 11, 'Dec') == false" slot="activator" label small color="white--text">
												<v-icon left>hourglass_empty</v-icon>{{$t('Empty')}}
											</v-chip>
											<v-chip v-else slot="activator" label small color="yellow white--text">
												<v-icon left>hourglass_empty</v-icon>{{$t('Exempted')}}
											</v-chip>
										</v-tooltip>
									</td>
								</tr>
							</template>
						</v-data-table>
					
					</v-card>
				</v-flex>
				<v-flex xs12 sm3 sm3>
					<v-card>
						<v-card-title class="title">
							{{$t('Comment section')}}
						</v-card-title>
						<v-divider></v-divider>
						<v-dialog scrollable v-model="dialog_comment_section" max-width="650">
				      <v-card>
				        <v-card-title class="headline">
				        	<div>
				        		[{{comment_store}}] : {{comment_remarks}} 
				        	</div> 
				        	<br>
				        	<div class="caption" style="position: absolute; left: 0; padding-left: 15px; margin-top:20px">
					        	{{$t('Sender')}}: {{comment_name_sender}}
				        	</div>
				        	<v-spacer></v-spacer>
				        	<v-btn fab sma ll :loading="btn_respond" color="red white--text" @click="dialog_comment_section = false">x</v-btn>

				        </v-card-title>
				        <v-divider></v-divider>
				        <v-card-text style="display:flex; flex-direction: column-reverse;" ref="setThistoBottom">
					       	<v-list three-line>
					          <template v-for="(item, index) in items_comment_section">
					            <v-divider v-if="index != 0" :key="index"></v-divider>
					            <v-list-tile avatar :key="item.title" @click="">
					              <v-list-tile-avatar style="">
					              	<v-avatar class="grey white--text" :size="36">{{item.name.charAt(0).toUpperCase()}}</v-avatar>
					              </v-list-tile-avatar>
					              <v-list-tile-content>
					                <v-list-tile-title v-html="item.name"></v-list-tile-title>
					                <v-list-tile-sub-title v-html="item.fca_comment"></v-list-tile-sub-title>
					              </v-list-tile-content>
					              <v-list-tile-action>
					              	<v-list-tile-action-text>
					              	</v-list-tile-action-text>
					                <v-list-tile-action-text>{{ $gs.date_replace(item.created_at)}}</v-list-tile-action-text> 
					           
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
				          	<v-btn color="red accent-4 white--text" :loading="btn_reply_load" @click.native="submitReply();"> {{$t('Submit')}} <v-icon>send</v-icon></v-btn>
				       		</v-flex>
				        </v-card-actions>
				      </v-card>
				    </v-dialog>
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
			                	<span><b>[{{ item.store }}]:  {{item.remarks}}</b></span>
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

	export default{
		components:{

		},
		data:()=>({
			loadcheckbox: false,
			comments_item: [],
			row: null,
			maxDate: null,
			headers:[
				{
					text: 'ARL',
					align:'left',
					sortable:true,
					value:'arl'
				},
				{
					text: (localStorage.getItem('flang') === 'fr' ? 'Restaurant' : 'Stores'),
					align:'left',
					sortable: true,
					value:'store'
				},
				{
					text: (localStorage.getItem('flang') === 'fr' ? 'Janvier' : 'Jan'),
					align: 'center',
					sortable: false,
					value :'Jan'
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
			items_comment_section: [

       ],
			item_company: [
				{
					text: 'All',
					value: 'all'
				},
				{
					text: 'Hi-flyer',
					value: 1
				},
				{
					text: 'Olympus',
					value: 2
				}
			],
			v_company: null,
			items:[],
			search: null,
			item_year:[],
			v_year: null,
			item_arls: [],
			v_arl: null,
			percentage_threshold: null,
			progres_value: 0,
			viewSummary: false,
			fca_id : null,
			arl : null,
			store : null,
			date_creater : null,
			audit_by: null,
			audit_date: null,
			items_getDetails: [],
			item_files: [],
			header_files: [],
			item_files_loading: true,
			chk_pending: false,
			respondMsg: null,
			remarks_textarea: null,
			btn_respond: false,
			showSuccess_respond: false,
			rules:{
				required: (value) => !!value || 'Required.'
			},
			sohwLeaveRemarksSuccess: false,
			feedbackSuggestion: false,
			dialog_comment_section: false,
			comment_store: null,
			comment_remarks: null,
			comment_name_sender: null,
			comment_fca_comments: null,
			comment_audit_date: null,
			comment_fca_id: null,
			busy: false,
			txt_reply: null,
			showReplyErrorMsg: false,
			showSuccessReply: false,
			btn_reply_load: false,
			load_exportExcel: false,
			dialog_extendExpDate: false,
			v_extendExpDate: null,
			prev_score:[],
			exempted_stores: []
		}),
		methods:{
			//export data
			checkExempted:function(store_id,month_num,month_name){
				var status = false;
				for(var obj in this.exempted_stores){
					if(
						store_id == this.exempted_stores[obj].store_name &&
						month_num == this.exempted_stores[obj].month_num &&
						month_name == this.exempted_stores[obj].month_txt
					){
						status = true;
					}
				}
				return status;
			},
			getExemptedItems(){
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
						//console.log(response.data)
					})
					.catch(error=>{
						console.log(error)
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
			approveOrNot(){
				const self =this
				const obj = {
					approvedOrnot: this.row,
					fca_id: this.fca_id
				}
				this.loadcheckbox = true;
				try{
					axios({
						url: '/ktool/api/approveOrNot',
						method:'post',
						data: obj,
						config:'JSON' 
					})
					.then(response=>{
						if(response.status == 200){
							this.loadcheckbox = false;
						}
					})
					.catch(error=>{
						console.log(error)
						this.loadcheckbox = false;
					})
				}catch(error){
					console.log(error)
					this.loadcheckbox = false;
				}
			},
			exportToExcel(){
				this.load_exportExcel = true
				const obj = {
					company: this.v_company,
					year : this.v_year,
					arl: this.v_arl
				}
				try{
					axios({
						url:'/ktool/api/exportFcaDashboard',
						method:'post',
						data: obj,
						config: 'JSON' 
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
						this.load_exportExcel = false;
					})
					.catch(error=>{
						console.log(error)
						this.load_exportExcel = false
					})
				}catch(error){
					console.log('error')
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
					this.txt_reply = ''
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
			loadMore:function(){
				this.busy = true;
				setTimeout(()=>{
					setTimeout(() => {
		        for (var i = 0, j = 2; i < j; i++) {
		          this.items_comment_section.push({ name: count++ });
		        }
		        this.busy = false;
		      }, 1000);
				},5000)
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
			submitRemarks(){
				const self = this
				this.feedbackSuggestion = true
				const obj = {
					remarks: this.remarks_textarea,
					fca_id: this.fca_id
				}
				if(!this.remarks_textarea){
					alert('Please enter your remarks')
					this.feedbackSuggestion = false;
					return false;
				}
				this.$store.dispatch('SEND_FEEDBACK',obj)
					.then(response=>{
						if(response.status === 'success'){
							this.remarks = ''
							self.sohwLeaveRemarksSuccess = true
						}
						setTimeout(()=>{
							self.sohwLeaveRemarksSuccess = false
						},5000)
						self.feedbackSuggestion = false
					})
					.catch(error=>{
						self.feedbackSuggestion = false
						console.log(error)
					})
			},
			submitPending(){
				const self = this
				this.btn_respond = true
				this.feedbackSuggestion = true
				if(!this.v_extendExpDate){
					alert('Select date to extend.')
					this.btn_respond = false
					this.feedbackSuggestion = false
					return false;
				}
				const obj = {
					respondMsg: this.respondMsg,
					isPending: this.chk_pending,
					fca_id: this.fca_id,
					date_to_extend: this.v_extendExpDate
				}

				this.$store.dispatch('SUBMIT_PENDING', obj)
					.then(response=>{
						if(response.status === 'success'){
							this.showSuccess_respond = true
						}
						this.btn_respond = false
						this.feedbackSuggestion = false
						setTimeout(()=>{
							self.showSuccess_respond = false
						},5000)
					})
					.catch(error=>{
						console.log(error);

						this.btn_respond = false
						this.feedbackSuggestion = false
					})				
			},
			downloadFile(item,index){
				// lets check in the server if this file exists
				axios.get('/ktool/api/downloadFile/'+index.original_file_name+'/'+index.file_name)
					.then(response=>{
						if(response.status === 200){
							if(response.data.data === 'success'){	
								var a = document.createElement("a")
								a.href="/ktool/public/storage/fca_files/"+index.original_file_name
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
			viewDetails(data){
				this.fca_id = data.fca_id
				this.arl = data.arl
				this.store = data.store
				this.date_creater = data.date_creater
				this.audit_by = data.audit_by 
				this.audit_date = data.audit_date
				const obj = {
					fca_id: data.fca_id
				}
				this.getPreviousScore(obj)

				this.$store.dispatch('GET_ARL_REPORTS', obj)
					.then(response=>{
						this.items_getDetails = response.details
						if(response.details[0].isEmailed > 0){
							this.row = ''+response.details[0].isEmailed+''
						}else{
							if(response.details[0].isReeval > 0){
								this.row = '2'
							}else{
								this.row = ''+response.details[0].isPassed+''
							}
						}
						//console.log(response.details)
					})
					.then(error=>{
						console.log(error)
					})
				this.$store.dispatch('GET_FILES_UPLOADED',obj)
						.then(response=>{
							this.item_files = response.data
							this.item_files_loading = false;
							//this.getTableApi(this.getYears)
						})
						.catch(error=>{
							this.item_files_loading = false;
						})
			},
			oc_company(val){
				var arl;
				if(!this.v_arl){
					arl = 'all'
				}else{
					arl = this.v_arl
				}
				var year = this.v_year;

				this.getTableApi(year, arl, val)
			},
			oc_arl(val){
				this.getTableApi(this.v_year, val, this.v_company)
			},
			oc_year(val){
				this.getTableApi(val, this.v_arl, this.v_company)
			},
			getTableApi(year = (new Date()).getFullYear(), arl = 'all', company = 'all'){
				const data = {
					year: year,
					arl : arl,
					company: company
				}
				this.$store.dispatch('GET_ARL_DASHBOARD', data)
				.then(response=>{
					this.items = response.data
					this.percentage_threshold = response.threshold_percent
				})
				.catch(error=>{
					console.log(error)
				})
			},
			getFeedbacks(){
				this.$store.dispatch('getFeedBacks')
					.then(response=>{
						this.comments_item = response.data
					})
					.catch(error=>{
						console.log(error);
					})
			}
		},
		filters:{
			truncate: function(string, value){
				return string.substring(0,value)+'...'
			},
		},
		created(){ 
			this.getExemptedItems()
			this.getTableApi();
			this.getFeedbacks();
		},
		mounted(){
			this.maxDate = this.$gs.getCurrentDate()
			this.v_company = 'all'
			this.v_year = (new Date()).getFullYear()
			var years = [];
      var currentYear = new Date().getFullYear(), years = [];
      var startYear = startYear || 2010;

      while ( startYear <= currentYear ) {
              years.push(startYear++);
      } 
      this.item_year = years.reverse()
      

      this.$store.dispatch('GET_ARLS')
				.then(response=>{
					this.item_arls = response.data
				})
				.catch(error=>{

				})


		}
	}
</script>
<style scoped>
	span >>> .chip__content{
		cursor: pointer;
	}

</style>