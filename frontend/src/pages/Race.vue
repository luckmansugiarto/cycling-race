<template>
  <q-page>
    <div class="row q-mx-md justify-center">
        <div class="col">
            <q-btn            
              class="q-my-md"
              :label="lblAddRace"              
              type="button"
              color="primary"
              @click="toggleForm"
            ></q-btn>
            <q-btn            
              class="q-my-md q-mx-md"
              label="Save"              
              type="submit"
              color="positive"
              @click="createNew"
              v-show="showsForm"      
            ></q-btn>
        </div>
    </div>

    <q-slide-transition>
      <q-form v-show="showsForm" class="q-my-lg q-mx-md items-end justify-center" style="max-width: 300px">
        <q-select
          class="q-mb-xs"
          label="Select Club"
          transition-show="jump-up"
          transition-hide="jump-up"          
          v-model="selectedClub"
          :options="clubList"
          option-value="id"
          option-label="title"
          style="width: 250px"
        />
        <q-input 
          v-model="formData.title" 
          label="Race Name"          
          :rules="[ val => val && val.length > 0 || 'Race name cannot be empty']"
        />
        <q-input 
          v-model="formData.address" 
          label="Race Address"
          :rules="[ val => val && val.length > 0 || 'Race address cannot be empty']"
        />

        <q-input @click="showDate('start')" readonly label="Start Date" class="q-mt-xs" v-model="formData.start_date">
          <template v-slot:prepend>
            <q-icon name="event" class="cursor-pointer">
              <q-popup-proxy ref="qStartDateProxy" transition-show="scale" transition-hide="scale">
                <q-date v-model="formData.start_date" mask="YYYY-MM-DD HH:mm:ss">
                  <div class="row items-center justify-end">
                    <q-btn v-close-popup label="Close" color="primary" flat />
                  </div>
                </q-date>
              </q-popup-proxy>
            </q-icon>
          </template>

          <template v-slot:append>
            <q-icon name="access_time" class="cursor-pointer">
              <q-popup-proxy ref="qStartTimeProxy" transition-show="scale" transition-hide="scale">
                <q-time v-model="formData.start_date" mask="YYYY-MM-DD HH:mm:ss" format24h>
                  <div class="row items-center justify-end">
                    <q-btn v-close-popup label="Close" color="primary" flat />
                  </div>
                </q-time>
              </q-popup-proxy>
            </q-icon>
          </template>
        </q-input>

        <q-input @click="showDate('end')" readonly label="End Date" class="q-mt-xs" v-model="formData.end_date">
          <template v-slot:prepend>
            <q-icon name="event" class="cursor-pointer">
              <q-popup-proxy ref="qEndDateProxy" transition-show="scale" transition-hide="scale">
                <q-date v-model="formData.end_date" mask="YYYY-MM-DD HH:mm:ss">
                  <div class="row items-center justify-end">
                    <q-btn v-close-popup label="Close" color="primary" flat />
                  </div>
                </q-date>
              </q-popup-proxy>
            </q-icon>
          </template>

          <template v-slot:append>
            <q-icon name="access_time" class="cursor-pointer">
              <q-popup-proxy ref="qEndTimeProxy" transition-show="scale" transition-hide="scale">
                <q-time v-model="formData.end_date" mask="YYYY-MM-DD HH:mm:ss" format24h>
                  <div class="row items-center justify-end">
                    <q-btn v-close-popup label="Close" color="primary" flat />
                  </div>
                </q-time>
              </q-popup-proxy>
            </q-icon>
          </template>
        </q-input>

        <q-select
          class="q-my-xs"
          label="Select Statuses"
          transition-show="jump-up"
          transition-hide="jump-up"
          option-value="value"
          option-label="label"          
          v-model="formData.status"
          :options="statuses"          
          style="width: 250px"
          emit-value
        />

      </q-form>
    </q-slide-transition>

    <div class="row justify-center">
      <div class="col">        
        <q-table 
          title="Races"          
          :data="raceList" 
          :columns="columns" 
          row-key="name"
          :loading="isLoading"
        >
          <template v-slot:body-cell-raceTitle="props">
            <q-td>
              <router-link :to="'/races/' + props.row.id">
                {{ props.row.title }}
              </router-link>
            </q-td>
          </template>
        </q-table>     
      </div>
    </div>
  </q-page>
