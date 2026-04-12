<script setup lang="ts">
import { usePage, Link, router } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from 'ziggy-js';
import menus from '@/Data/navigation';
import { menuIcons } from '@/Services/menuIcons';

defineProps({
  isOpen: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(['close-sidebar']);

const page = usePage();
const user = computed(() => page.props.auth.user as unknown as { roles: string[], permissions: string[] } | null);
const currentRouteName = computed(() => page.props.current_route_name as string);

const handleItemClick = () => {
  if (window.innerWidth < 768) {
    emit('close-sidebar');
  }
};

const logout = () => {
  router.post(route('logout'));
};

const isMenuVisible = (menu: { external?: boolean, route_name?: string, role?: string, permission?: string }) => {
  if (!user.value) return false;
  if (user.value.roles.includes('admin')) return true;

  if (menu.role && !user.value.roles.includes(menu.role)) return false;
  if (menu.permission && !user.value.permissions.includes(menu.permission)) return false;

  return true;
};

const getActiveMenuClass = (menu: { external?: boolean, route_name?: string, role?: string, permission?: string }) => {
  if (!currentRouteName.value || !menu.route_name) return '';

  // Check if the current route exactly matches or is a sub-route
  const isActive =
    currentRouteName.value === menu.route_name ||
    (currentRouteName.value.startsWith(menu.route_name.split('.index')[0]) && menu.route_name !== 'admin.dashboard');

  return isActive ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' : '';
};
</script>

<template>
  <!-- Mobile Backdrop -->
  <div
    v-if="isOpen"
    class="fixed inset-0 z-20 bg-black bg-opacity-50 transition-opacity md:hidden"
    @click="emit('close-sidebar')"
  />

  <!-- Sidebar -->
  <aside
    class="fixed inset-y-0 left-0 z-30 w-64 bg-white dark:bg-gray-800 shadow-md flex flex-col transition-transform duration-300 transform md:translate-x-0 md:static md:inset-0"
    :class="isOpen ? 'translate-x-0' : '-translate-x-full'"
  >
    <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
      <Link
        href="/admin/dashboard"
        class="text-2xl font-bold text-gray-800 dark:text-white flex items-center space-x-2"
      >
        <span>Admin Panel</span>
      </Link>
      <!-- Mobile Close Button -->
      <button
        class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none"
        @click="emit('close-sidebar')"
      >
        <svg
          class="h-6 w-6"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M6 18L18 6M6 6l12 12"
          />
        </svg>
      </button>
    </div>

    <nav class="flex-1 overflow-y-auto py-4">
      <template
        v-for="menu in menus"
        :key="menu.title"
      >
        <template v-if="isMenuVisible(menu)">
          <a
            v-if="menu.external"
            :href="menu.route_name ? route(menu.route_name) : '#'"
            class="flex items-center px-6 py-3 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200"
          >
            <component
              :is="menuIcons[menu.icon as keyof typeof menuIcons]"
              class="h-5 w-5 mr-3"
            />
            <span>{{ menu.title }}</span>
          </a>
          <Link
            v-else
            :href="menu.route_name ? route(menu.route_name) : '#'"
            class="flex items-center px-6 py-3 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-colors duration-200"
            :class="getActiveMenuClass(menu)"
            @click="handleItemClick"
          >
            <component
              :is="menuIcons[menu.icon as keyof typeof menuIcons]"
              class="h-5 w-5 mr-3"
            />
            <span>{{ menu.title }}</span>
          </Link>
        </template>
      </template>
    </nav>

    <div class="p-4 border-t border-gray-200 dark:border-gray-700">
      <button
        class="flex w-full items-center px-4 py-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md transition-colors duration-200"
        @click="logout"
      >
        <component
          :is="menuIcons.logout"
          class="h-5 w-5 mr-3"
        />
        <span>Logout</span>
      </button>
    </div>
  </aside>
</template>
