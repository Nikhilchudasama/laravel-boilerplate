<script setup lang="ts">
import { onMounted, ref, watch, onBeforeUnmount } from 'vue';
import flatpickr from 'flatpickr';
import { Instance } from 'flatpickr/dist/types/instance';
import 'flatpickr/dist/flatpickr.css';

const props = defineProps<{
  modelValue: string | Date | null;
  label?: string;
  error?: string;
  placeholder?: string;
  required?: boolean;
  disabled?: boolean;
  config?: object;
}>();

const emit = defineEmits(['update:modelValue']);

const inputRef = ref(null);
let fp: Instance | null = null;

onMounted(() => {
  if (inputRef.value) {
    fp = flatpickr(inputRef.value, {
      ...props.config,
      defaultDate: props.modelValue as flatpickr.Options.DateOption,
      onChange: (selectedDates, dateStr) => {
        emit('update:modelValue', dateStr);
      },
    });
  }
});

watch(() => props.modelValue, (newValue) => {
  if (fp && newValue) {
    fp.setDate(newValue, false);
  }
});

onBeforeUnmount(() => {
  if (fp) {
    fp.destroy();
  }
});
</script>

<template>
  <div class="mb-4">
    <label
      v-if="label"
      class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2"
    >
      {{ label }} <span
        v-if="required"
        class="text-red-500"
      >*</span>
    </label>
    <div class="relative">
      <input
        ref="inputRef"
        :value="modelValue"
        :placeholder="placeholder"
        :disabled="disabled"
        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 transition duration-150 ease-in-out"
        :class="{ 'border-red-500 focus:ring-red-500': error, 'opacity-50 cursor-not-allowed': disabled }"
      >
      <div
        class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-300"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
          stroke-width="1.5"
          stroke="currentColor"
          class="size-5"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"
          />
        </svg>
      </div>
    </div>
    <div
      v-if="error"
      class="text-red-500 text-sm mt-1"
    >
      {{ error }}
    </div>
  </div>
</template>
