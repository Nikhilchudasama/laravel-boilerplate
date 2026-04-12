<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

interface Props {
    type?: 'button' | 'submit' | 'reset';
    variant?: 'primary' | 'secondary' | 'danger' | 'outline' | 'ghost';
    size?: 'sm' | 'md' | 'lg';
    loading?: boolean;
    disabled?: boolean;
    fullWidth?: boolean;
    href?: string;
    method?: string;
    as?: string;
}

const props = withDefaults(defineProps<Props>(), {
    type: 'button',
    variant: 'primary',
    size: 'md',
    loading: false,
    disabled: false,
    fullWidth: false,
});

const variantClasses = {
    primary: 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-lg shadow-indigo-600/20 focus:ring-indigo-500',
    secondary: 'bg-zinc-100 text-zinc-900 hover:bg-zinc-200 dark:bg-zinc-800 dark:text-zinc-100 dark:hover:bg-zinc-700 focus:ring-zinc-500',
    danger: 'bg-red-600 text-white hover:bg-red-700 shadow-lg shadow-red-600/20 focus:ring-red-500',
    outline: 'bg-transparent border border-zinc-300 text-zinc-700 hover:bg-zinc-50 dark:border-zinc-700 dark:text-zinc-300 dark:hover:bg-zinc-900/50 focus:ring-zinc-500',
    ghost: 'bg-transparent text-zinc-600 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:bg-zinc-800 focus:ring-zinc-500',
};

const sizeClasses = {
    sm: 'px-3 py-1.5 text-xs',
    md: 'px-5 py-2.5 text-sm',
    lg: 'px-8 py-3.5 text-base',
};

const classes = computed(() => {
    return [
        'inline-flex items-center justify-center font-bold rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed',
        variantClasses[props.variant],
        sizeClasses[props.size],
        props.fullWidth ? 'w-full' : '',
    ].join(' ');
});
</script>

<template>
  <Link
    v-if="href"
    :href="href"
    :method="method as any"
    :as="as as any"
    :class="classes"
  >
    <slot />
  </Link>
  <button
    v-else
    :type="type"
    :disabled="disabled || loading"
    :class="classes"
  >
    <svg
      v-if="loading"
      class="animate-spin -ml-1 mr-2 h-4 w-4 text-current"
      xmlns="http://www.w3.org/2000/svg"
      fill="none"
      viewBox="0 0 24 24"
    >
      <circle
        class="opacity-25"
        cx="12"
        cy="12"
        r="10"
        stroke="currentColor"
        stroke-width="4"
      />
      <path
        class="opacity-75"
        fill="currentColor"
        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
      />
    </svg>
    <slot />
  </button>
</template>
