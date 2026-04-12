<script setup lang="ts">
import Modal from '@/Components/Modal.vue';
import Button from '@/Components/Form/Button.vue';
import { ExclamationTriangleIcon } from '@heroicons/vue/24/outline';

defineProps<{
  show: boolean;
  title: string;
  message: string;
  confirmText?: string;
  cancelText?: string;
  type?: 'danger' | 'warning' | 'info';
  loading?: boolean;
}>();

const emit = defineEmits(['close', 'confirm']);

const close = () => {
  emit('close');
};

const confirm = () => {
  emit('confirm');
};

const typeClasses = {
  danger: 'bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400',
  warning: 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400',
  info: 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400',
};
</script>

<template>
  <Modal
    :show="show"
    max-width="md"
    @close="close"
  >
    <div class="p-6">
      <div class="sm:flex sm:items-start">
        <div
          :class="typeClasses[type || 'danger']"
          class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full sm:mx-0 sm:h-10 sm:w-10"
        >
          <ExclamationTriangleIcon
            class="h-6 w-6"
            aria-hidden="true"
          />
        </div>
        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
          <h3
            id="modal-title"
            class="text-lg font-semibold leading-6 text-gray-900 dark:text-white"
          >
            {{ title }}
          </h3>
          <div class="mt-2">
            <p class="text-sm text-gray-500 dark:text-gray-400">
              {{ message }}
            </p>
          </div>
        </div>
      </div>

      <div class="mt-6 sm:flex sm:flex-row-reverse space-y-3 sm:space-y-0">
        <Button
          :variant="(type === 'danger' ? 'danger' : 'primary') as any"
          class="sm:ml-3 w-full sm:w-auto"
          :loading="loading"
          @click="confirm"
        >
          {{ confirmText || 'Confirm' }}
        </Button>
        <Button
          variant="secondary"
          class="w-full sm:w-auto"
          @click="close"
        >
          {{ cancelText || 'Cancel' }}
        </Button>
      </div>
    </div>
  </Modal>
</template>
