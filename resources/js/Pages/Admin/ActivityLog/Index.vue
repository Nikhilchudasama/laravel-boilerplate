<script setup lang="ts">
import { ref, reactive } from 'vue';
import { Head } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import DataTable from '@/Components/DataTable.vue';
import Modal from '@/Components/Modal.vue';
import ActivityChanges from './ActivityChanges.vue';
import { formatDate } from '@/Services/date';
import { EyeIcon, XMarkIcon } from '@heroicons/vue/24/outline';


interface Activity {
  id: number;
  log_name: string;
  description: string;
  subject_type: string;
  causer: string;
  properties: {
    attributes?: Record<string, unknown>;
    old?: Record<string, unknown>;
  };
  created_at: string;
}

defineProps<{
  logNames: string[];
  causers: { id: number; name: string }[];
}>();

const columns = [
  { key: 'created_at', label: 'Time', sortable: true },
  { key: 'log_name', label: 'Log', sortable: true },
  { key: 'description', label: 'Action', sortable: true },
  { key: 'causer', label: 'Causer' },
  { key: 'subject_type', label: 'Resource' },
];

const filters = reactive({
  log_name: '',
  causer_id: '',
  date_from: '',
  date_to: '',
});

const selectedActivity = ref<Activity | null>(null);
const isModalOpen = ref(false);

const openDetails = (row: Activity) => {
  selectedActivity.value = row;
  isModalOpen.value = true;
};


const closeDetails = () => {
  isModalOpen.value = false;
  setTimeout(() => {
    selectedActivity.value = null;
  }, 300);
};

const resetFilters = () => {
  filters.log_name = '';
  filters.causer_id = '';
  filters.date_from = '';
  filters.date_to = '';
};

const asActivity = (row: unknown) => row as Activity;
</script>

<template>
  <Head title="Activity Log" />

  <div class="flex justify-between items-center mb-6">
    <div>
      <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">
        Activity Log
      </h2>
      <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
        Monitor system changes and user actions
      </p>
    </div>
  </div>

  <!-- Filters -->
  <div class="mb-6 bg-white dark:bg-zinc-900 p-4 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <div>
        <label class="block text-xs font-medium text-zinc-500 uppercase mb-1">Log Name</label>
        <select
          v-model="filters.log_name"
          class="block w-full pl-3 pr-10 py-2 text-sm border-zinc-300 dark:border-zinc-800 dark:bg-zinc-950 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-xl"
        >
          <option value="">
            All Logs
          </option>
          <option
            v-for="name in logNames"
            :key="name"
            :value="name"
          >
            {{ name }}
          </option>
        </select>
      </div>
      <div>
        <label class="block text-xs font-medium text-zinc-500 uppercase mb-1">Causer</label>
        <select
          v-model="filters.causer_id"
          class="block w-full pl-3 pr-10 py-2 text-sm border-zinc-300 dark:border-zinc-800 dark:bg-zinc-950 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-xl"
        >
          <option value="">
            All Users
          </option>
          <option
            v-for="causer in causers"
            :key="causer.id"
            :value="causer.id"
          >
            {{ causer.name }}
          </option>
        </select>
      </div>
      <div>
        <label class="block text-xs font-medium text-zinc-500 uppercase mb-1">Date From</label>
        <input
          v-model="filters.date_from"
          type="date"
          class="block w-full px-3 py-2 text-sm border-zinc-300 dark:border-zinc-800 dark:bg-zinc-950 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-xl"
        >
      </div>
      <div>
        <label class="block text-xs font-medium text-zinc-500 uppercase mb-1">Date To</label>
        <input
          v-model="filters.date_to"
          type="date"
          class="block w-full px-3 py-2 text-sm border-zinc-300 dark:border-zinc-800 dark:bg-zinc-950 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-xl"
        >
      </div>
    </div>
    <div class="mt-4 flex justify-end">
      <button
        class="text-sm font-medium text-zinc-500 hover:text-indigo-600 transition-colors"
        @click="resetFilters"
      >
        Reset Filters
      </button>
    </div>
  </div>

  <DataTable
    :fetch-url="route('admin.activity-log.index')"
    :columns="columns"
    :extra-params="filters"
    allow-column-customization
    local-storage-key="activity_log_columns"
  >
    <template #cell-created_at="{ row }">
      <div class="flex flex-col">
        <span class="text-xs font-medium text-zinc-900 dark:text-zinc-100 whitespace-nowrap">{{
          formatDate(asActivity(row).created_at) }}</span>
        <span class="text-[10px] text-zinc-500">{{ asActivity(row).created_at.split(' ')[1] }}</span>
      </div>
    </template>

    <template #cell-log_name="{ row }">
      <span
        class="px-2 py-1 text-[10px] font-bold uppercase tracking-wider rounded-md bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400"
      >
        {{ asActivity(row).log_name }}
      </span>
    </template>

    <template #cell-description="{ row }">
      <span
        :class="{
          'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400': asActivity(row).description === 'created',
          'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400': asActivity(row).description === 'updated',
          'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400': asActivity(row).description === 'deleted',
          'bg-zinc-100 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300': !['created', 'updated', 'deleted'].includes(asActivity(row).description)
        }"
        class="px-2 py-1 text-xs font-medium rounded-md capitalize"
      >
        {{ asActivity(row).description }}
      </span>
    </template>

    <template #actions="{ row }">
      <button
        class="p-2 text-zinc-400 hover:text-indigo-600 transition-colors"
        @click="openDetails(asActivity(row))"
      >
        <EyeIcon class="h-5 w-5" />
      </button>
    </template>
  </DataTable>

  <!-- Details Modal -->
  <Modal
    :show="isModalOpen"
    max-width="4xl"
    @close="closeDetails"
  >
    <div class="p-6">
      <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-bold text-zinc-900 dark:text-white">
          Activity Details
        </h3>
        <button
          class="p-2 text-zinc-400 hover:text-zinc-500"
          @click="closeDetails"
        >
          <XMarkIcon class="h-6 w-6" />
        </button>
      </div>

      <div
        v-if="selectedActivity"
        class="space-y-6"
      >
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-zinc-50 dark:bg-zinc-900/50 p-4 rounded-2xl">
          <div>
            <p class="text-xs font-medium text-zinc-500 uppercase">
              Description
            </p>
            <p class="text-sm font-semibold text-zinc-900 dark:text-white capitalize">
              {{
                selectedActivity.description }}
            </p>
          </div>
          <div>
            <p class="text-xs font-medium text-zinc-500 uppercase">
              Performed At
            </p>
            <p class="text-sm text-zinc-900 dark:text-white">
              {{ formatDate(selectedActivity.created_at) }}
            </p>
          </div>
          <div>
            <p class="text-xs font-medium text-zinc-500 uppercase">
              Causer
            </p>
            <p class="text-sm text-zinc-900 dark:text-white">
              {{ selectedActivity.causer }}
            </p>
          </div>
          <div>
            <p class="text-xs font-medium text-zinc-500 uppercase">
              Resource
            </p>
            <p class="text-sm text-zinc-900 dark:text-white">
              {{ selectedActivity.subject_type }}
            </p>
          </div>
        </div>

        <div>
          <h4 class="text-sm font-bold text-zinc-900 dark:text-white mb-4">
            Changes Detail
          </h4>
          <div class="border border-zinc-200 dark:border-zinc-800 rounded-2xl overflow-hidden">
            <ActivityChanges :properties="selectedActivity.properties" />
          </div>
        </div>
      </div>
    </div>
  </Modal>
</template>
