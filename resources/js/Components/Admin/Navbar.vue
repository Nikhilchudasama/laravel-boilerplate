<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Bars3Icon, BellIcon } from '@heroicons/vue/24/outline';
import { ref } from 'vue';
import { route } from 'ziggy-js';
import { isDark, toggleTheme } from '@/Services/theme';
import { menuIcons } from '@/Services/menuIcons';

const isProfileOpen = ref(false);

const emit = defineEmits(['toggle-sidebar']);

const toggleProfile = () => {
    isProfileOpen.value = !isProfileOpen.value;
};
</script>

<template>
  <header
    class="flex items-center justify-between px-6 py-4 bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700"
  >
    <div class="flex items-center">
      <button
        class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 rounded-md"
        @click="emit('toggle-sidebar')"
      >
        <Bars3Icon class="h-6 w-6" />
      </button>
      <h2 class="text-xl font-semibold text-gray-800 dark:text-white ml-4 md:ml-0">
        Admin Panel
      </h2>
    </div>

    <div class="flex items-center space-x-4">
      <button
        class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 rounded-lg transition-colors"
        title="Toggle Dark Mode"
        @click="toggleTheme"
      >
        <component
          :is="isDark ? menuIcons.sun : menuIcons.moon"
          class="h-6 w-6"
        />
      </button>

      <button
        class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 rounded-lg transition-colors"
      >
        <BellIcon class="h-6 w-6" />
      </button>

      <div class="relative">
        <button
          class="flex items-center text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 rounded-full"
          @click="toggleProfile"
        >
          <img
            class="h-8 w-8 rounded-full object-cover"
            src="https://ui-avatars.com/api/?name=Admin+User"
            alt="Admin"
          >
          <span class="ml-2 text-gray-700 dark:text-gray-300 font-medium hidden md:block">Admin User</span>
        </button>

        <!-- Dropdown -->
        <div
          v-if="isProfileOpen"
          class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50 ring-1 ring-black ring-opacity-5 border border-gray-100 dark:border-gray-700"
        >
          <Link
            :href="route('admin.profile')"
            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
          >
            My Profile
          </Link>
          <Link
            :href="route('admin.profile.security')"
            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
          >
            Security & Privacy
          </Link>
          <div class="border-t border-gray-100 dark:border-gray-700" />
          <Link
            :href="route('logout')"
            method="post"
            as="button"
            class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700"
          >
            Logout
          </Link>
        </div>
      </div>
    </div>
  </header>

  <!-- Backdrop for dropdown -->
  <div
    v-if="isProfileOpen"
    class="fixed inset-0 z-40"
    tabindex="-1"
    @click="isProfileOpen = false"
  />
</template>
