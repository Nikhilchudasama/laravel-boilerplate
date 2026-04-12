<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ShieldCheckIcon } from '@heroicons/vue/24/outline';
import Button from '@/Components/Form/Button.vue';
import { route } from 'ziggy-js';

const form = useForm({
    code: '',
});

const submit = () => {
    form.post(route('2fa.challenge'), {
        onFinish: () => form.reset('code'),
    });
};
</script>

<template>
  <Head title="Two-Factor Authentication" />

  <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-zinc-50 dark:bg-zinc-950">
    <div
      class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white dark:bg-zinc-900 shadow-xl overflow-hidden sm:rounded-2xl border border-zinc-200 dark:border-zinc-800"
    >
      <div class="flex flex-col items-center mb-6 text-center">
        <div class="p-3 bg-indigo-100 dark:bg-indigo-900/30 rounded-full mb-4">
          <ShieldCheckIcon class="h-8 w-8 text-indigo-600 dark:text-indigo-400" />
        </div>
        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">
          Two-Factor Authentication
        </h2>
        <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
          Please confirm access to your account by entering the authentication code provided by your
          authenticator application.
        </p>
      </div>

      <form @submit.prevent="submit">
        <div>
          <label
            for="code"
            class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2"
          >
            Authentication Code
          </label>
          <input
            id="code"
            v-model="form.code"
            type="text"
            inputmode="numeric"
            class="block w-full px-4 py-3 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-800 rounded-xl text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-center tracking-widest text-2xl font-bold"
            autofocus
            autocomplete="one-time-code"
            maxlength="6"
            placeholder="000000"
          >
          <p
            v-if="form.errors.code"
            class="mt-2 text-sm text-red-500 font-medium"
          >
            {{ form.errors.code }}
          </p>
        </div>

        <div class="mt-8">
          <Button
            type="submit"
            class="w-full justify-center py-3"
            :loading="form.processing"
          >
            Verify Code
          </Button>
        </div>

        <div class="mt-6 text-center">
          <Link
            :href="route('logout')"
            method="post"
            as="button"
            class="text-sm text-zinc-500 hover:text-indigo-600 font-medium"
          >
            Cancel and Log Out
          </Link>
        </div>
      </form>
    </div>
  </div>
</template>
