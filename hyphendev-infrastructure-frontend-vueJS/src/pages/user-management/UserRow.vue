<script setup>
  import TheDeleteDialog from "@/components/TheDeleteDialog.vue";
  import { UserService } from '@/services';

  const props = defineProps(['user']);
  const emit = defineEmits(['updateUsers', 'edit']);

  const deleteUser = () => {
    UserService.destroy(props.user.id)
        .catch(() => {})
        .finally(() => {
          emit('updateUsers');
        });
  }
</script>
<template>
  <tr>
    <td>{{ props.user.name }}</td>
    <td>{{ props.user.email }}</td>
    <td>
      <VChip class="ma-2" v-for="role in props.user.roles">
        {{ role.name }}
      </VChip>
    </td>
    <td>
      <div class="d-flex">
        <VBtn @click="$emit('edit', props.user)">
          <VIcon icon="mdi-account-edit"/>
        </VBtn>
        <TheDeleteDialog @destroy="deleteUser" class="ml-2"/>
      </div>
    </td>
  </tr>
</template>