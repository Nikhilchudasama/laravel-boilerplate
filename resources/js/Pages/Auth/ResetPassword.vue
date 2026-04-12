<script setup lang="ts">
import { useForm, Head } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import type { ResetPasswordFormData } from '@/types/forms';
import TextInput from '@/Components/Form/TextInput.vue';
import Button from '@/Components/Form/Button.vue';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';

defineOptions({ layout: FrontendLayout });

const props = defineProps<{
    token: string;
    email: string;
}>();

const form = useForm<ResetPasswordFormData>({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.update'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
  <Head title="Reset Password" />

  <div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 dark:text-white">
          Reset your password
        </h2>
      </div>

      <form
        class="mt-8 space-y-6"
        @submit.prevent="submit"
      >
        <div class="space-y-4">
          <TextInput
            v-model="form.email"
            type="email"
            label="Email address"
            :error="form.errors.email"
            required
            readonly
          />

          <TextInput
            v-model="form.password"
            type="password"
            label="New Password"
            placeholder="New Password"
            :error="form.errors.password"
            required
            autofocus
          />

          <TextInput
            v-model="form.password_confirmation"
            type="password"
            label="Confirm Password"
            placeholder="Confirm Password"
            required
          />
        </div>

        <div>
          <Button
            type="submit"
            :disabled="form.processing"
            class="w-full"
          >
            Reset Password
          </Button>
        </div>
      </form>
    </div>
  </div>
</template>
