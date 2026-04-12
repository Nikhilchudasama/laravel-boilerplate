<script setup lang="ts">
import toast from '@/Stores/toast';
import {
    CheckCircleIcon,
    XCircleIcon,
    InformationCircleIcon,
    ExclamationTriangleIcon,
    XMarkIcon
} from '@heroicons/vue/24/outline';

const icons = {
    success: CheckCircleIcon,
    error: XCircleIcon,
    info: InformationCircleIcon,
    warning: ExclamationTriangleIcon,
};

const styles = {
    success: 'bg-emerald-50 text-emerald-800 border-emerald-200 dark:bg-emerald-950 dark:text-emerald-200 dark:border-emerald-800',
    error: 'bg-red-50 text-red-800 border-red-200 dark:bg-red-950 dark:text-red-200 dark:border-red-800',
    info: 'bg-blue-50 text-blue-800 border-blue-200 dark:bg-blue-950 dark:text-blue-200 dark:border-blue-800',
    warning: 'bg-amber-50 text-amber-800 border-amber-200 dark:bg-amber-950 dark:text-amber-200 dark:border-amber-800',
};

const iconColors = {
    success: 'text-emerald-500',
    error: 'text-red-500',
    info: 'text-blue-500',
    warning: 'text-amber-500',
};
</script>

<template>
  <div class="fixed top-4 right-4 z-[9999] w-full max-w-sm space-y-3 px-4 pointer-events-none">
    <TransitionGroup
      enter-active-class="transform ease-out duration-300 transition"
      enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
      enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
      leave-active-class="transition ease-in duration-150"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-for="item in toast.items"
        :key="item.id"
        class="flex items-start p-4 rounded-2xl border shadow-lg backdrop-blur-md transition-all duration-300 pointer-events-auto"
        :class="styles[item.type]"
      >
        <div class="flex-shrink-0">
          <component
            :is="icons[item.type]"
            class="h-6 w-6"
            :class="iconColors[item.type]"
            aria-hidden="true"
          />
        </div>
        <div class="ml-3 w-0 flex-1 pt-0.5">
          <p class="text-sm font-bold leading-tight uppercase tracking-wider mb-1 opacity-75">
            {{ item.type }}
          </p>
          <p class="text-sm font-medium">
            {{ item.message }}
          </p>
        </div>
        <div class="ml-4 flex flex-shrink-0">
          <button
            type="button"
            class="inline-flex rounded-md text-current hover:opacity-75 focus:outline-none transition-opacity"
            @click="toast.remove(item.id)"
          >
            <span class="sr-only">Close</span>
            <XMarkIcon
              class="h-5 w-5"
              aria-hidden="true"
            />
          </button>
        </div>
      </div>
    </TransitionGroup>
  </div>
</template>
