<script setup lang="ts">
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';
import { XMarkIcon } from '@heroicons/vue/24/outline';

const props = defineProps<{
  isOpen: boolean;
  columns: { key: string; label: string; hidden?: boolean }[];
}>();

const emit = defineEmits(['close', 'update:columns']);

const toggleColumn = (key: string) => {
  const updatedColumns = props.columns.map(col => {
    if (col.key === key) {
      return { ...col, hidden: !col.hidden };
    }
    return col;
  });
  emit('update:columns', updatedColumns);
};
</script>

<template>
  <TransitionRoot
    as="template"
    :show="isOpen"
  >
    <Dialog
      as="div"
      class="relative z-50"
      @close="$emit('close')"
    >
      <TransitionChild
        as="template"
        enter="ease-out duration-300"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="ease-in duration-200"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
      </TransitionChild>

      <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
          <TransitionChild
            as="template"
            enter="ease-out duration-300"
            enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-to="opacity-100 translate-y-0 sm:scale-100"
            leave="ease-in duration-200"
            leave-from="opacity-100 translate-y-0 sm:scale-100"
            leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          >
            <DialogPanel
              class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6"
            >
              <div class="absolute right-0 top-0 hidden pr-4 pt-4 sm:block">
                <button
                  type="button"
                  class="rounded-md bg-white dark:bg-gray-800 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                  @click="$emit('close')"
                >
                  <span class="sr-only">Close</span>
                  <XMarkIcon
                    class="h-6 w-6"
                    aria-hidden="true"
                  />
                </button>
              </div>
              <div class="sm:flex sm:items-start">
                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                  <DialogTitle
                    as="h3"
                    class="text-base font-semibold leading-6 text-gray-900 dark:text-white"
                  >
                    Manage Columns
                  </DialogTitle>
                  <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-4">
                    <div class="space-y-4">
                      <div
                        v-for="col in columns"
                        :key="col.key"
                        class="relative flex items-start"
                      >
                        <div class="flex h-6 items-center">
                          <input
                            :id="`col-${col.key}`"
                            :name="`col-${col.key}`"
                            type="checkbox"
                            :checked="!col.hidden"
                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"
                            @change="toggleColumn(col.key)"
                          >
                        </div>
                        <div class="ml-3 text-sm leading-6">
                          <label
                            :for="`col-${col.key}`"
                            class="font-medium text-gray-900 dark:text-gray-300 w-full cursor-pointer"
                          >{{
                            col.label }}</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                <button
                  type="button"
                  class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 sm:mt-0 sm:w-auto"
                  @click="$emit('close')"
                >
                  Done
                </button>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>
