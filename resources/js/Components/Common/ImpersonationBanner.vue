<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { ArrowLeftOnRectangleIcon } from '@heroicons/vue/24/outline';
import type { PageProps } from '@/types/inertia';

const page = usePage<PageProps & { auth?: { is_impersonating?: boolean, user: { name: string } | null } }>();
</script>

<template>
  <div
    v-if="page.props.auth?.is_impersonating"
    class="bg-amber-600 dark:bg-amber-700 text-white px-4 py-2 text-center text-sm font-bold flex justify-center items-center space-x-3 shadow-lg z-[100] relative"
  >
    <div class="flex items-center space-x-2">
      <span class="inline-flex items-center justify-center w-5 h-5 bg-amber-500 rounded-full text-[10px] animate-pulse">
        !
      </span>
      <span>
        Impersonating: <span class="underline decoration-amber-300 decoration-2 underline-offset-4">{{
          page.props.auth.user?.name }}</span>
      </span>
    </div>

    <a
      :href="route('admin.users.impersonate.leave')"
      class="flex items-center space-x-1 px-3 py-1 bg-white/10 hover:bg-white/20 border border-white/20 rounded-lg transition-all duration-200 group"
    >
      <ArrowLeftOnRectangleIcon class="h-4 w-4 group-hover:-translate-x-1 transition-transform" />
      <span>Stop Impersonating</span>
    </a>
  </div>
</template>
