<script setup lang="ts">
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { ChevronDownIcon, ArrowDownTrayIcon } from '@heroicons/vue/20/solid';
defineEmits(['export-excel']);
</script>

<template>
  <Menu
    as="div"
    class="relative inline-block text-left"
  >
    <div>
      <MenuButton
        class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white dark:bg-gray-800 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-200 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"
      >
        <ArrowDownTrayIcon
          class="-ml-0.5 h-5 w-5 text-gray-400"
          aria-hidden="true"
        />
        Export
        <ChevronDownIcon
          class="-mr-1 h-5 w-5 text-gray-400"
          aria-hidden="true"
        />
      </MenuButton>
    </div>

    <transition
      enter-active-class="transition ease-out duration-100"
      enter-from-class="transform opacity-0 scale-95"
      enter-to-class="transform opacity-100 scale-100"
      leave-active-class="transition ease-in duration-75"
      leave-from-class="transform opacity-100 scale-100"
      leave-to-class="transform opacity-0 scale-95"
    >
      <MenuItems
        class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
      >
        <div class="py-1">
          <MenuItem v-slot="{ active }">
            <button
              type="button"
              :class="[
                active ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-200',
                'block w-full px-4 py-2 text-left text-sm',
              ]"
              @click="$emit('export-excel')"
            >
              Export to Excel
            </button>
          </MenuItem>
        </div>
      </MenuItems>
    </transition>
  </Menu>
</template>
