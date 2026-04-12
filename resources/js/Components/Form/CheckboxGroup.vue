<script setup lang="ts">

const props = defineProps<{
  modelValue: (string | number)[];
  options: { id: string | number; name: string }[];
  label?: string;
  error?: string;
  disabled?: boolean;
}>();

const emit = defineEmits(['update:modelValue']);

const check = (id: string | number, checked: boolean) => {
  let updated = [...props.modelValue];
  if (checked) {
    updated.push(id);
  } else {
    updated = updated.filter((item) => item !== id);
  }
  emit('update:modelValue', updated);
};
</script>

<template>
  <div class="mb-4">
    <label
      v-if="label"
      class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2"
    >
      {{ label }}
    </label>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <div
        v-for="(option, index) in options"
        :key="index"
        class="flex items-center"
      >
        <input
          :id="`checkbox-${option.id}`"
          type="checkbox"
          :value="option.id"
          :checked="modelValue.includes(option.id)"
          :disabled="disabled"
          class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded cursor-pointer disabled:opacity-50"
          @change="(e) => check(option.id, (e.target as HTMLInputElement).checked)"
        >
        <label
          :for="`checkbox-${option.id}`"
          class="ml-2 block text-sm text-gray-700 dark:text-gray-300 cursor-pointer select-none"
          :class="{ 'font-semibold text-indigo-600 dark:text-indigo-400': modelValue.includes(option.id) }"
        >
          {{ option.name }}
        </label>
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
