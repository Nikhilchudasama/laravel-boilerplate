<script setup lang="ts">
import { onMounted, onUnmounted, watch } from 'vue';

const props = defineProps<{
    show: boolean;
    maxWidth?: 'sm' | 'md' | 'lg' | 'xl' | '2xl' | '4xl';
    closeable?: boolean;
}>();

const emit = defineEmits(['close']);

watch(() => props.show, (value) => {
    if (value) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = 'auto';
    }
});

const close = () => {
    if (props.closeable) {
        emit('close');
    }
};

const closeOnEscape = (e: KeyboardEvent) => {
    if (e.key === 'Escape' && props.show) {
        close();
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));
onUnmounted(() => {
    document.removeEventListener('keydown', closeOnEscape);
    document.body.style.overflow = 'auto';
});

const maxWidthClass = {
    'sm': 'sm:max-w-sm',
    'md': 'sm:max-w-md',
    'lg': 'sm:max-w-lg',
    'xl': 'sm:max-w-xl',
    '2xl': 'sm:max-w-2xl',
    '4xl': 'sm:max-w-4xl',
}[props.maxWidth || '2xl'];
</script>

<template>
  <Teleport to="body">
    <div
      v-show="show"
      class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50 flex items-center justify-center"
    >
      <!-- Backdrop -->
      <transition
        enter-active-class="ease-out duration-300"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="ease-in duration-200"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div
          v-show="show"
          class="absolute inset-0 transform transition-all"
          @click="close"
        >
          <div class="absolute inset-0 bg-zinc-900/40 dark:bg-zinc-950/60 backdrop-blur-sm" />
        </div>
      </transition>

      <!-- Modal Content -->
      <transition
        enter-active-class="ease-out duration-300"
        enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        enter-to-class="opacity-100 translate-y-0 sm:scale-100"
        leave-active-class="ease-in duration-200"
        leave-from-class="opacity-100 translate-y-0 sm:scale-100"
        leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
      >
        <div
          v-show="show"
          class="relative z-10 mb-6 bg-white dark:bg-zinc-900 rounded-2xl overflow-hidden shadow-2xl transform transition-all sm:w-full"
          :class="maxWidthClass"
        >
          <slot v-if="show" />
        </div>
      </transition>
    </div>
  </Teleport>
</template>
