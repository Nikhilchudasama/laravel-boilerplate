<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import DataTable from '@/Components/DataTable.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';


interface Role {
  id: number;
  name: string;
  created_at: string;
}

defineProps<{
  roles: {
    data: Role[];
    links: unknown[];
    meta: unknown;
  };
  filters?: {
    global?: string;
  };
  totalRecords?: number;
}>();

const columns = [
  { key: 'name', label: 'Name', sortable: true },
  { key: 'created_at', label: 'Created At', sortable: true },
];

const selectedRoleIds = ref<string[]>([]);
const dataTableRef = ref<InstanceType<typeof DataTable> | null>(null);

const confirmModal = ref({
  show: false,
  title: '',
  message: '',
  onConfirm: () => { },
  loading: false
});

const closeConfirm = () => {
  confirmModal.value.show = false;
};

const handleBulkDelete = (ids: string[]) => {
  confirmModal.value = {
    show: true,
    title: 'Delete Roles',
    message: `Are you sure you want to delete ${ids.length} selected roles? This action cannot be undone.`,
    loading: false,
    onConfirm: () => {
      confirmModal.value.loading = true;
      router.post(route('admin.roles.bulk-delete'), { ids }, {
        onSuccess: () => {
          selectedRoleIds.value = [];
          dataTableRef.value?.resetSelection();
          dataTableRef.value?.fetchRecords();
          closeConfirm();
        },
        onFinish: () => {
          confirmModal.value.loading = false;
        }
      });
    }
  };
};

const asRole = (row: unknown) => row as Role;
</script>

<template>
  <Head title="Roles" />

  <div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
      Roles
    </h2>
    <Link
      v-if="$can('create_roles')"
      :href="route('admin.roles.create')"
      class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
    >
      Create Role
    </Link>
  </div>

  <DataTable
    ref="dataTableRef"
    v-model:selected-ids="selectedRoleIds"
    :fetch-url="route('admin.roles.index')"
    :columns="columns"
    allow-column-customization
    allow-excel-export
    show-selection
    local-storage-key="roles_table_columns"
  >
    <template #bulk-actions="{ selectedIds }">
      <div
        v-if="selectedIds.length > 0"
        class="flex items-center space-x-2 animate-in fade-in slide-in-from-left-2 duration-200"
      >
        <span class="text-sm text-gray-500 mr-2">{{ selectedIds.length }} selected</span>
        <button
          class="px-3 py-1 text-xs font-semibold bg-red-50 text-red-700 border border-red-200 rounded-md hover:bg-red-100 transition-colors"
          @click="handleBulkDelete(selectedIds)"
        >
          Delete Roles
        </button>
      </div>
    </template>

    <template #cell-created_at="{ row }">
      {{ new Date(asRole(row).created_at).toLocaleDateString() }}
    </template>
    <template #actions="{ row }">
      <Link
        v-if="$can('edit_roles')"
        :href="route('admin.roles.edit', asRole(row).id)"
        class="text-indigo-600 hover:text-indigo-900 dark:hover:text-indigo-400"
      >
        Edit
      </Link>
    </template>
  </DataTable>

  <ConfirmModal
    :show="confirmModal.show"
    :title="confirmModal.title"
    :message="confirmModal.message"
    :loading="confirmModal.loading"
    @close="closeConfirm"
    @confirm="confirmModal.onConfirm"
  />
</template>
