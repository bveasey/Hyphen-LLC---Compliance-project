<template>
   <div>
      <slot
         name="trigger"
         :show="show"
      >
         <VBtn
            @click="show"
         >
            <slot name="label">
               <VIcon>mdi-delete</VIcon>
            </slot>
         </VBtn>
      </slot>
      <VDialog
         :width="width"
         v-model="isActive"
         @input="onInput"
      >
         <VCard>
            <VCardTitle
               class="text-h5"
               v-if="title || $slots.title"
            >
               <slot name="title">
                  {{ title }}
               </slot>
            </VCardTitle>

            <VCardText>
               <slot
                  name="content"
                  v-if="content || $slots.content"
               >
                  {{ content }}
               </slot>
            </VCardText>

            <VDivider />

            <VCardActions>
               <VBtn
                  @click="close"
                  class="mr-4"
                  >Cancel</VBtn
               >
               <VBtn
                  color="red"
                  @click="destroy"
                  >Delete</VBtn
               >
            </VCardActions>
         </VCard>
      </VDialog>
   </div>
</template>

<script>
export default {
   props: {
      width: {
         type: [String, Number, null],
         default: 400,
      },
      content: {
         type: [String, null],
         default: 'Are you sure you want to delete this?',
      },
      title: {
         type: [String, null],
         default: 'Heads up!',
      },
   },
   data: () => ({
      isActive: false,
   }),
   methods: {
      destroy() {
         this.$emit('destroy');
         this.isActive = false;
      },
      show() {
         this.isActive = true;
      },
      close() {
         this.isActive = false;
         this.$emit('cancel');
      },
      // if the user hits the escape key or clicks the bg
      onInput(value) {
         if (!value) {
            this.$emit('cancel');
         }
      },
   },
};
</script>
