<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import TextInput from '@/Components/Form/TextInput.vue';
import CheckboxGroup from '@/Components/Form/CheckboxGroup.vue';
import Button from '@/Components/Form/Button.vue';
import { ArrowLeftIcon } from '@heroicons/vue/24/outline';
import type { RoleFormData } from '@/types/forms';

const props = defineProps<{
    role?: {
        id: string;
        name: string;
        permissions: Array<{ id: string; name: string }>;
    } | null;
    permissions: Array<{
        id: string;
        name: string;
    }>;
}>();

const isEditing = !!props.role;

const form = useForm<RoleFormData>({
    name: props.role?.name ?? '',
    permissions: props.role?.permissions.map(p => p.id) ?? [] as (string | number)[],
});

const submit = () => {
    if (isEditing) {
        form.post(route('admin.roles.update', props.role!.id));
    } else {
        form.post(route('admin.roles.store'));
    }
};
</script>

<template>
  <Head :title="isEditing ? 'Edit Role' : 'Create Role'" />

  <div class="max-w-2xl mx-auto">
    <div class="flex items-center space-x-4 mb-6">
      <Link
        :href="route('admin.roles.index')"
        class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
        title="Back to List"
      >
        <ArrowLeftIcon class="h-5 w-5" />
      </Link>
      <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
        {{ isEditing ? 'Edit Role: ' + role?.name : 'Create New Role' }}
      </h2>
    </div>

    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
      <form @submit.prevent="submit">
        <TextInput
          v-model="form.name"
          label="Role Name"
          :error="form.errors.name"
          required
        />

        <div class="mt-6">
          <CheckboxGroup
            v-model="(form.permissions as any)"
            :options="permissions"
            label="Permissions"
          />
        </div>

        <div class="flex justify-end mt-6">
          <Button
            type="submit"
            :loading="form.processing"
          >
            {{ isEditing ? 'Update Role' : 'Create Role' }}
          </Button>
        </div>
      </form>
    </div>
  </div>
</template>
