<script setup>
import {ref, computed} from "vue";
import { ServiceService } from '@/services'

const props = defineProps(['service', 'filter']);

const filter = computed(() => props.filter);

const name = ref('');
const email = ref('');
const status = ref('');
const searching = ref(false);

const search = (query) =>
{
  searching.value = true;
  name.value = "";
  email.value = "";
  status.value = "";

  ServiceService.search(props.service.id, query)
      .then(({data}) => {
        name.value = data.name;
        email.value = data.email;

        if (data.error) {
          status.value = "Error";
        } else {
          status.value = data.status;
        }
      }).catch((error) => {
        status.value = "Error";
      })
      .finally(() => {
        searching.value = false;
      });
}

const color = computed(() => {
  let green = '#008000bf';
  let red = '#ff0000bf';
  let yellow = '#ffc200bf';

  switch (status.value) {
    case 'Error':
    case 'Inactive':
      return red;

    case "Active":
      return green;

    case "Account not Found":
      return yellow;

    default:
      return 'white';
  }
});

const show = computed(() => {
  if (filter.value == 'All' || status.value == '') {
    return true;
  }

  if (filter.value == status.value) {
    return true;
  }

  if (filter.value == 'Inactive' && status.value == 'Account not Found') {
    return true;
  }

  return false;
});

defineExpose({search});

</script>
<template>
  <tr v-show="show">
    <td class="text-left">{{ service.name }}</td>
    <td class="text-left">{{ name }}</td>
    <td class="text-left">{{ email }}</td>
    <td class="text-left">
      <span v-show="!searching" :style="{ color: color, fontSize: '1.5rem'}">● </span>
      <span v-show="!searching">{{ status }}</span>
      <v-progress-circular
          color="primary"
          indeterminate
          v-show="searching"
          size="25"
      />
    </td>
  </tr>
</template>