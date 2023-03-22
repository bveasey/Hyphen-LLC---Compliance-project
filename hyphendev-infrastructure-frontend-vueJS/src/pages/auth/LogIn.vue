<script setup>
  import { computed, ref } from "vue";
  import { AuthenticationService } from "@/services";
  import { useAuthenticationStore } from "@/stores";
  import { useRouter } from 'vue-router';
  import { getEmailValidation } from "@/composables/Validation/emailValidation.js";

  const router = useRouter();

  const authStore = useAuthenticationStore();

  const email = ref('');
  const password = ref('');
  const loading = ref(false);
  const form = ref(null);
  const valid = ref(false);

  const generalError = ref('');
  const validationErrors = ref([]);

  const emailRules = getEmailValidation();
  const passwordRules = [
    v => !!v || 'Password is required',
  ];

  function login() {
    loading.value = true;
    validationErrors.value = [];
    generalError.value = '';

    AuthenticationService.login(email.value, password.value)
        .then(() => router.push({name: 'dashboard'}))
        .catch((error) => {
          switch (error.response.status) {
            case 422:
              validationErrors.value = error.response.data.errors;
              break;
            default:
              generalError.value = "Authentication Failed";
              form.value.reset();
          };

          loading.value = false;
        })
  }

  const enabled = computed(() => !loading.value && valid.value);
</script>

<template>
  <div style="max-width: 500px;" class="d-flex ma-auto flex-column">
    <div class="pa-4">
      <h1>Welcome to Hyphen</h1>
    </div>
    <v-card class="justify-center pa-3">
      <v-card-title>Login</v-card-title>

      <v-alert
          class="mb-4"
          v-if="generalError"
          density="compact"
          type="error"
          variant="outlined"
      >{{ generalError }}</v-alert>

      <v-form @submit.prevent="login()" ref="form" v-model="valid">
        <v-text-field
            :rules="emailRules"
            v-model="email"
            label="Email"
            required
        >
        </v-text-field>
        <v-text-field
            type="password"
            :rules="passwordRules"
            v-model="password"
            label="Password"
            required
        >
        </v-text-field>
        <v-btn
          :loading="loading"
          :disabled="!enabled"
          color="primary"
          elevation="2"
          type="submit"
        > Login</v-btn>
      </v-form>
    </v-card>
  </div>
</template>