<script setup lang="ts">
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/24/outline';
import { computed } from 'vue';

const props = defineProps<{
    currentPage: number;
    perPage: number;
    totalRecords: number;
}>();

const emit = defineEmits(['page-change']);

const totalPages = computed(() => Math.ceil(props.totalRecords / props.perPage));

const getPageNumbers = () => {
    const pages = [];
    const total = totalPages.value;

    // Logic from user snippet: current - 3 to current + 3
    let startPage = props.currentPage - 3 <= 0 ? 1 : props.currentPage - 3;
    let endPage = props.currentPage + 3 > total ? total : props.currentPage + 3;

    // Adjust start if we are near the end to show more previous pages?
    // User snippet didn't do that, simplified loop:
    for (let i = startPage; i <= endPage; i++) {
        pages.push(i);
    }
    return pages;
};

const pageNumbers = computed(() => getPageNumbers());

const changePage = (page: number) => {
    if (page >= 1 && page <= totalPages.value && page !== props.currentPage) {
        emit('page-change', page);
    }
};
</script>

<template>
  <nav
    class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
    aria-label="Pagination"
  >
    <!-- Previous -->
    <button
      :disabled="currentPage <= 1"
      class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
      @click="changePage(currentPage - 1)"
    >
      <span class="sr-only">Previous</span>
      <ChevronLeftIcon class="h-5 w-5" />
    </button>

    <!-- Pages -->
    <template
      v-for="page in pageNumbers"
      :key="page"
    >
      <button
        class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-700"
        :class="page === currentPage
          ? 'z-10 bg-indigo-50 dark:bg-indigo-900 border-indigo-500 dark:border-indigo-500 text-indigo-600 dark:text-white'
          : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200'"
        @click="changePage(page)"
      >
        {{ page }}
      </button>
    </template>

    <!-- Next -->
    <button
      :disabled="currentPage >= totalPages"
      class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
      @click="changePage(currentPage + 1)"
    >
      <span class="sr-only">Next</span>
      <ChevronRightIcon class="h-5 w-5" />
    </button>
  </nav>
</template>
