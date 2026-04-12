<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    modelValue: string | number | null;
    options: { value: string | number; label: string }[];
    label?: string;
    error?: string;
    placeholder?: string;
    required?: boolean;
    disabled?: boolean;
}>();

const emit = defineEmits(['update:modelValue']);

const value = computed({
    get() {
        return props.modelValue;
    },
    set(value) {
        emit('update:modelValue', value);
    },
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
      <select
        v-model="value"
        :disabled="disabled"
        class="block w-full px-3 py-2 border rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 transition duration-150 ease-in-out"
        :class="{ 'border-red-500 focus:ring-red-500': error, 'opacity-50 cursor-not-allowed': disabled }"
      >
        <option
          v-if="placeholder"
          value=""
          disabled
          selected
        >
          {{ placeholder }}
        </option>
        <option
          v-for="option in options"
          :key="option.value"
          :value="option.value"
        >
          {{ option.label }}
        </option>
      </select>
      <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-300">
        <svg
          class="fill-current h-4 w-4"
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 20 20"
        >
          <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
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
