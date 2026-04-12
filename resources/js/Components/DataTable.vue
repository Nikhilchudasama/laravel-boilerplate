<script setup lang="ts">
import Pagination from './Pagination.vue';
import ColumnManagement from './ColumnManagement.vue';
import ExportDropDown from './ExportDropDown.vue';
import { ChevronUpIcon, ChevronDownIcon, MagnifyingGlassIcon, AdjustmentsHorizontalIcon } from '@heroicons/vue/24/outline';
import { computed, onMounted, reactive, watch } from 'vue';
import axios from 'axios';
import { debounce } from 'lodash';

const props = defineProps<{
  columns: {
    key: string;
    label: string;
    sortable?: boolean;
    hidden?: boolean;
    headerClass?: string;
    bodyClass?: string;
  }[];
  fetchUrl: string;
  perPageRecordLimits?: number[];
  allowColumnCustomization?: boolean;
  localStorageKey?: string;
  allowExcelExport?: boolean;
  extraParams?: Record<string, unknown>;
  showSelection?: boolean;
  primaryKey?: string;
}>();

const emit = defineEmits(['update:selected-ids']);

const state = reactive({
  isDataFetching: false,
  perPageRecordLimits: props.perPageRecordLimits || [10, 25, 50, 100],
  perPage: 10,
  sortDirection: 'desc',
  sortBy: 'created_at',
  searchText: '',
  totalRecords: 0,
  records: [] as Record<string, unknown>[],
  currentPage: 1,
  paginationStats: { from: 0, to: 0, total: 0 },
  customizedColumns: [] as typeof props.columns,
  isColumnModalOpen: false,
  selectedIds: new Set<string>(),
});

const pk = props.primaryKey || 'id';

const isAllSelected = computed(() => {
  return state.records.length > 0 && state.records.every(row => state.selectedIds.has(String(row[pk])));
});

const isSomeSelected = computed(() => {
  return state.selectedIds.size > 0 && !isAllSelected.value;
});

const toggleAll = () => {
  if (isAllSelected.value) {
    state.records.forEach(row => state.selectedIds.delete(String(row[pk])));
  } else {
    state.records.forEach(row => state.selectedIds.add(String(row[pk])));
  }
  emit('update:selected-ids', Array.from(state.selectedIds));
};

const toggleRow = (id: string | number) => {
  const stringId = String(id);
  if (state.selectedIds.has(stringId)) {
    state.selectedIds.delete(stringId);
  } else {
    state.selectedIds.add(stringId);
  }
  emit('update:selected-ids', Array.from(state.selectedIds));
};

const resetSelection = () => {
  state.selectedIds.clear();
  emit('update:selected-ids', []);
};

watch(() => props.extraParams, () => {
  state.currentPage = 1;
  fetchRecords();
}, { deep: true });


// Initialize columns
const visibleColumns = computed(() => {
  return state.customizedColumns.filter(col => !col.hidden);
});

onMounted(() => {
  if (props.allowColumnCustomization && props.localStorageKey) {
    const stored = localStorage.getItem(props.localStorageKey);
    if (stored) {
      state.customizedColumns = JSON.parse(stored);
    } else {
      state.customizedColumns = JSON.parse(JSON.stringify(props.columns));
    }
  } else {
    state.customizedColumns = JSON.parse(JSON.stringify(props.columns));
  }
  fetchRecords();
});

const updateColumns = (newColumns: typeof props.columns) => {
  state.customizedColumns = newColumns;
  if (props.localStorageKey) {
    localStorage.setItem(props.localStorageKey, JSON.stringify(newColumns));
  }
};

const sortRecords = (columnKey: string) => {
  if (state.sortBy === columnKey) {
    state.sortDirection = state.sortDirection === 'asc' ? 'desc' : 'asc';
  } else {
    state.sortBy = columnKey;
    state.sortDirection = 'asc';
  }
  state.currentPage = 1;
  fetchRecords();
};

