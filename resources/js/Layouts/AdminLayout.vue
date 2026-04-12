<script setup lang="ts">
import { ref, watch, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import type { PageProps } from '@/types/inertia';
import Sidebar from '@/Components/Admin/Sidebar.vue';
import Navbar from '@/Components/Admin/Navbar.vue';
import Breadcrumbs from '@/Components/Admin/Breadcrumbs.vue';
import ToastList from '@/Components/Common/ToastList.vue';
import { isDark } from '@/Services/theme';
import toast from '@/Stores/toast';
const isSidebarOpen = ref(false);
const page = usePage<PageProps>();

const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value;
};

const closeSidebar = () => {
  isSidebarOpen.value = false;
};

// Listen specifically for flash messages
watch(
  () => page.props.flash,
  (flash: Record<string, string>) => {
    if (!flash) return;

    if (flash.success) {
      toast.success(flash.success);
    }
    if (flash.error) {
      toast.error(flash.error);
    }
    if (flash.info) {
      toast.info(flash.info);
    }
    if (flash.warning) {
      toast.warning(flash.warning);
    }
  },
  { deep: true, immediate: true }
);

onMounted(() => {
  // Testing toast removed
});
</script>

<template>
  <div
    :class="{ dark: isDark }"
    class="flex h-screen bg-gray-100 dark:bg-gray-900"
  >
    <!-- Toast Notifications -->
    <ToastList />

    <!-- Sidebar -->
    <Sidebar
      :is-open="isSidebarOpen"
      @close-sidebar="closeSidebar"
    />

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Impersonation Banner -->
      <ImpersonationBanner />


      <!-- Navbar -->
      <Navbar @toggle-sidebar="toggleSidebar" />

      <!-- Main Content -->
      <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 dark:bg-gray-900 p-6">
        <Breadcrumbs />
        <slot />
      </main>
    </div>
  </div>
</template>
