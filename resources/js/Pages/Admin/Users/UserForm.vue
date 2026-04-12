<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import TextInput from '@/Components/Form/TextInput.vue';
import SelectInput from '@/Components/Form/SelectInput.vue';
import CheckboxGroup from '@/Components/Form/CheckboxGroup.vue';
import Button from '@/Components/Form/Button.vue';
import { ArrowLeftIcon } from '@heroicons/vue/24/outline';
import type { UserFormData } from '@/types/forms';

const props = defineProps<{
    user?: {
        id: string;
        name: string;
        email: string;
        type: 'admin' | 'user';
        active: boolean;
        roles: Array<{ id: string; name: string }>;
    } | null;
    roles: Array<{ id: string; name: string }>;
}>();

const isEditing = !!props.user;

const form = useForm<UserFormData>({
    name: props.user?.name ?? '',
    email: props.user?.email ?? '',
    password: '',
    type: (props.user?.type ?? 'user') as 'admin' | 'user',
    active: props.user?.active ? 1 : 0,
    roles: props.user?.roles.map(r => Number(r.id)) ?? [] as number[],
});

const submit = () => {
    if (isEditing) {
        form.post(route('admin.users.update', props.user!.id));
    } else {

        form.post(route('admin.users.store'));
    }
};

const typeOptions = [
    { value: 'admin', label: 'Admin' },
    { value: 'user', label: 'User' },
];

const activeOptions = [
    { value: 1, label: 'Active' },
    { value: 0, label: 'Inactive' },
];

const mappedRoles = props.roles.map(r => ({
    id: r.name,
    name: r.name
}));
</script>

<template>
  <Head :title="isEditing ? 'Edit User' : 'Create User'" />

  <div class="max-w-2xl mx-auto pb-12">
    <div class="flex items-center space-x-4 mb-6">
      <Link
        :href="route('admin.users.index')"
        class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
        title="Back to List"
      >
        <ArrowLeftIcon class="h-5 w-5" />
      </Link>
      <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
        {{ isEditing ? 'Edit User: ' + user?.name : 'Create New User' }}
      </h2>
    </div>

    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm space-y-6">
      <form
        class="space-y-6"
        @submit.prevent="submit"
      >
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <TextInput
            v-model="form.name"
            label="Name"
            :error="form.errors.name"
            required
          />
          <TextInput
            v-model="form.email"
            type="email"
            label="Email"
            :error="form.errors.email"
            required
          />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <SelectInput
            v-model="form.type"
            :options="typeOptions"
            label="User Type"
            :error="form.errors.type"
            required
          />
          <SelectInput
            v-model="(form.active as any)"
            :options="activeOptions"
            label="Status"
            :error="form.errors.active"
            required
          />
        </div>

        <TextInput
          v-model="(form.password as any)"
          type="password"
          :label="isEditing ? 'Password (leave blank to keep current)' : 'Password'"
          :error="form.errors.password"
          :required="!isEditing"
        />

        <div class="border-t border-gray-100 dark:border-gray-700 pt-6">
          <CheckboxGroup
            v-model="(form.roles as any)"
            :options="mappedRoles"
            label="Assign Roles"
          />
        </div>

        <div class="flex justify-end pt-6 border-t border-gray-100 dark:border-gray-700">
          <Button
            type="submit"
            :loading="form.processing"
          >
            {{ isEditing ? 'Update User' : 'Create User' }}
          </Button>
        </div>
      </form>
    </div>
  </div>
</template>
