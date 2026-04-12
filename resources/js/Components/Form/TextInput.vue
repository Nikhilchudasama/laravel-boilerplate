<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
    modelValue: string | number | null;
    label?: string;
    type?: string;
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
    <input
      v-model="value"
      :type="type || 'text'"
      :placeholder="placeholder"
      :disabled="disabled"
      class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 transition duration-150 ease-in-out"
      :class="{ 'border-red-500 focus:ring-red-500': error, 'opacity-50 cursor-not-allowed': disabled }"
    >
    <div
      v-if="error"
      class="text-red-500 text-sm mt-1"
    >
      {{ error }}
    </div>
  </div>
</template>
