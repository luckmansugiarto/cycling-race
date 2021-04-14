<template>
  <q-page>
    <div class="row justify-center">
      <div class="col">
        <q-btn
            id="AddParticipants"
            class="q-my-md"
            :label="lblAddParticipant"
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
      <q-form v-show="showsForm" class="q-mt-xs q-mb-md q-mx-md items-end justify-center" style="max-width: 600px">
        <p>
          Add an existing participant or create a new one, and then add it into the race
        </p>

        <div class="row">
          <div class="col">
            <q-select
              class="q-mb-md"
              label="Select Existing Participant"
              transition-show="jump-up"
              transition-hide="jump-up"
              v-model="participant"
              :options="riderList"
              option-value="id"
              option-label="firstname"
              style="width: 250px"
            />
          </div>
          <div class="col">
            <q-btn
              type="button"
              color="primary"
              label="Clear"
              class="q-mt-md"
              @click="resetForm"
            ></q-btn>
          </div>
        </div>

        <q-input
          v-model="formData.firstname"
          label="Firstname"
          :rules="[ val => val && val.length > 0 || 'Firstname cannot be empty']"
        />

        <q-input
          v-model="formData.surname"
          label="Lastname"
          :rules="[ val => val && val.length > 0 || 'Lastname cannot be empty']"
        />

        <q-input
          type="number"
          v-model="formData.age"
          label="Age"
          :rules="[ val => (val >= 10 && val <= 99) || 'Invalid age']"
        />

        <q-select
          class="q-mb-md"
          label="Select Grading"
          transition-show="jump-up"
          transition-hide="jump-up"
          option-value="value"
          option-label="label"
          v-model="formData.grading"
          :options="gradings"
          style="width: 250px"
          emit-value
        />

        <q-select
          class="q-my-xs"
          label="Select Gender"
          transition-show="jump-up"
          transition-hide="jump-up"
          option-value="value"
          option-label="label"
          v-model="formData.gender"
          :options="genders"
          style="width: 250px"
          emit-value
        />

        <q-input
          type="number"
          v-model="formRaceResult.finish_position"
          label="Finish Position"
          :rules="[ val => (val >= 1 && val < riderList.length) || 'Invalid finish position']"
        />

        <q-input @click="showTime()" readonly label="Finish Time" class="q-mt-xs" v-model="finishTime">
          <template v-slot:append>
            <q-icon name="access_time" class="cursor-pointer">
              <q-popup-proxy ref="qTimeProxy" transition-show="scale" transition-hide="scale">
                <q-time v-model="finishTime" mask="HH:mm:ss" format24h with-seconds>
                  <div class="row items-center justify-end">
                    <q-btn v-close-popup label="Close" color="primary" flat />
                  </div>
                </q-time>
              </q-popup-proxy>
            </q-icon>
          </template>
        </q-input>

      </q-form>
    </q-slide-transition>

    <div class="row justify-center">
      <div class="col">
        <q-table
          title="Participants"
          :data="raceParticipants"
          :columns="columns"
          row-key="name"
          :loading="isLoading"
        ></q-table>
      </div>
    </div>
  </q-page>
</template>

<script lang="ts">
import { defineComponent, ref, watch } from '@vue/composition-api';
import { usePromise } from 'vue-composable';
import moment from 'moment';
import { isNil } from 'lodash';
import { Race, RaceResult, Rider } from '../components/model';

