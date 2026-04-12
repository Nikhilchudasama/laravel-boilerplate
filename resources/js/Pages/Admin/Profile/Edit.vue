<script setup lang="ts">
import { ref } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import { UserCircleIcon, KeyIcon, PhotoIcon } from '@heroicons/vue/24/outline';
import Button from '@/Components/Form/Button.vue';
import TextInput from '@/Components/Form/TextInput.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { route } from 'ziggy-js';
import type { ProfileFormData, PasswordFormData } from '@/types/forms';
import type { User } from '@/types/models';

defineOptions({ layout: AdminLayout });

const props = defineProps<{
  user: User;
  mustVerifyEmail?: boolean;
  status?: string | null;
  activeTab?: 'profile' | 'security';
  timezones: string[];
}>();

const currentTab = ref(props.activeTab || 'profile');

const switchTab = (tab: 'profile' | 'security') => {
  currentTab.value = tab;
};

const profileForm = useForm<ProfileFormData>({
  name: props.user.name,
  email: props.user.email,
  timezone: props.user.timezone ?? '',
  avatar: null,
  _method: 'POST',
});

const passwordForm = useForm<PasswordFormData>({
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
  profileForm.post(route('admin.profile.update'), {
    preserveScroll: true,
  });
};

const updatePassword = () => {
  passwordForm.post(route('admin.profile.password'), {
    preserveScroll: true,
    onSuccess: () => {
      passwordForm.reset();
    },
  });
};
</script>

<template>
  <div class="space-y-6">
    <Head title="Admin Profile" />

    <!-- Tabs -->
    <div class="border-b border-gray-200 dark:border-gray-700">
      <nav
        class="-mb-px flex space-x-8"
        aria-label="Tabs"
      >
        <button
          :class="[
            currentTab === 'profile'
              ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400'
              : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300',
            'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors'
          ]"
          @click="switchTab('profile')"
        >
          <div class="flex items-center">
            <UserCircleIcon class="h-5 w-5 mr-2" />
            Profile Information
          </div>
        </button>
        <button
          :class="[
            currentTab === 'security'
              ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400'
              : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300',
            'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors'
          ]"
          @click="switchTab('security')"
        >
          <div class="flex items-center">
            <KeyIcon class="h-5 w-5 mr-2" />
            Update Password
          </div>
        </button>
      </nav>
    </div>

    <!-- Profile Information Tab -->
    <div
      v-if="currentTab === 'profile'"
      class="bg-white dark:bg-gray-800 shadow sm:rounded-lg"
    >
      <div class="px-4 py-5 sm:p-6">
        <div class="md:grid md:grid-cols-3 md:gap-6">
          <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
              <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
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
                    <Button
                      type="button"
                      variant="secondary"
                      size="sm"
                      @click="triggerAvatarUpload"
                    >
                      Change Photo
                    </Button>
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
                <!-- Name -->
                <div class="col-span-6 sm:col-span-4">
                  <TextInput
                    v-model="profileForm.name"
                    label="Name"
                    type="text"
                    autocomplete="name"
                    :error="profileForm.errors.name"
                  />
                </div>

                <!-- Email -->
                <div class="col-span-6 sm:col-span-4">
                  <TextInput
                    v-model="profileForm.email"
                    label="Email"
                    type="email"
                    autocomplete="email"
                    :error="profileForm.errors.email"
                  />
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

    <!-- Update Password Tab -->
    <div
      v-if="currentTab === 'security'"
      class="bg-white dark:bg-gray-800 shadow sm:rounded-lg"
    >
      <div class="px-4 py-5 sm:p-6">
        <div class="md:grid md:grid-cols-3 md:gap-6">
          <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
              <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
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
                  <TextInput
                    v-model="passwordForm.current_password"
                    label="Current Password"
                    type="password"
                    autocomplete="current-password"
                    :error="passwordForm.errors.current_password"
                  />
                </div>

                <div class="col-span-6 sm:col-span-4">
                  <TextInput
                    v-model="passwordForm.password"
                    label="New Password"
                    type="password"
                    autocomplete="new-password"
                    :error="passwordForm.errors.password"
                  />
                </div>

                <div class="col-span-6 sm:col-span-4">
                  <TextInput
                    v-model="passwordForm.password_confirmation"
                    label="Confirm Password"
                    type="password"
                    autocomplete="new-password"
                  />
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
