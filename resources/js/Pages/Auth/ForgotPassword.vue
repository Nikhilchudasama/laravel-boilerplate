<script setup lang="ts">
import { useForm, usePage, Head, Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import type { ForgotPasswordFormData } from '@/types/forms';
import TextInput from '@/Components/Form/TextInput.vue';
import Button from '@/Components/Form/Button.vue';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';

defineOptions({ layout: FrontendLayout });

const page = usePage();

const form = useForm<ForgotPasswordFormData>({
    email: '',
});

const submit = () => {
    form.post(route('password.email'), {
        onFinish: () => form.reset('email'),
    });
};
</script>

<template>
  <Head title="Forgot Password" />

  <div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 dark:text-white">
          Forgot your password?
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
          No problem. Just let us know your email address and we will email you a password reset link.
        </p>
      </div>

      <form
        class="mt-8 space-y-6"
        @submit.prevent="submit"
      >
        <div class="rounded-md shadow-sm -space-y-px">
          <TextInput
            v-model="form.email"
            type="email"
            label="Email address"
            placeholder="Email address"
            :error="form.errors.email"
            required
            autofocus
          />
        </div>

        <div
          v-if="(page.props as any).flash?.success"
          class="rounded-md bg-green-50 dark:bg-green-900/20 p-4"
        >
          <p class="text-sm text-green-800 dark:text-green-200">
            {{ (page.props as any).flash.success }}
          </p>
        </div>

        <div class="flex items-center justify-between">
          <Link
            :href="route('login')"
            class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400"
          >
            Back to login
          </Link>

          <Button
            type="submit"
            :disabled="form.processing"
          >
            Email Password Reset Link
          </Button>
        </div>
      </form>
    </div>
  </div>
</template>
