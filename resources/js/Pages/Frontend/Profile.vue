<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { UserIcon, EnvelopeIcon, ArrowLeftIcon } from '@heroicons/vue/24/outline';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import Button from '@/Components/Form/Button.vue';
import type { ProfileFormData } from '@/types/forms';

defineOptions({ layout: FrontendLayout });

const props = defineProps<{
  auth?: {
    user: {
      name: string;
      email: string;
      avatar_url: string;
      timezone: string;
    };
  };
}>();

const form = useForm<ProfileFormData>({
  name: props.auth?.user?.name || '',
  email: props.auth?.user?.email || '',
  timezone: props.auth?.user?.timezone || '',
  avatar: null,
});

const onFileChange = (e: Event) => {
  const target = e.target as HTMLInputElement;
  if (target.files?.length) {
    form.avatar = target.files[0];
  }
};

const submit = () => {
  // We use a manual form submission for multipart/form-data with post
  form.post('/profile', {
    forceFormData: true,
  });
};
</script>

<template>
  <Head title="Edit Profile" />

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
            Personal Profile
          </h2>
          <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
            Update your personal information
          </p>
        </div>

        <form
          class="p-6 space-y-6"
          @submit.prevent="submit"
        >
          <!-- Profile Picture Section -->
          <div class="flex items-center space-x-6 pb-6 border-b border-zinc-100 dark:border-zinc-800/50">
            <div class="relative group h-24 w-24 shrink-0">
              <img
                :src="props.auth?.user.avatar_url"
                class="h-full w-full rounded-full object-cover border-4 border-white dark:border-zinc-800 shadow-sm"
                alt="Avatar"
              >
              <div
                class="absolute inset-0 bg-black/40 rounded-full opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none"
              >
                <UserIcon class="h-8 w-8 text-white" />
              </div>
            </div>
            <div class="flex-1">
              <label class="block text-sm font-semibold text-zinc-900 dark:text-white mb-1">Profile
                Photo</label>
              <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-3">
                JPG, PNG or GIF. Max size 2MB.
              </p>
              <input
                type="file"
                class="block w-full text-sm text-zinc-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 transition-all cursor-pointer"
                accept="image/*"
                @change="onFileChange"
              >
              <p
                v-if="form.errors.avatar"
                class="mt-2 text-xs text-red-500 font-medium font-medium"
              >
                {{
                  form.errors.avatar }}
              </p>
            </div>
          </div>

          <div>
            <label
              for="name"
              class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5"
            >
              Full Name
            </label>
            <div class="relative group">
              <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400">
                <UserIcon class="h-5 w-5" />
              </div>
              <input
                id="name"
                v-model="form.name"
                type="text"
                required
                class="block w-full pl-11 pr-4 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-800 rounded-xl text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all sm:text-sm"
                placeholder="John Doe"
              >
            </div>
            <p
              v-if="form.errors.name"
              class="mt-1.5 text-xs text-red-500 font-medium"
            >
              {{ form.errors.name
              }}
            </p>
          </div>

          <div>
            <label
              for="email"
              class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5"
            >
              Email Address
            </label>
            <div class="relative group">
              <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-zinc-400">
                <EnvelopeIcon class="h-5 w-5" />
              </div>
              <input
                id="email"
                v-model="form.email"
                type="email"
                required
                class="block w-full pl-11 pr-4 py-2.5 bg-zinc-50 dark:bg-zinc-950 border border-zinc-300 dark:border-zinc-800 rounded-xl text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all sm:text-sm"
                placeholder="name@example.com"
              >
            </div>
            <p
              v-if="form.errors.email"
              class="mt-1.5 text-xs text-red-500 font-medium"
            >
              {{ form.errors.email
              }}
            </p>
          </div>

          <div class="flex items-center justify-end space-x-4 pt-4">
            <Link
              href="/dashboard"
              class="px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-white transition-colors"
            >
              Cancel
            </Link>
            <Button
              type="submit"
              :loading="form.processing"
            >
              Save Changes
            </Button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
