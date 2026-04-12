<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeftIcon, KeyIcon, ShieldCheckIcon } from '@heroicons/vue/24/outline';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import Button from '@/Components/Form/Button.vue';
import { route } from 'ziggy-js';
import type { PasswordFormData } from '@/types/forms';
import { useAuth } from '@/Composables/useAuth';

defineOptions({ layout: FrontendLayout });

const { user } = useAuth();

defineProps<{
  mfaSetup?: {
    secret: string;
    qr_code: string;
  };
  error?: string;
  success?: string;
}>();

const passwordForm = useForm<PasswordFormData>({
  current_password: '',
  password: '',
  password_confirmation: '',
});

const mfaEnableForm = useForm({
  code: '',
});

const updatePassword = () => {
  passwordForm.post(route('profile.password'), {
    preserveScroll: true,
    onSuccess: () => {
      passwordForm.reset();
    },
  });
};

const setupMFA = () => {
  mfaEnableForm.post(route('2fa.setup'), {
    preserveScroll: true,
  });
};

const enableMFA = () => {
  mfaEnableForm.post(route('2fa.enable'), {
    preserveScroll: true,
    onSuccess: () => {
      mfaEnableForm.reset();
    },
  });
};

const disableMFA = () => {
  if (confirm('Are you sure you want to disable 2FA? This will make your account less secure.')) {
    mfaEnableForm.post(route('2fa.disable'), {
      preserveScroll: true,
    });
  }
};
</script>

