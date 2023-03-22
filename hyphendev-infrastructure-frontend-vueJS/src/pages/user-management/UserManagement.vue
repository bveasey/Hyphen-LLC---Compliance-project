<script setup>
  import { onMounted, ref } from "vue";
  import { UserService} from '@/services';
  import UserRow from "./userRow.vue";
  import UserForm from "./UserForm.vue";

  onMounted(() => {
    getUsers();
  });

  const users = ref([]);
  const getUsers = () => {
    users.value = [];
    UserService.index()
        .then(({data}) => {
          users.value = data;
        });
  }

  const userForm = ref(null);

  const showUserForm = (user = null) => {
    userForm.value.setUser(user);
  }
</script>

<template>
  <div>
    <VCard class="py-3 px-4">
      <div class="d-flex justify-space-between">
        <h1 class="text-h5">User Management</h1>
        <VBtn @click="showUserForm()">
          <VIcon icon="mdi-account-plus" />
          <span class="px-3">New User</span>
        </VBtn>
      </div>
      <VTable>
        <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Roles</th>
          <th>Actions</th>
        </tr>
        </thead>
        <tbody>
          <UserRow v-for="user in users" :user="user" @edit="showUserForm" @updateUsers="getUsers"/>
        </tbody>
      </VTable>
    </VCard>
    <UserForm ref="userForm" @updateUsers="getUsers"/>
  </div>
</template>