</template>

<script lang="ts">
import { defineComponent, ref, watch } from '@vue/composition-api';
import { usePromise } from 'vue-composable';
import moment from 'moment';
import { Race } from '../components/model';
import { isNil } from 'lodash';

export default defineComponent({
  name: 'Races',
  data() {
    return {
      lblAddRace: 'Add Race',
      columns: [
        {
          name: 'raceTitle',
          required: true,
          label: 'Race',
          align: 'left',
          field: (row: {title: string}) => row.title,          
          sortable: true
        },
        {
          name: 'raceClub',
          required: true,
          label: 'Club',
          align: 'left',
          field: (row: {club: {title: string}}) => row.club.title,          
          sortable: true
        },
        {
          name: 'raceAddress',
          required: true,
          label: 'Address',
          align: 'left',
          field: (row: {address: string}) => row.address,          
          sortable: true
        },
        {
          name: 'raceStatus',
          required: true,
          label: 'Status',
          align: 'left',
          field: (row: {status: string}) => row.status,        
          sortable: true
        },
        { name: 'startDate', align: 'left', label: 'Start Date', field: (row: {start_date: string}) => row.start_date, sortable: true, format: (val) => moment(String(val)).format('MMMM Do YYYY, h:mm:ss a') },       
        { name: 'endDate', align: 'left', label: 'End Date', field: (row: {end_date: string}) => row.end_date, sortable: true, format: (val) => moment(String(val)).format('MMMM Do YYYY, h:mm:ss a') }  
      ],
      showsForm: false,
      statuses: [
        { label: 'PENDING', value: 'PENDING' }, 
        { label: 'COMPLETED', value: 'COMPLETED' }
      ]
    }
  },
  setup(props, ctx) {
    let clubList: Array = ref([]);
    let raceList: Array = ref([]);
    let isLoading: unknown = ref(true);
    let formData: Race = ref({});
    let selectedClub: {title: string, address: string} = ref(null);

    const fetchData = (type: string) => {
      if (type === 'club') {
        usePromise(
          () => fetch('/api/clubs')
            .then(r => r.json(), true)
            .then((v: unknown) => {
              clubList.value = v;
            })
        );
      } else {
        usePromise(
          () => fetch('/api/races')
            .then(r => r.json(), true)
            .then((v: unknown) => {
              raceList.value = v;
              isLoading.value = false;              
            })            
        );
      }
    };

    const createNew = () => {      
      ctx.root.$axios.post('/api/races', formData.value)
        .then(r => {
          fetchData();
          resetForm();
          ctx.root.$q.notify({
            type: 'positive',
            message: 'Saved'
          });
        })
        .catch((e) => {
          ctx.root.$q.notify({ type: 'negative', message: 'Failed to save' });
        })
    };

    const resetForm = () => {
      formData.value = {
        address: '',
        club_id: null,
        end_date: '',        
        start_date: '',
        status: '',
        title: ''
      };
    }

    watch(selectedClub, (curVal, oldVal) => {
      if (!isNil(curVal)) {
        formData.value.club_id = curVal.id;
      }
    });

    resetForm();
    fetchData();
    fetchData('club');

    return { 
      clubList, 
      createNew, 
      formData, 
      isLoading, 
      raceList,
      selectedClub
    };
  },
  methods: {    
    showDate(type: string) {
      if (type === 'end') {
        this.$refs.qEndDateProxy.show();
      } else {
        this.$refs.qStartDateProxy.show();
      }
    },
    toggleForm() {
      this.showsForm = !this.showsForm;

      if (this.showsForm) {
        this.lblAddRace = 'Cancel';
      } else {
        this.lblAddRace = 'Add Race';
      }
    }
  }
});
</script>