export default defineComponent({
  name: 'Participants',
  data() {
    return {
      lblAddParticipant: 'Add Participant',
      columns: [
        {
          name: 'riderName',
          required: true,
          label: 'Name',
          align: 'left',
          field: (row: {firstname: string, surname: string}) => row.firstname + (row.surname != '' ? ' ' +row.surname : ''),
          sortable: true
        },
        {
          name: 'riderGrading',
          required: true,
          label: 'Grade',
          align: 'left',
          field: (row: {grading: string}) => row.grading,
          sortable: true
        },
        {
          name: 'riderGender',
          required: true,
          label: 'Gender',
          align: 'left',
          field: (row: {gender: string}) => row.gender,
          sortable: true
        },
        {
          name: 'riderAge',
          required: true,
          label: 'age',
          align: 'left',
          field: (row: {age: number}) => row.age,
          sortable: true
        },
        {
          name: 'riderFinishPosition',
          required: true,
          label: 'Finished Position',
          align: 'center',
          field: (row: {pivot: {finish_position: string}}) => row.pivot.finish_position,
          sortable: true
        },
        {
          name: 'riderFinishTime',
          required: true,
          label: 'Finished Time',
          align: 'center',
          field: (row: {pivot: {finish_time: string}}) => row.pivot.finish_time,
          format: (val: string) => moment.unix(val).format('hh:mm:ss'),
          sortable: true
        },
      ],
      genders: [
        { label: 'Male', value: 'Male' },
        { label: 'Female', value: 'Female' },
        { label: 'Other', value: 'Other' }
      ],
      gradings: [
        { label: 'Master', value: 'Master' },
        { label: 'Advanced', value: 'Advanced' },
        { label: 'Intermediate', value: 'Intermediate' },
        { label: 'Beginner', value: 'Beginner' }
      ],
      showsForm: false
    }
  },
  setup(props, ctx) {
    let finishTime: string = ref('');
    let formData: Rider = ref({});
    let formRaceResult: RaceResult = ref({});
    let riderList: Array = ref([]);
    let raceDetails: Race = {};
    let raceParticipants: Array = ref([]);
    let isLoading: unknown = ref(true);
    let participant: Rider = ref(null);

    const fetchData = (type: string) => {
      usePromise(
        () => fetch('/api/riders')
          .then(r => r.json(), true)
          .then((v: Array) => riderList.value = v)
      );

      usePromise(
        () => fetch('/api/races')
          .then(r => r.json(), true)
          .then((v: Array) => {
            raceDetails = v.filter((el: {id: number}) => el.id === parseInt(ctx.root.$route.params.id));

            if (raceDetails.length === 1 && raceDetails[0].riders) {
              raceDetails = raceDetails[0];
              raceParticipants.value = raceDetails.riders;
            }

            isLoading.value = false;
          })
      );
    };

    const createNew = () => {
      let route = ctx.root.$route;
      let axios = ctx.root.$axios;

      if (isNil(participant.value)) {
        axios.post('/api/riders', formData.value)
          .then(v => {
            console.log(v.data);
            return axios.post('/api/races/' + route.params.id + '/riders/' + v.data.id, {
              finish_position: formRaceResult.value.finish_position,
              finish_time: formRaceResult.value.finish_time
            });
          })
          .then(r => {
            ctx.root.$q.notify({type: 'positive', message: 'Participant added into the race'});
            fetchData();
            resetForm();
          })
          .catch((e) => {
            ctx.root.$q.notify({ type: 'negative', message: 'Failed to add participant into the race' });
          });
      } else {
        axios.post('/api/races/' + route.params.id + '/riders/' + formRaceResult.value.rider_id, {
          finish_position: formRaceResult.value.finish_position,
          finish_time: formRaceResult.value.finish_time
        })
          .then(r => {
            ctx.root.$q.notify({type: 'positive', message: 'Participant added into the race'});
            fetchData();
            resetForm();
          })
          .catch((e) => {
            ctx.root.$q.notify({ type: 'negative', message: 'Failed to add participant into the race' });
          });
      }
    }

    const resetForm = () => {
      participant.value = null;
      formData.value = {
        age: null,
        firstname: null,
        gender: null,
        grading: '',
        surname: ''
      };
      formRaceResult.value = {
        finish_position: null,
        finish_time: null,
        rider_id: null
      };
    }

    watch(finishTime, (currentVal, oldVal) => {
      if (currentVal !== '') {
        formRaceResult.value.finish_time = moment(moment(raceDetails).format('YYYY-MM-DD') + ` ${currentVal}`).unix();
      }
    });

    watch(participant, (currentVal, oldVal) => {
      if (!isNil(currentVal)) {
        formData.value = currentVal;
        formRaceResult.value.rider_id = currentVal.id;
      }
    });

    resetForm();
    fetchData();

    return {
      createNew,
      finishTime,
      formData,
      formRaceResult,
      isLoading,
      participant,
      raceParticipants,
      resetForm,
      riderList
    };
  },
  methods: {
    showTime() {
      this.$refs.qTimeProxy.show();
    },
    toggleForm() {
      this.showsForm = !this.showsForm;

      if (this.showsForm) {
        this.lblAddParticipant = 'Cancel';
      } else {
        this.lblAddParticipant = 'Add Participant';
      }
    }
  }
});
</script>
