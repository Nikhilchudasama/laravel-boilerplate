<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

import { route } from 'ziggy-js';
import DataTable from '@/Components/DataTable.vue';
import { FingerPrintIcon } from '@heroicons/vue/24/outline';
import { dateOnly } from '@/Services/date';
import { useAuth } from '@/Composables/useAuth';
import ConfirmModal from '@/Components/ConfirmModal.vue';

interface User {
  id: number;
  name: string;
  email: string;
  type: string;
  active: boolean;
  created_at: string;
}

defineProps<{
  users: {
    data: User[];
    links: unknown[];
    meta: unknown;
  };
  filters?: {
    global?: string;
  };
  totalRecords?: number;
}>();

const { user: currentUser } = useAuth();

const impersonateUser = (id: string | number) => {
  if (confirm('Are you sure you want to impersonate this user?')) {
    router.visit(route('admin.users.impersonate', id));
  }
};

const columns = [
  { key: 'name', label: 'Name', sortable: true },
  { key: 'email', label: 'Email', sortable: true },
  { key: 'type', label: 'Type', sortable: true },
  { key: 'active', label: 'Status', sortable: true },
  { key: 'created_at', label: 'Joined', sortable: true },
];

const selectedUserIds = ref<string[]>([]);
const dataTableRef = ref<InstanceType<typeof DataTable> | null>(null);

const confirmModal = ref({
  show: false,
  title: '',
  message: '',
  type: 'danger' as 'danger' | 'warning' | 'info',
  onConfirm: () => { },
  loading: false
});

const closeConfirm = () => {
  confirmModal.value.show = false;
};

const handleBulkDelete = (ids: string[]) => {
  confirmModal.value = {
    show: true,
    title: 'Delete Users',
    message: `Are you sure you want to delete ${ids.length} selected users? This action cannot be undone.`,
    type: 'danger',
    loading: false,
    onConfirm: () => {
      confirmModal.value.loading = true;
      router.post(route('admin.users.bulk-delete'), { ids }, {
        onSuccess: () => {
          selectedUserIds.value = [];
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

const handleBulkToggleActive = (ids: string[], active: boolean) => {
  const status = active ? 'activate' : 'deactivate';
  confirmModal.value = {
    show: true,
    title: `${active ? 'Activate' : 'Deactivate'} Users`,
    message: `Are you sure you want to ${status} ${ids.length} selected users?`,
    type: active ? 'info' : 'warning',
    loading: false,
    onConfirm: () => {
      confirmModal.value.loading = true;
      router.post(route('admin.users.bulk-toggle-active'), { ids, active }, {
        onSuccess: () => {
          selectedUserIds.value = [];
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

const asUser = (row: unknown) => row as User;
</script>

<template>
  <Head title="Users" />

  <div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
      Users
    </h2>
    <Link
      v-if="$can('create_users')"
      :href="route('admin.users.create')"
      class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
    >
      Create User
    </Link>
  </div>

  <DataTable
    ref="dataTableRef"
    v-model:selected-ids="selectedUserIds"
    :fetch-url="route('admin.users.index')"
    :columns="columns"
    allow-column-customization
    allow-excel-export
    show-selection
    local-storage-key="users_table_columns"
  >
    <template #bulk-actions="{ selectedIds }">
      <div
        v-if="selectedIds.length > 0"
        class="flex items-center space-x-2 animate-in fade-in slide-in-from-left-2 duration-200"
      >
        <span class="text-sm text-gray-500 mr-2">{{ selectedIds.length }} selected</span>
        <button
          class="px-3 py-1 text-xs font-semibold bg-green-50 text-green-700 border border-green-200 rounded-md hover:bg-green-100 transition-colors"
          @click="handleBulkToggleActive(selectedIds, true)"
        >
          Activate
        </button>
        <button
          class="px-3 py-1 text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200 rounded-md hover:bg-amber-100 transition-colors"
          @click="handleBulkToggleActive(selectedIds, false)"
        >
          Deactivate
        </button>
        <button
          class="px-3 py-1 text-xs font-semibold bg-red-50 text-red-700 border border-red-200 rounded-md hover:bg-red-100 transition-colors"
          @click="handleBulkDelete(selectedIds)"
        >
          Delete
        </button>
      </div>
    </template>

    <template #cell-type="{ row }">
      <span
        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 uppercase"
      >
        {{ asUser(row).type }}
      </span>
    </template>
    <template #cell-active="{ row }">
      <span
        :class="asUser(row).active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
      >
        {{ asUser(row).active ? 'Active' : 'Inactive' }}
      </span>
    </template>
    <template #cell-created_at="{ row }">
      {{ dateOnly(asUser(row).created_at) }}
    </template>
    <template #actions="{ row }">
      <div class="flex items-center space-x-3">
        <button
          v-if="$can('edit_users') && Number(asUser(row).id) !== Number(currentUser?.id)"
          class="text-gray-400 hover:text-indigo-600 transition-colors"
          title="Impersonate User"
          @click="impersonateUser(asUser(row).id)"
        >
          <FingerPrintIcon class="h-5 w-5" />
        </button>
        <Link
          v-if="$can('edit_users')"
          :href="route('admin.users.edit', asUser(row).id)"
          class="text-indigo-600 hover:text-indigo-900 dark:hover:text-indigo-400"
        >
          Edit
        </Link>
      </div>
    </template>
  </DataTable>

  <ConfirmModal
    :show="confirmModal.show"
    :title="confirmModal.title"
    :message="confirmModal.message"
    :type="confirmModal.type"
    :loading="confirmModal.loading"
    @close="closeConfirm"
    @confirm="confirmModal.onConfirm"
  />
</template>
