<script setup lang="ts">
import { useForm, Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import type { LoginFormData } from '@/types/forms';
import {
  EnvelopeIcon,
  LockClosedIcon,
  EyeIcon,
  EyeSlashIcon,
  ArrowRightOnRectangleIcon
} from '@heroicons/vue/24/outline';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import Button from '@/Components/Form/Button.vue';

defineOptions({ layout: FrontendLayout });

const showPassword = ref(false);

const form = useForm<LoginFormData>({
  email: '',
  password: '',
  remember: false,
});

const submit = () => {
  form.post('/login', {
    onFinish: () => form.reset('password'),
  });
};
</script>

<template>

  <Head title="Login" />

  <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <!-- Background Pattern -->
    <div class="absolute inset-0 z-0 opacity-10 dark:opacity-20 pointer-events-none overflow-hidden">
      <svg class="absolute left-0 top-0 h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none" fill="none">
        <defs>
          <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
            <path d="M 40 0 L 0 0 0 40" fill="none" stroke="currentColor" stroke-width="0.5"
              class="text-zinc-300 dark:text-zinc-700" />
          </pattern>
        </defs>
        <rect width="100%" height="100%" fill="url(#grid)" />
      </svg>
    </div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10">
      <div class="text-center">
        <div
          class="inline-flex items-center justify-center p-3 bg-indigo-600 rounded-xl shadow-lg shadow-indigo-500/20 mb-4">
          <LockClosedIcon class="h-8 w-8 text-white" />
        </div>
        <h2 class="text-3xl font-extrabold text-zinc-900 dark:text-white tracking-tight">
          Welcome Back
        </h2>
        <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
          Sign in to your account to continue
        </p>
      </div>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md relative z-10">
      <div
        class="bg-white dark:bg-zinc-900 py-8 px-4 shadow-xl shadow-zinc-200/50 dark:shadow-none sm:rounded-2xl sm:px-10 border border-zinc-200 dark:border-zinc-800">
        <form class="space-y-6" @submit.prevent="submit">
          <!-- Email field -->
          <div>
            <label for="email" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">
              Email address
            </label>
            <div class="relative group">
              <div
                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400 group-focus-within:text-indigo-500 transition-colors">
                <EnvelopeIcon class="h-5 w-5" />
              </div>
              <input id="email" v-model="form.email" type="email" autocomplete="email" required autofocus
                class="block w-full pl-11 pr-4 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-800 rounded-xl text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all sm:text-sm"
                placeholder="name@example.com">
            </div>
            <p v-if="form.errors.email" class="mt-1.5 text-xs text-red-500 font-medium px-1">
              {{ form.errors.email }}
            </p>
          </div>

          <!-- Password field -->
          <div>
            <label for="password" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">
              Password
            </label>
            <div class="relative group">
              <div
                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400 group-focus-within:text-indigo-500 transition-colors">
                <LockClosedIcon class="h-5 w-5" />
              </div>
              <input id="password" v-model="form.password" :type="showPassword ? 'text' : 'password'"
                autocomplete="current-password" required
                class="block w-full pl-11 pr-11 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-800 rounded-xl text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all sm:text-sm"
                placeholder="••••••••">
              <button type="button"
                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 transition-colors"
                @click="showPassword = !showPassword">
                <EyeIcon v-if="!showPassword" class="h-5 w-5" />
                <EyeSlashIcon v-else class="h-5 w-5" />
              </button>
            </div>
            <p v-if="form.errors.password" class="mt-1.5 text-xs text-red-500 font-medium px-1">
              {{ form.errors.password }}
            </p>
          </div>

          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <input id="remember-me" v-model="form.remember" type="checkbox"
                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 dark:focus:ring-offset-zinc-900 border-zinc-300 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-950 rounded transition-colors">
              <label for="remember-me" class="ml-2.5 block text-sm text-zinc-600 dark:text-zinc-400 select-none">
                Remember me
              </label>
            </div>

            <div class="text-sm">
              <Link :href="route('password.request')"
                class="font-semibold text-indigo-600 hover:text-indigo-500 transition-colors">
                Forgot password?
              </Link>
            </div>
          </div>

          <div>
            <Button type="submit" :loading="form.processing" full-width>
              <ArrowRightOnRectangleIcon class="h-5 w-5 mr-2" />
              Sign in
            </Button>
          </div>

          <div class="text-center">
            <p class="text-sm text-zinc-600 dark:text-zinc-400">
              Don't have an account?
              <Link href="/register" class="font-semibold text-indigo-600 hover:text-indigo-500 transition-colors">
                Create one now
              </Link>
            </p>
          </div>
        </form>
      </div>

      <p class="mt-8 text-center text-xs text-zinc-400 dark:text-zinc-600 uppercase tracking-widest font-bold">
        &copy; {{ new Date().getFullYear() }} Laravel Boilerplate
      </p>
    </div>
  </div>
</template>
