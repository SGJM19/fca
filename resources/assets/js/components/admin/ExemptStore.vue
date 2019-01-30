<template>
  <div id="exemp_store">
    <v-container grid-list-md>
      <v-card color="white">
        <v-card-title>
          <v-layout row>
            <v-flex xs12 sm3 sm3>
              <v-select
                v-model="v_company"
                :items="item_company"
                item-text="text"
                item-value="value"
                placeholder="Select Hiflyer/Olympus"
              >
              </v-select>
            </v-flex>
            <v-flex xs12 sm3 sm3>
              <v-menu
                ref="menu"
                :close-on-content-click="false"
                v-model="menu"
                :nudge-right="40"
                :return-value.sync="date"
                lazy
                transition="scale-transition"
                offset-y
                full-width
                min-width="290px"
              >
                <v-text-field
                  slot="activator"
                  v-model="date"
                  label="Picker in menu"
                  prepend-icon="event"
                  readonly
                ></v-text-field>
                <v-date-picker v-model="date" no-title scrollable>
                  <v-spacer></v-spacer>
                  <v-btn flat color="primary" @click="menu = false">Cancel</v-btn>
                  <v-btn flat color="primary" @click="$refs.menu.save(date)">OK</v-btn>
                </v-date-picker>
              </v-menu>
            </v-flex>
            <v-flex xs12 sm3 sm3 offset-sm6 >
              <v-text-field
                placeholder="Search store. . ."
                v-model="search_store"
                prepend-icon="search"
              >
                
              </v-text-field>
            </v-flex>
          </v-layout>
          
        </v-card-title>
        <!--   :pagination.sync="pagination"
          :total-items="total_items" -->
        <div id="table_container" style="max-height:500px !important; background-color:white; overflow-y:auto !important; border:0px solid #000;">
          <v-data-table
            :headers="header_stores"
            :items="item_stores"
            :search="search_store"
          >
            <template slot="items" slot-scope="props">
              <tr>
                <td>
                  {{props.item.store_id}}
                </td>
                <td style="padding:0px; margin:0px;"> <!-- january -->
                  <v-chip :color="checkIfThisAllowed(props.item.id, 0, 'Jan') ? 'grey lighten-1 white--text' : 'green white--text'" label small @click="allowOrNot(props.item.id, props.item.store_id, 0,'Jan')">
                    <span v-if="checkIfThisAllowed(props.item.id, 0, 'Jan')">Disabled</span>
                    <span v-else>Allowed</span>
                  </v-chip>
                </td> 
                <td style="padding:0px;">
                   <v-chip :color="checkIfThisAllowed(props.item.id, 1, 'Feb') ? 'grey lighten-1 white--text' : 'green white--text'" label small @click="allowOrNot(props.item.id, props.item.store_id, 1,'Feb')">
                    <span v-if="checkIfThisAllowed(props.item.id, 1, 'Feb')">Disabled</span>
                    <span v-else>Allowed</span>
                  </v-chip>
                </td>
                <td style="padding:0px;">
                  <v-chip :color="checkIfThisAllowed(props.item.id, 2, 'Mar') ? 'grey lighten-1 white--text' : 'green white--text'" label small @click="allowOrNot(props.item.id, props.item.store_id, 2,'Mar')">
                    <span v-if="checkIfThisAllowed(props.item.id, 2, 'Mar')">Disabled</span>
                    <span v-else>Allowed</span>
                  </v-chip>
                </td>
                <td style="padding:0px;">
                  <v-chip :color="checkIfThisAllowed(props.item.id, 3, 'Apr') ? 'grey lighten-1 white--text' : 'green white--text'" label small @click="allowOrNot(props.item.id, props.item.store_id, 3,'Apr')">
                    <span v-if="checkIfThisAllowed(props.item.id, 3, 'Apr')">Disabled</span>
                    <span v-else>Allowed</span>
                  </v-chip>
                </td>
                <td style="padding:0px;">
                  <v-chip :color="checkIfThisAllowed(props.item.id, 4, 'May') ? 'grey lighten-1 white--text' : 'green white--text'" label small @click="allowOrNot(props.item.id, props.item.store_id, 4, 'May')">
                    <span v-if="checkIfThisAllowed(props.item.id, 4, 'May')">Disabled</span>
                    <span v-else>Allowed</span>
                  </v-chip>
                </td>
                <td style="padding:0px;">
                  <v-chip :color="checkIfThisAllowed(props.item.id, 5, 'Jun') ? 'grey lighten-1 white--text' : 'green white--text'" label small @click="allowOrNot(props.item.id, props.item.store_id, 5, 'Jun')">
                    <span v-if="checkIfThisAllowed(props.item.id, 5, 'Jun')">Disabled</span>
                    <span v-else>Allowed</span>
                  </v-chip>
                </td>
                <td style="padding:0px;">
                  <v-chip :color="checkIfThisAllowed(props.item.id, 6, 'Jul') ? 'grey lighten-1 white--text' : 'green white--text'" label small @click="allowOrNot(props.item.id, props.item.store_id, 6, 'Jul')">
                    <span v-if="checkIfThisAllowed(props.item.id, 6, 'Jul')">Disabled</span>
                    <span v-else>Allowed</span>
                  </v-chip>
                </td>
                <td style="padding:0px;">
                  <v-chip :color="checkIfThisAllowed(props.item.id, 7, 'Aug') ? 'grey lighten-1 white--text' : 'green white--text'" label small @click="allowOrNot(props.item.id, props.item.store_id, 7,'Aug')">
                    <span v-if="checkIfThisAllowed(props.item.id, 7, 'Aug')">Disabled</span>
                    <span v-else>Allowed</span>
                  </v-chip>
                </td>
                <td style="padding:0px;">
                  <v-chip :color="checkIfThisAllowed(props.item.id, 8, 'Sep') ? 'grey lighten-1 white--text' : 'green white--text'" label small @click="allowOrNot(props.item.id, props.item.store_id, 8,'Sep')">
                    <span v-if="checkIfThisAllowed(props.item.id, 8, 'Sep')">Disabled</span>
                    <span v-else>Allowed</span>
                  </v-chip>
                </td>
                <td style="padding:0px;">
                  <v-chip :color="checkIfThisAllowed(props.item.id, 9, 'Oct') ? 'grey lighten-1 white--text' : 'green white--text'" label small @click="allowOrNot(props.item.id, props.item.store_id, 9, 'Oct')">
                    <span v-if="checkIfThisAllowed(props.item.id, 9, 'Oct')">Disabled</span>
                    <span v-else>Allowed</span>
                  </v-chip>
                </td>
                <td style="padding:0px;">
                  <v-chip :color="checkIfThisAllowed(props.item.id, 10, 'Nov') ? 'grey lighten-1 white--text' : 'green white--text'" label small @click="allowOrNot(props.item.id, props.item.store_id, 10,'Nov')">
                    <span v-if="checkIfThisAllowed(props.item.id, 10, 'Nov')">Disabled</span>
                    <span v-else>Allowed</span>
                  </v-chip>
                </td>
                <td style="padding:0px;">
                  <v-chip :color="checkIfThisAllowed(props.item.id, 11, 'Dec') ? 'grey lighten-1 white--text' : 'green white--text'" label small @click="allowOrNot(props.item.id, props.item.store_id, 11,'Dec')">
                    <span v-if="checkIfThisAllowed(props.item.id, 11, 'Dec')">Disabled</span>
                    <span v-else>Allowed</span>
                  </v-chip>
                </td>
              </tr>
            </template>
          </v-data-table>
          
        </div>
        
      </v-card>
      <div>
        Click the label to toggle which one you want to allowed or disabled.
      </div>
    </v-container>
  </div>  
