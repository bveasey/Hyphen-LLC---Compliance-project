<script setup>
  import { computed, ref, onMounted } from "vue";
  import ServiceRow from "@/components/ServiceRow.vue";
  import { getEmailValidation } from "@/composables/Validation/emailValidation.js";
  import { ServiceService } from "@/services";

  const pageError = ref(false);
  const services = ref([]);

  onMounted(() => {
    ServiceService.get()
        .then(({data}) => {
          services.value = data;
        })
        .catch((error) => {
          pageError.value = true;
        });
  });

  const form = ref(null);
  const valid = ref(false);
  const loading = ref(false);
  const enabled = computed(() => !loading.value && valid.value);

  const email = ref('');
  const emailRules = getEmailValidation();

  const filterAccounts = ref('All');

  const serviceRows = ref(null);
  const search = () => {
    serviceRows.value.forEach(row => row.search(email.value));
  }

</script>
<template>
  <VCard class="px-3 py-2 ma-2">
    <h1 class="pb-2 text-h5">Lookup Accounts by Email</h1>

    <VForm @submit.prevent="search()" ref="form" v-model="valid">
        <VTextField
            :rules="emailRules"
            v-model="email"
            label="Email"
            required
            variant="underlined"
            prepend-inner-icon="mdi-magnify"
        >
          <template v-slot:append>
            <VBtn
                color="primary"
                type="submit"
                :loading="loading"
                :disabled="!enabled"
            >Search</VBtn>
          </template>
        </VTextField>
    </VForm>
  </VCard>
  <VCard class="text-center px-3 py-2 ma-2">
    <div class="d-flex">
      <VRadioGroup v-model="filterAccounts" inline>
        <VRadio
            class="my-auto mx-2"
            label="All"
            value="All"
        >
        </VRadio>
        <VRadio
            class="my-auto mx-0"
            label="Active"
            value="Active"
        >
        </VRadio>
        <VRadio
            class="my-auto mx-2"
            label="Inactive"
            value="Inactive"
        >
        </VRadio>
      </VRadioGroup>
    </div>
    <p v-show="pageError" style="color: #ea2323e6">Failed to Load Services</p>
    <VTable
      fixed-header
  >
      <thead>
        <tr>
          <th class="text-left">
            Service
          </th>
          <th class="text-left">
            Name
          </th>
          <th class="text-left">
            Email
          </th>
          <th class="text-left">
            Status
          </th>
        </tr>
      </thead>
      <tbody>
        <ServiceRow
        v-for="service in services"
        ref="serviceRows"
        :service="service"
        :filter="filterAccounts">
        </ServiceRow>
      </tbody>
    </VTable>
  </VCard>
</template>