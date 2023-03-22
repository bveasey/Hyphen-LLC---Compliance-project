<script setup>
import { AuthenticationService } from "@/services";
import { useRouter } from 'vue-router';

const router = useRouter();

function logout() {
  AuthenticationService.logout()
      .then(() => router.push({name: 'login'}));
}
</script>

<template>
  <VAppBar
      dense
      elevation="6"
  >
    <VAppBarTitle>Hyphen</VAppBarTitle>

    <VBtn to="/dashboard">Dashboard</VBtn>
    <VBtn to="/account-lookup">Account Lookup</VBtn>
    <VBtn to="/user-management">User management</VBtn>

    <VBtn
        elevation="2"
        rounded
        x-small
        @click="logout"
    >Logout</VBtn>
  </VAppBar>
</template>