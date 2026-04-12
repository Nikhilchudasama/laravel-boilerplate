<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
  UserIcon,
  ShieldCheckIcon,
  ArrowRightOnRectangleIcon,
  Cog6ToothIcon
} from '@heroicons/vue/24/outline';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';

defineOptions({ layout: FrontendLayout });

const props = defineProps<{
  auth?: {
    user: {
      name: string;
      email: string;
      avatar_url: string;
      type: string;
    };
  };
}>();
</script>

<template>

  <Head title="User Dashboard" />

  <div class="min-h-screen">
    <main class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
      <!-- Welcome Header -->
      <div class="mb-10">
        <h1 class="text-3xl font-extrabold text-zinc-900 dark:text-white tracking-tight">
          Welcome back, {{ props.auth?.user?.name || 'User' }}!
        </h1>
        <p class="mt-2 text-lg text-zinc-500 dark:text-zinc-400">
          Manage your account settings and view your activity here.
        </p>
      </div>

      <!-- Quick Stats/Cards -->
      <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <!-- Profile Card -->
        <div
          class="bg-white dark:bg-zinc-900 overflow-hidden shadow-sm hover:shadow-md transition-shadow rounded-2xl border border-zinc-200 dark:border-zinc-800 p-6">
          <div class="flex items-center space-x-4 mb-4">
            <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-xl">
              <UserIcon class="h-6 w-6 text-blue-600" />
            </div>
            <h3 class="text-lg font-bold text-zinc-900 dark:text-white">
              Personal Profile
            </h3>
          </div>
          <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">
            Keep your personal information and contact details up to date.
          </p>
          <Link href="/profile"
            class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-500">
            Edit Profile
            <ArrowRightOnRectangleIcon class="h-4 w-4 ml-1" />
          </Link>
        </div>

        <!-- Security Card -->
        <div
          class="bg-white dark:bg-zinc-900 overflow-hidden shadow-sm hover:shadow-md transition-shadow rounded-2xl border border-zinc-200 dark:border-zinc-800 p-6">
          <div class="flex items-center space-x-4 mb-4">
            <div class="p-3 bg-green-50 dark:bg-green-900/20 rounded-xl">
              <ShieldCheckIcon class="h-6 w-6 text-green-600" />
            </div>
            <h3 class="text-lg font-bold text-zinc-900 dark:text-white">
              Security & Privacy
            </h3>
          </div>
          <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">
            Manage your password, 2FA settings, and active login sessions.
          </p>
          <Link href="/security"
            class="inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-500">
            Review Settings
            <ArrowRightOnRectangleIcon class="h-4 w-4 ml-1" />
          </Link>
        </div>

        <!-- Admin Bypass Notification (Demo) -->
        <div v-if="props.auth?.user?.type === 'admin'"
          class="bg-indigo-50 dark:bg-indigo-900/20 overflow-hidden shadow-sm rounded-2xl border border-indigo-200 dark:border-indigo-800/50 p-6">
          <div class="flex items-center space-x-4 mb-4">
            <div class="p-3 bg-indigo-600 rounded-xl">
              <Cog6ToothIcon class="h-6 w-6 text-white" />
            </div>
            <h3 class="text-lg font-bold text-indigo-900 dark:text-indigo-100">
              Staff Access
            </h3>
          </div>
          <p class="text-sm text-indigo-700 dark:text-indigo-300 mb-6">
            You have administrative privileges. You can switch to the management panel.
          </p>
          <Link href="/admin/dashboard"
            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-xs font-bold rounded-lg hover:bg-indigo-700 transition-colors uppercase tracking-wider">
            Go to Admin Panel
          </Link>
        </div>
      </div>

      <!-- Coming Soon Placeholder -->
      <div class="mt-12 p-8 border-2 border-dashed border-zinc-200 dark:border-zinc-800 rounded-3xl text-center">
        <p class="text-zinc-500 dark:text-zinc-600 font-medium">
          More customized features for your account will appear here soon.
        </p>
      </div>
    </main>

    <!-- Minimal Footer -->
    <footer class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 border-t border-zinc-200 dark:border-zinc-800 mt-auto">
      <p class="text-center text-xs text-zinc-400 uppercase tracking-widest font-bold">
        &copy; {{ new Date().getFullYear() }} Laravel Boilerplate
      </p>
    </footer>
  </div>
</template>