</template>
<script>
  export default{
    data:()=>({
      header_stores:[
        {
          text:'Store',
          align:'center',
          sortable:true,
          value:'store_id'
        },
        {
          text:'Jan',
          align:'Center',
          sortable:false,
        },
        {
          text:'Feb',
          align:'Center',
          sortable:false,
        },
        {
          text:'Mar',
          align:'Center',
          sortable:false,
        },
        {
          text:'Apr',
          align:'center',
          sortable:false,
        },
        {
          text:'May',
          align:'center',
          sortable:false,
        },
        {
          text:'Jun',
          align:'center',
          sortable:false,
        },
        {
          text:'Jul',
          align:'center',
          sortable:false,
        },
        ,
        {
          text:'Aug',
          align:'center',
          sortable:false,
        },
        {
          text:'Sep',
          align:'center',
          sortable:false,
        },
        {
          text:'Oct',
          align:'center',
          sortable:false,
        },
        {
          text:'Nov',
          align:'center',
          sortable:false,
        },
        {
          text:'Dec',
          align:'center',
          sortable:false,
        }
      ],
      v_year: null,
      items_year: [],
      item_stores:[],
      total_items:0,
      pagination:{
        rowsPerPage:5
      },
      search_store: null,
      v_company: null,
      item_company: [
        {text:'All', value: 'all'},
        {text:'Hiflyer', value: 'hiflyer'},
        {text:'Olympus', value: 'olympus'},
      ],
      exempt_items: [],
      date:null,
      menu:false
    }),
    watch:{
      search_store:{
       handler:_.debounce(function(val){
      
       },500),
       deep:true 
      }
    },
    methods:{
      checkIfThisAllowed(id,month_num, month_txt){
        //console.log(id +' '+month_num +' '+ month_txt);
        for(var obj in this.exempt_items){
          if(
            this.exempt_items[obj].store_id == id &&
            this.exempt_items[obj].month_num == month_num &&
            this.exempt_items[obj].month_txt == month_txt ){
              return true
          } 
        }
        return false;
      }, 
      disallowed(){

      },
      allowOrNot(id,store_id, m_num, m_txt){
        const obj = {
          url:'/ktool/api/insert_update',
          data:{
            id: id,
            store_id: store_id,
            m_num: m_num,
            m_txt: m_txt
          },
          method:'POST'
        }

        this.$store.dispatch('COMMIT_ACTION_NO_FILE',obj)
          .then(response=>{
            if(response.status === 'success'){
              this.getDataFromApi();
            }else{
              this.getDataFromApi();
            }
          })
      },
      getDataFromApi(){
        const obj = {
          url: '/ktool/api/exempt_store',
          method:'POST',
          data: {}
        }
        this.$store.dispatch('COMMIT_ACTION_NO_FILE',obj)
          .then(response=>{
            this.total_items = response.total_items
            this.exempt_items = response.exempt_stores
            this.item_stores = response.data
          })
          .catch(error=>{

          })
      }
    },
    created(){
      this.getDataFromApi();
      
    },
    mounted(){
      var shot = document.getElementById('table_container').children[0].children[0]
        shot.addEventListener('scroll', z => {
        
          const cells = document.querySelectorAll('th:nth-child(-n+1), td:nth-child(-n+1)')
          for (var i = cells.length - 1; i >= 0; i--) {
            const cell = cells[i]
            cell.style = `transform:translateX(${z.srcElement.scrollLeft}px);`
          }
        })
    }
  }
</script>
<style scoped>

span >>> .chip__content{
      cursor: pointer;
   }

>>> table.table thead th{
  transition: none !important;
}

>>> .table th:nth-child(-n+1){
  background: #F7F7F7;
}
>>> .table td:nth-child(-n+1){
  background: #F7F7F7;
}
>>> .tooltip{
  position: inherit !important;
}
>>> .chip{
  position: inherit !important;
}
>>> .chip__content{
  z-index: auto !important;
}



</style>
