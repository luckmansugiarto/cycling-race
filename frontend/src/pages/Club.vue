<template>
  <q-page>
    <div class="row q-mx-md justify-center">
        <div class="col">
            <q-btn            
              class="q-my-md"
              :label="lblAddClub"              
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
        <q-input 
          v-model="formData.title" 
          label="Club's Name"
          class="self-center"
          :rules="[ val => val && val.length > 0 || 'Club\'s name cannot be empty']"
        />
        <q-input 
          v-model="formData.address" 
          label="Club's Address"
          :rules="[ val => val && val.length > 0 || 'Address cannot be empty']"
        />
      </q-form>
    </q-slide-transition>
    
    <div class="row justify-center">
      <div class="col">        
        <q-table 
          title="Clubs"          
          :data="clubList" 
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
import { defineComponent, ref } from '@vue/composition-api';
import { usePromise } from 'vue-composable';
import moment from 'moment';

export default defineComponent({
  name: 'Clubs',
  data() {
    return {
      lblAddClub: 'Add Club',
      showsForm: false,
      columns: [
        {
          name: 'clubTitle',
          required: true,
          label: 'Name',
          align: 'left',
          field: (row: {title: string}) => row.title,          
          sortable: true
        },
        {
          name: 'clubAddress',
          required: true,
          label: 'Address',
          align: 'left',
          field: (row: {address: string}) => row.address,
          sortable: true
        }
      ],
    }
  },
  setup(props, ctx) {
    let clubList: Array = ref([]);
    let isLoading: unknown = ref(true);
    let formData: { title: string, address: string} = ref({title: '', address: ''});

    const fetchClubs = () => {
      usePromise(
        () => fetch('/api/clubs')
          .then(r => r.json(), true)
          .then((v: unknown) => {
            clubList.value = v;
            isLoading.value = false;
          })
      );
    };

    fetchClubs();

    const createNew = () => {      
      ctx.root.$axios.post('/api/clubs', formData.value)
        .then(r => {
          fetchClubs();
          formData.value = {title: '', address: ''};
          ctx.root.$q.notify({ type: 'positive', message: 'Saved' });
        })
        .catch(e => {
            ctx.root.$q.notify({ type: 'negative', message: 'Failed' });
        });        
    };

    return { createNew, formData, isLoading, clubList };
  },
  methods: {    
    toggleForm() {
      this.showsForm = !this.showsForm;

      if (this.showsForm) {
        this.lblAddClub = 'Cancel';
      } else {
        this.lblAddClub = 'Add Club';
      }
    }
  }
});
</script>