<template>
  <Head title="Security & Privacy" />

  <div class="min-h-screen">
    <div class="max-w-3xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
      <Link
        href="/dashboard"
        class="inline-flex items-center text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:text-indigo-600 mb-6"
      >
        <ArrowLeftIcon class="h-4 w-4 mr-2" />
        Back to Dashboard
      </Link>

      <div
        class="bg-white dark:bg-zinc-900 shadow-xl rounded-2xl border border-zinc-200 dark:border-zinc-800 overflow-hidden"
      >
        <div class="px-6 py-5 border-b border-zinc-200 dark:border-zinc-800">
          <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">
            Security & Privacy
          </h2>
          <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
            Manage your password and security
            settings
          </p>
        </div>

        <div class="p-6">
          <!-- Update Password -->
          <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
              <h3 class="text-lg font-medium leading-6 text-zinc-900 dark:text-white flex items-center">
                <KeyIcon class="h-5 w-5 mr-2 text-indigo-500" />
                Update Password
              </h3>
              <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                Ensure your account is using a long, random password to stay secure.
              </p>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
              <form @submit.prevent="updatePassword">
                <div class="space-y-4">
                  <div>
                    <label
                      for="current_password"
                      class="block text-sm font-medium text-zinc-700 dark:text-zinc-300"
                    >
                      Current Password
                    </label>
                    <input
                      id="current_password"
                      v-model="passwordForm.current_password"
                      type="password"
                      autocomplete="current-password"
                      class="mt-1 block w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-800 rounded-xl text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all sm:text-sm"
                    >
                    <p
                      v-if="passwordForm.errors.current_password"
                      class="mt-1 text-xs text-red-500 font-medium"
                    >
                      {{ passwordForm.errors.current_password }}
                    </p>
                  </div>

                  <div>
                    <label
                      for="password"
                      class="block text-sm font-medium text-zinc-700 dark:text-zinc-300"
                    >
                      New Password
                    </label>
                    <input
                      id="password"
                      v-model="passwordForm.password"
                      type="password"
                      autocomplete="new-password"
                      class="mt-1 block w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-800 rounded-xl text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all sm:text-sm"
                    >
                    <p
                      v-if="passwordForm.errors.password"
                      class="mt-1 text-xs text-red-500 font-medium"
                    >
                      {{ passwordForm.errors.password }}
                    </p>
                  </div>

                  <div>
                    <label
                      for="password_confirmation"
                      class="block text-sm font-medium text-zinc-700 dark:text-zinc-300"
                    >
                      Confirm Password
                    </label>
                    <input
                      id="password_confirmation"
                      v-model="passwordForm.password_confirmation"
                      type="password"
                      autocomplete="new-password"
                      class="mt-1 block w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-800 rounded-xl text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all sm:text-sm"
                    >
                  </div>
                </div>

                <div class="mt-6 flex items-center justify-end">
                  <Button
                    type="submit"
                    :loading="passwordForm.processing"
                  >
                    Update Password
                  </Button>
                </div>
              </form>
            </div>
          </div>
          <hr class="my-8 border-zinc-200 dark:border-zinc-800">

          <!-- Two-Factor Authentication -->
          <div class="md:grid md:grid-cols-3 md:gap-6 mt-10">
            <div class="md:col-span-1">
              <h3 class="text-lg font-medium leading-6 text-zinc-900 dark:text-white flex items-center">
                <ShieldCheckIcon class="h-5 w-5 mr-2 text-indigo-500" />
                Two-Factor Authentication
              </h3>
              <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                Add additional security to your account using two-factor authentication.
              </p>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
              <div
                v-if="user?.google2fa_secret"
                class="space-y-4"
              >
                <div
                  class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4"
                >
                  <p class="text-sm text-green-800 dark:text-green-300 font-medium flex items-center">
                    <ShieldCheckIcon class="h-5 w-5 mr-2" />
                    Two-factor authentication is enabled.
                  </p>
                </div>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">
                  When two-factor authentication is enabled, you will be prompted for a secure, random
                  token during authentication. You may retrieve this token from your phone's Google
                  Authenticator application.
                </p>
                <div class="flex justify-end">
                  <Button
                    variant="danger"
                    :loading="mfaEnableForm.processing"
                    @click="disableMFA"
                  >
                    Disable 2FA
                  </Button>
                </div>
              </div>

              <div
                v-else-if="mfaSetup"
                class="space-y-6"
              >
                <p class="text-sm text-zinc-500 dark:text-zinc-400 font-medium">
                  Finish setting up two-factor authentication.
                </p>

                <div class="space-y-4">
                  <p class="text-sm text-zinc-700 dark:text-zinc-300">
                    1. Scan this QR code with your authenticator app (Google Authenticator, Authy,
                    etc.):
                  </p>
                  <div class="p-4 bg-white rounded-xl inline-block border border-zinc-200">
                    <!-- Render the SVG QR code from the backend -->
                    <!-- eslint-disable vue/no-v-html -->
                    <div
                      class="w-48 h-48 [&>svg]:w-full [&>svg]:h-full"
                      v-html="mfaSetup.qr_code"
                    />
                    <!-- eslint-enable vue/no-v-html -->
                  </div>

                  <p class="text-sm text-zinc-700 dark:text-zinc-300">
                    2. Enter the 6-digit code from your app to verify:
                  </p>
                  <div class="max-w-xs">
                    <input
                      v-model="mfaEnableForm.code"
                      type="text"
                      placeholder="000000"
                      maxlength="6"
                      class="block w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-800 rounded-xl text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all sm:text-sm text-center tracking-widest text-lg font-bold"
                    >
                    <p
                      v-if="mfaEnableForm.errors.code"
                      class="mt-1 text-xs text-red-500 font-medium"
                    >
                      {{ mfaEnableForm.errors.code }}
                    </p>
                  </div>
                </div>

                <div class="flex justify-end">
                  <Button
                    :loading="mfaEnableForm.processing"
                    @click="enableMFA"
                  >
                    Confirm & Enable
                  </Button>
                </div>
              </div>

              <div
                v-else
                class="space-y-4"
              >
                <div class="bg-zinc-50 dark:bg-zinc-800/50 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4">
                  <p class="text-sm text-zinc-600 dark:text-zinc-400">
                    Two-factor authentication is not yet enabled.
                  </p>
                </div>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">
                  When two-factor authentication is enabled, you will be prompted for a secure, random
                  token during authentication. You may retrieve this token from your phone's Google
                  Authenticator application.
                </p>
                <div class="flex justify-end">
                  <Button
                    :loading="mfaEnableForm.processing"
                    @click="setupMFA"
                  >
                    Enable 2FA
                  </Button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
