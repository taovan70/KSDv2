<script setup>
import {CModal, CModalHeader, CModalBody, CModalTitle} from '@coreui/vue';

const props = defineProps({
  title: String,
  visible: Boolean
})

const emit = defineEmits(['close'])
</script>

<template>
  <CModal alignment="center" scrollable :visible="props.visible" @close="() => { emit('close', true) }">
    <CModalHeader>
      <CModalTitle>{{ props.title }}</CModalTitle>
    </CModalHeader>
    <CModalBody>
      <slot/>
    </CModalBody>
  </CModal>
</template>

<style scoped>
:deep .btn-close {
  box-sizing: content-box;
  width: 1em;
  height: 1em;
  padding: .25em;
  background: #fff url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='rgba%2844, 56, 74, 0.95%29'%3e%3cpath d='M.293.293a1 1 0 0 1 1.414 0L8 6.586 14.293.293a1 1 0 1 1 1.414 1.414L9.414 8l6.293 6.293a1 1 0 0 1-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 0 1-1.414-1.414L6.586 8 .293 1.707a1 1 0 0 1 0-1.414z'/%3e%3c/svg%3e") center/1em auto no-repeat;
  border: 0;
  border-radius: .375rem;
  opacity: .5;
}
</style>
