<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps<{
  properties: {
    attributes?: Record<string, unknown>;
    old?: Record<string, unknown>;
  };
}>();

const changes = computed(() => {
  const attributes = props.properties?.attributes || {};
  const old = props.properties?.old || {};

  // Get all unique keys from both attributes and old
  const keys = Array.from(new Set([...Object.keys(attributes), ...Object.keys(old)]));

  // Filter out common metadata and identical values
  return keys
    .filter(key => !['id', 'created_at', 'updated_at', 'password', 'remember_token'].includes(key))
    .map(key => ({
      field: key,
      oldValue: old[key],
      newValue: attributes[key],
      isChanged: JSON.stringify(old[key]) !== JSON.stringify(attributes[key])
    }))
    .filter(item => item.isChanged);
});

const formatValue = (value: unknown) => {
  if (value === null) return 'null';
  if (value === undefined) return 'undefined';
  if (typeof value === 'boolean') return value ? 'true' : 'false';
  if (typeof value === 'object') return JSON.stringify(value, null, 2);
  return value.toString();
};
</script>

<template>
  <div class="overflow-x-auto">
    <table
      v-if="changes.length"
      class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800"
    >
      <thead class="bg-zinc-50 dark:bg-zinc-900/50">
        <tr>
          <th
            scope="col"
            class="px-4 py-2 text-left text-xs font-medium text-zinc-500 uppercase"
          >
            Field
          </th>
          <th
            scope="col"
            class="px-4 py-2 text-left text-xs font-medium text-zinc-500 uppercase"
          >
            Old Value
          </th>
          <th
            scope="col"
            class="px-4 py-2 text-left text-xs font-medium text-zinc-500 uppercase"
          >
            New Value
          </th>
        </tr>
      </thead>
      <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
        <tr
          v-for="change in changes"
          :key="change.field"
        >
          <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-zinc-900 dark:text-zinc-100">
            {{ change.field }}
          </td>
          <td class="px-4 py-2 text-sm text-zinc-500 dark:text-zinc-400 break-words max-w-xs">
            <span class="px-2 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded line-through">
              {{ formatValue(change.oldValue) }}
            </span>
          </td>
          <td class="px-4 py-2 text-sm text-zinc-500 dark:text-zinc-400 break-words max-w-xs">
            <span class="px-2 py-0.5 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded">
              {{ formatValue(change.newValue) }}
            </span>
          </td>
        </tr>
      </tbody>
    </table>
    <div
      v-else
      class="py-4 text-center text-sm text-zinc-500 italic"
    >
      No specific attribute changes recorded or visible.
    </div>
  </div>
</template>