const fetchRecords = () => {
  state.isDataFetching = true;

  const params = {
    per_page: state.perPage,
    page: state.currentPage,
    sort_direction: state.sortDirection,
    sort_by: state.sortBy,
    search_text: state.searchText,
    ...props.extraParams,
  };


  axios.get(props.fetchUrl, { params })
    .then((response) => {
      state.isDataFetching = false;
      state.records = response.data.data;
      state.totalRecords = response.data.total_records;

      // Calculate stats client-side based on simplified response
      const from = (state.currentPage - 1) * state.perPage + 1;
      const to = Math.min(state.currentPage * state.perPage, state.totalRecords);

      state.paginationStats = {
        from: state.totalRecords > 0 ? from : 0,
        to: to,
        total: state.totalRecords,
      };
    })
    .catch((error) => {
      console.error("Error fetching data:", error);
      state.isDataFetching = false;
    });
};

const changeCurrentPage = (pageNumber: number) => {
  state.currentPage = pageNumber;
  fetchRecords();
};

const exportExcel = () => {
  // Construct export URL by replacing current route name or appending /export
  // Assuming convention: route('admin.users.index') -> route('admin.users.export')
  // But we passed fetchUrl as specific string (e.g. /admin/users). 
  // We can append /export to it, or pass an export-url prop. 
  // Simpler: Helper to append /export to fetchUrl or just query params.

  // Using window.location to trigger download
  const url = new URL(props.fetchUrl + '/export', window.location.origin);

  if (state.searchText) url.searchParams.append('search_text', state.searchText);
  if (state.sortBy) url.searchParams.append('sort_by', state.sortBy);
  if (state.sortDirection) url.searchParams.append('sort_direction', state.sortDirection);

  window.location.href = url.toString();
};

const updatePerPage = (event: Event) => {
  const target = event.target as HTMLSelectElement;
  state.perPage = parseInt(target.value);
  state.currentPage = 1;
  fetchRecords();
};

const updateSearchText = debounce((event: Event) => {
  const target = event.target as HTMLInputElement;
  state.searchText = target.value;
  state.currentPage = 1;
  fetchRecords();
}, 500);

// Helper for sort icon
const getSortIcon = (key: string) => {
  if (state.sortBy !== key) return null;
  return state.sortDirection === 'asc' ? ChevronUpIcon : ChevronDownIcon;
};

// Initial fetch
onMounted(() => {
  fetchRecords();
});

defineExpose({
  resetSelection,
  fetchRecords,
});
</script>

