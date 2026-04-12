<script setup lang="ts">
import { ref } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import { UserCircleIcon, KeyIcon, PhotoIcon } from '@heroicons/vue/24/outline';
import Button from '@/Components/Form/Button.vue';
import type { User } from '@/types/models';

const props = defineProps<{
  user: User;
  mustVerifyEmail?: boolean;
  status?: string | null;
  activeTab?: 'profile' | 'security';
}>();


const profileForm = useForm({
  name: props.user.name,
  email: props.user.email,
  avatar: null as File | null,
  _method: 'POST',
});

const passwordForm = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
});

const avatarPreview = ref<string | null>(props.user.avatar_url);
const avatarInput = ref<HTMLInputElement | null>(null);

const triggerAvatarUpload = () => {
  avatarInput.value?.click();
};

const handleAvatarChange = (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files && target.files[0]) {
    profileForm.avatar = target.files[0];
    avatarPreview.value = URL.createObjectURL(target.files[0]);
  }
};

const updateProfile = () => {
  profileForm.post(route('profile.update'), {
    preserveScroll: true,
    onSuccess: () => {
      // toast.success('Profile updated successfully.');
    },
  });
};

const updatePassword = () => {
  passwordForm.post(route('profile.password'), {
    preserveScroll: true,
    onSuccess: () => {
      passwordForm.reset();
      // toast.success('Password updated successfully.');
    },
  });
};
</script>

<template>
  <div class="space-y-6">
    <Head title="Profile" />

    <!-- Profile Information -->
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
      <div class="px-4 py-5 sm:p-6">
        <div class="md:grid md:grid-cols-3 md:gap-6">
          <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
              <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white flex items-center">
                <UserCircleIcon class="h-6 w-6 mr-2 text-indigo-500" />
                Profile Information
              </h3>
              <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Update your account's profile information and email address.
              </p>
            </div>
          </div>
          <div class="mt-5 md:mt-0 md:col-span-2">
            <form @submit.prevent="updateProfile">
              <div class="grid grid-cols-6 gap-6">
                <!-- Avatar -->
                <div class="col-span-6 sm:col-span-6 flex items-center space-x-6">
                  <div
                    class="shrink-0 relative group cursor-pointer"
                    @click="triggerAvatarUpload"
                  >
                    <img
                      class="h-24 w-24 object-cover rounded-full ring-4 ring-white dark:ring-gray-700 shadow-lg transition-transform transform group-hover:scale-105"
                      :src="avatarPreview || 'https://ui-avatars.com/api/?name=' + user.name"
                      alt="Current profile photo"
                    >
                    <div
                      class="absolute inset-0 rounded-full bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"
                    >
                      <PhotoIcon class="h-8 w-8 text-white" />
                    </div>
                  </div>
                  <div>
                    <CommonButton
                      type="button"
                      variant="secondary"
                      size="sm"
                      @click="triggerAvatarUpload"
                    >
                      Change Photo
                    </CommonButton>
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                      JPG, GIF or PNG. Max size of 1MB.
                    </p>
                    <input
                      ref="avatarInput"
                      type="file"
                      class="hidden"
                      accept="image/*"
                      @change="handleAvatarChange"
                    >
                    <p
                      v-if="profileForm.errors.avatar"
                      class="mt-2 text-sm text-red-600 dark:text-red-400"
                    >
                      {{ profileForm.errors.avatar }}
                    </p>
                  </div>
                </div>

                <!-- Name -->
                <div class="col-span-6 sm:col-span-4">
                  <label
                    for="name"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                  >
                    Name
                  </label>
                  <input
                    id="name"
                    v-model="profileForm.name"
                    type="text"
                    autocomplete="name"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  >
                  <p
                    v-if="profileForm.errors.name"
                    class="mt-2 text-sm text-red-600 dark:text-red-400"
                  >
                    {{ profileForm.errors.name }}
                  </p>
                </div>

                <!-- Email -->
                <div class="col-span-6 sm:col-span-4">
                  <label
                    for="email"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                  >
                    Email
                  </label>
                  <input
                    id="email"
                    v-model="profileForm.email"
                    type="email"
                    autocomplete="email"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  >
                  <p
                    v-if="profileForm.errors.email"
                    class="mt-2 text-sm text-red-600 dark:text-red-400"
                  >
                    {{ profileForm.errors.email }}
                  </p>
                </div>
              </div>

              <div class="mt-6 flex items-center justify-end">
                <Button
                  type="submit"
                  variant="primary"
                  :disabled="profileForm.processing"
                >
                  Save Changes
                </Button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Update Password -->
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
      <div class="px-4 py-5 sm:p-6">
        <div class="md:grid md:grid-cols-3 md:gap-6">
          <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
              <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white flex items-center">
                <KeyIcon class="h-6 w-6 mr-2 text-amber-500" />
                Update Password
              </h3>
              <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Ensure your account is using a long, random password to stay secure.
              </p>
            </div>
          </div>
          <div class="mt-5 md:mt-0 md:col-span-2">
            <form @submit.prevent="updatePassword">
              <div class="grid grid-cols-6 gap-6">
                <div class="col-span-6 sm:col-span-4">
                  <label
                    for="current_password"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                  >
                    Current Password
                  </label>
                  <input
                    id="current_password"
                    v-model="passwordForm.current_password"
                    type="password"
                    autocomplete="current-password"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  >
                  <p
                    v-if="passwordForm.errors.current_password"
                    class="mt-2 text-sm text-red-600 dark:text-red-400"
                  >
                    {{ passwordForm.errors.current_password }}
                  </p>
                </div>

                <div class="col-span-6 sm:col-span-4">
                  <label
                    for="password"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                  >
                    New Password
                  </label>
                  <input
                    id="password"
                    v-model="passwordForm.password"
                    type="password"
                    autocomplete="new-password"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  >
                  <p
                    v-if="passwordForm.errors.password"
                    class="mt-2 text-sm text-red-600 dark:text-red-400"
                  >
                    {{ passwordForm.errors.password }}
                  </p>
                </div>

                <div class="col-span-6 sm:col-span-4">
                  <label
                    for="password_confirmation"
                    class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                  >
                    Confirm Password
                  </label>
                  <input
                    id="password_confirmation"
                    v-model="passwordForm.password_confirmation"
                    type="password"
                    autocomplete="new-password"
                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                  >
                </div>
              </div>

              <div class="mt-6 flex items-center justify-end">
                <Button
                  type="submit"
                  variant="primary"
                  :disabled="passwordForm.processing"
                >
                  Update Password
                </Button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
