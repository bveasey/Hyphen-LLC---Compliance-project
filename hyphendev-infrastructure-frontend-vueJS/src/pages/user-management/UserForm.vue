<script setup>
  import formErrors from '@/lib/formErrors'
  import { onMounted, ref } from "vue";
  import { RoleService, UserService } from '@/services';

  const emit = defineEmits(['updateUsers']);
  const { setFormErrors, clearFormErrors, formErrorBindings } = formErrors();

  const form = ref(null);
  const busy = ref(false);

  const defaultForm = {
    name: null,
    email: null,
    password: null,
    roles: []
  };

  const user = ref(null);

  const isEditing = ref(false);
  const showForm = ref(false);

  onMounted(() => {
    getRoles();
  });

  const setUser = (formData = null) => {
    clearFormErrors();
    user.value = formData;
    isEditing.value = !!user.value;
    form.value = {...defaultForm};

    if (isEditing.value) {
      form.value = {
        name: formData.name,
        email: formData.email,
        password: null,
        roles: formData.roles.map(role => role.id)
      };
    }
    showForm.value = true;
  }

  const submit = () => {
    clearFormErrors();
    busy.value = true;
    if (!isEditing.value) {
      create();
    } else {
      update();
    }
  }

  const create = () => {
    UserService.store(form.value)
        .then(() => {
          emit('updateUsers');
          form.value = {...defaultForm};
        })
        .catch((errors) => {
          setFormErrors(errors);
        })
        .finally(() => {
          busy.value = false;
        });
  }

  const update = () => {
    UserService.update(user.value.id, form.value)
        .then(() => {
          showForm.value = false;
        })
        .catch((errors) => {
          setFormErrors(errors);
        })
        .finally(() => {
          emit('updateUsers');
          busy.value = false;
        });
  }

  const availableRoles = ref([]);
  const getRoles = () => {
    RoleService.index()
        .then(({data}) => {
          availableRoles.value = data;
        });
  }

  defineExpose({setUser});
</script>
<template>
  <VDialog v-model="showForm">
    <VCard style="width: 400px;" class="pa-3">
      <VCardTitle>{{  isEditing ? 'Edit' : 'Create'  }} User</VCardTitle>
      <VForm @submit.prevent="submit" autocomplete="off" v-if="form" :disabled="busy">
        <VTextField
            outlined
            label="Name"
            v-model="form.name"
            v-bind="formErrorBindings('name')"/>
        <VTextField
            outlined
            label="Email"
            v-model="form.email"
            v-bind="formErrorBindings('email')"/>
        <VTextField
            outlined
            label="Password"
            type="password"
            autocomplete="new-password"
            v-model="form.password"
            v-bind="formErrorBindings('password')"/>
        <VSelect
            v-model="form.roles"
            :items="availableRoles"
            item-title="name"
            item-value="id"
            chips
            label="Roles"
            multiple
            v-bind="formErrorBindings('roles')"/>

        <div>
          <VBtn type="submit" class="mr-3" color="primary" :loading="busy">{{ isEditing ? 'Update' : 'Save' }}</VBtn>
          <VBtn type="button" @click="showForm = false">Cancel</VBtn>
        </div>
      </VForm>
    </VCard>
  </VDialog>
</template>