<template>
  <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
    <!-- Header / Search -->
    <div
      class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-700/50 rounded-t-lg"
    >
      <div class="relative w-full max-w-md">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <MagnifyingGlassIcon class="h-5 w-5 text-gray-400" />
        </div>
        <input
          :value="state.searchText"
          type="text"
          placeholder="Search..."
          class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition duration-150 ease-in-out"
          @input="updateSearchText"
        >
      </div>
      <div class="flex items-center space-x-2">
        <slot
          name="bulk-actions"
          :selected-ids="Array.from(state.selectedIds)"
        />

        <ExportDropDown
          v-if="allowExcelExport"
          @export-excel="exportExcel"
        />

        <button
          v-if="allowColumnCustomization"
          class="p-2 text-gray-400 hover:text-gray-500 rounded-full hover:bg-gray-100 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500"
          title="Customize Columns"
          @click="state.isColumnModalOpen = true"
        >
          <AdjustmentsHorizontalIcon class="h-5 w-5" />
        </button>
        <slot name="header-actions" />
      </div>
    </div>

    <ColumnManagement
      :is-open="state.isColumnModalOpen"
      :columns="state.customizedColumns"
      @close="state.isColumnModalOpen = false"
      @update:columns="updateColumns"
    />

    <!-- Table -->
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-700">
          <tr>
            <th
              v-if="showSelection"
              scope="col"
              class="px-6 py-3 text-left"
            >
              <input
                type="checkbox"
                :checked="isAllSelected"
                :indeterminate="isSomeSelected"
                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded dark:bg-gray-800 dark:border-gray-600"
                @change="toggleAll"
              >
            </th>
            <th
              v-for="col in visibleColumns"
              :key="col.key"
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap"
              :class="{ 'cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600': col.sortable }"
              @click="col.sortable && sortRecords(col.key)"
            >
              <div class="flex items-center space-x-1">
                <span>{{ col.label }}</span>
                <component
                  :is="getSortIcon(col.key)"
                  v-if="getSortIcon(col.key)"
                  class="h-4 w-4"
                />
              </div>
            </th>
            <th
              scope="col"
              class="relative px-6 py-3"
            >
              <span class="sr-only">Actions</span>
            </th>
          </tr>
        </thead>
        <tbody
          v-if="state.isDataFetching"
          class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
        >
          <tr
            v-for="n in 5"
            :key="'loading-' + n"
          >
            <td
              :colspan="columns.length + 1"
              class="px-6 py-4"
            >
              <div class="animate-pulse flex space-x-4">
                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4" />
              </div>
            </td>
          </tr>
        </tbody>
        <tbody
          v-else
          class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"
        >
          <tr
            v-for="(row, index) in state.records"
            :key="index"
            :class="[
              state.selectedIds.has(String(row[pk])) ? 'bg-indigo-50/50 dark:bg-indigo-900/20' : '',
              'hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors'
            ]"
          >
            <td
              v-if="showSelection"
              class="px-6 py-4 whitespace-nowrap"
            >
              <input
                type="checkbox"
                :checked="state.selectedIds.has(String(row[pk]))"
                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded dark:bg-gray-800 dark:border-gray-600"
                @change="toggleRow(String(row[pk]))"
              >
            </td>
            <td
              v-for="col in visibleColumns"
              :key="col.key"
              class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100"
            >
              <slot
                :name="`cell-${col.key}`"
                :row="row"
              >
                {{ row[col.key] }}
              </slot>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <slot
                name="actions"
                :row="row"
              />
            </td>
          </tr>
          <tr v-if="state.records.length === 0">
            <td
              :colspan="columns.length + (showSelection ? 2 : 1)"
              class="px-6 py-12 text-center text-gray-500 dark:text-gray-400"
            >
              No records found.
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div
      class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between bg-gray-50 dark:bg-gray-700/50 rounded-b-lg"
    >
      <div class="flex items-center space-x-2">
        <span class="text-sm text-gray-700 dark:text-gray-300">Show</span>
        <select
          :value="state.perPage"
          class="block w-20 py-1 px-2 text-sm border border-gray-300 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
          @change="updatePerPage"
        >
          <option
            v-for="limit in state.perPageRecordLimits"
            :key="limit"
            :value="limit"
          >
            {{ limit }}
          </option>
        </select>
        <span class="text-sm text-gray-700 dark:text-gray-300">entries</span>
      </div>

      <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-end">
        <div class="mr-4">
          <p class="text-sm text-gray-700 dark:text-gray-300">
            Showing
            <span class="font-medium">{{ state.paginationStats.from }}</span>
            to
            <span class="font-medium">{{ state.paginationStats.to }}</span>
            of
            <span class="font-medium">{{ state.paginationStats.total }}</span>
            results
            <span v-if="state.totalRecords && state.paginationStats.total < state.totalRecords">
              (filtered from <span class="font-medium">{{ state.totalRecords }}</span> total entries)
            </span>
          </p>
        </div>
        <div>
          <Pagination
            :current-page="state.currentPage"
            :per-page="state.perPage"
            :total-records="state.totalRecords"
            @page-change="changeCurrentPage"
          />
        </div>
      </div>
    </div>
  </div>
</template>
