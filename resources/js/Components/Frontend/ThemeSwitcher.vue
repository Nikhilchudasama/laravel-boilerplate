<script setup lang="ts">
import { inject } from 'vue';
import { SunIcon, MoonIcon } from '@heroicons/vue/24/outline';

const themeContext = inject<{ theme: { value: string }, toggleTheme: () => void }>('theme');
</script>

<template>
  <button
    v-if="themeContext"
    class="p-2 rounded-xl bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
    title="Toggle Theme"
    @click="themeContext.toggleTheme"
  >
    <SunIcon
      v-if="themeContext.theme.value === 'dark'"
      class="h-5 w-5"
    />
    <MoonIcon
      v-else
      class="h-5 w-5"
    />
  </button>
</template>
