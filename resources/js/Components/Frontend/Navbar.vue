<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import type { PageProps } from '@/types/inertia';
import {
  Squares2X2Icon,
  BellIcon,
  UserIcon
} from '@heroicons/vue/24/outline';
import ThemeSwitcher from '@/Components/Frontend/ThemeSwitcher.vue';
import Button from '@/Components/Form/Button.vue';
import { computed } from 'vue';

const page = usePage<PageProps>();
const user = computed(() => page.props.auth?.user);
</script>

<template>
  <nav
    class="bg-white dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-800 sticky top-0 z-30 transition-colors duration-500"
  >
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex items-center space-x-3">
          <Link
            href="/dashboard"
            class="flex items-center space-x-3 group"
          >
            <div class="p-2 bg-indigo-600 rounded-lg group-hover:bg-indigo-700 transition-colors">
              <Squares2X2Icon class="h-6 w-6 text-white" />
            </div>
            <span class="text-xl font-bold text-zinc-900 dark:text-white">Customer Hub</span>
          </Link>
        </div>

        <div class="flex items-center space-x-4">
          <ThemeSwitcher />

          <button class="p-2 text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300 transition-colors">
            <BellIcon class="h-6 w-6" />
          </button>

          <div class="flex items-center space-x-3 pl-4 border-l border-zinc-200 dark:border-zinc-800">
            <div class="h-8 w-8 rounded-full overflow-hidden border border-zinc-300 dark:border-zinc-700">
              <img
                v-if="user?.avatar_url"
                :src="user.avatar_url"
                class="h-full w-full object-cover"
                alt="Avatar"
              >
              <div
                v-else
                class="h-full w-full bg-zinc-200 dark:bg-zinc-800 flex items-center justify-center"
              >
                <UserIcon class="h-5 w-5 text-zinc-500" />
              </div>
            </div>

            <div class="hidden sm:flex flex-col">
              <span class="text-sm font-semibold text-zinc-900 dark:text-white leading-tight">{{
                user?.name }}</span>
              <!-- Frontend Profile Links -->
              <div class="flex space-x-2 mt-1">
                <Link
                  :href="route('profile')"
                  class="text-[10px] uppercase font-bold text-indigo-600 hover:text-indigo-500"
                >
                  Profile
                </Link>
                <span class="text-[10px] text-zinc-300">|</span>
                <Link
                  :href="route('security')"
                  class="text-[10px] uppercase font-bold text-indigo-600 hover:text-indigo-500"
                >
                  Security
                </Link>
              </div>
            </div>

            <Button
              href="/logout"
              method="post"
              as="button"
              variant="ghost"
              size="sm"
              class="ml-2 !text-xs !font-bold !text-zinc-500 hover:!text-red-500 uppercase tracking-widest"
            >
              Exit
            </Button>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>
