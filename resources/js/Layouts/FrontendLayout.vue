<script setup lang="ts">
import { ref, onMounted, provide, watch } from 'vue';
import Navbar from '@/Components/Frontend/Navbar.vue';
import ImpersonationBanner from '@/Components/Common/ImpersonationBanner.vue';
import ToastList from '@/Components/Common/ToastList.vue';

import { usePage } from '@inertiajs/vue3';
import type { PageProps } from '@/types/inertia';
import { computed } from 'vue';
import toast from '@/Stores/toast';

const page = usePage<PageProps>();
const isAuthenticated = computed(() => !!page.props.auth?.user);
const theme = ref(localStorage.getItem('frontend-theme') || 'light');

const toggleTheme = () => {
  theme.value = theme.value === 'light' ? 'dark' : 'light';
  localStorage.setItem('frontend-theme', theme.value);
};

const flashMessages = computed(() => page.props.flash);

// Listen for flash messages
watch(
  flashMessages,
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
  // Ensure initial theme is applied correctly
  const savedTheme = localStorage.getItem('frontend-theme');
  if (savedTheme) {
    theme.value = savedTheme;
  }

});

provide('theme', { theme, toggleTheme });
</script>

<template>
  <div
    :class="theme === 'dark' ? 'dark' : ''"
    class="min-h-screen"
  >
    <ToastList />
    <div
      class="min-h-screen bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 transition-colors duration-500"
    >
      <ImpersonationBanner />
      <Navbar v-if="isAuthenticated" />

      <slot />
    </div>
  </div>
</template>
