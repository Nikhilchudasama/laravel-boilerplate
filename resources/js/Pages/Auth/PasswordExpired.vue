<script setup lang="ts">
import { useForm, Head } from '@inertiajs/vue3';
import { LockClosedIcon } from '@heroicons/vue/24/outline';
import CommonButton from '@/Components/Form/Button.vue';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';

defineOptions({ layout: FrontendLayout });

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.expired.update'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-900 px-4 py-12 sm:px-6 lg:px-8">
    <Head title="Password Expired" />

    <div class="max-w-md w-full space-y-8 bg-white dark:bg-gray-800 p-8 rounded-xl shadow-2xl">
      <div class="text-center">
        <div
          class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-amber-100 dark:bg-amber-900/30"
        >
          <LockClosedIcon class="h-8 w-8 text-amber-600 dark:text-amber-500" />
        </div>
        <h2 class="mt-6 text-3xl font-extrabold text-gray-900 dark:text-white">
          Password Expired
        </h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
          For your security, your password has expired. Please verify your current password and create a new
          one to continue.
        </p>
      </div>

      <form
        class="mt-8 space-y-6"
        @submit.prevent="submit"
      >
        <div class="space-y-4">
          <!-- Current Password -->
          <div>
            <label
              for="current_password"
              class="block text-sm font-medium text-gray-700 dark:text-gray-300"
            >
              Current Password
            </label>
            <div class="mt-1">
              <input
                id="current_password"
                v-model="form.current_password"
                type="password"
                required
                class="appearance-none block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 dark:text-white dark:bg-gray-700 sm:text-sm"
              >
            </div>
            <p
              v-if="form.errors.current_password"
              class="mt-1 text-sm text-red-600 dark:text-red-400"
            >
              {{ form.errors.current_password }}
            </p>
          </div>

          <!-- New Password -->
          <div>
            <label
              for="password"
              class="block text-sm font-medium text-gray-700 dark:text-gray-300"
            >
              New Password
            </label>
            <div class="mt-1">
              <input
                id="password"
                v-model="form.password"
                type="password"
                required
                class="appearance-none block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 dark:text-white dark:bg-gray-700 sm:text-sm"
              >
            </div>
            <p
              v-if="form.errors.password"
              class="mt-1 text-sm text-red-600 dark:text-red-400"
            >
              {{ form.errors.password }}
            </p>
          </div>

          <!-- Confirm Password -->
          <div>
            <label
              for="password_confirmation"
              class="block text-sm font-medium text-gray-700 dark:text-gray-300"
            >
              Confirm New Password
            </label>
            <div class="mt-1">
              <input
                id="password_confirmation"
                v-model="form.password_confirmation"
                type="password"
                required
                class="appearance-none block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 dark:text-white dark:bg-gray-700 sm:text-sm"
              >
            </div>
          </div>
        </div>

        <div>
          <CommonButton
            type="submit"
            variant="primary"
            size="lg"
            class="w-full justify-center"
            :disabled="form.processing"
          >
            Update Password
          </CommonButton>
        </div>
      </form>
    </div>
  </div>
</template>
