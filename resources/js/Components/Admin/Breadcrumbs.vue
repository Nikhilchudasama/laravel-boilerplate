<script setup lang="ts">
import { computed } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
import { ChevronRightIcon, HomeIcon } from '@heroicons/vue/20/solid';
import { route } from 'ziggy-js';

const page = usePage();
const currentRouteName = computed(() => page.props.current_route_name as string);

interface Breadcrumb {
  label: string;
  href?: string;
  active: boolean;
}

const breadcrumbs = computed(() => {
  if (!currentRouteName.value) return [];

  const items: Breadcrumb[] = [
    { label: 'Dashboard', href: route('admin.dashboard'), active: currentRouteName.value === 'admin.dashboard' }
  ];

  if (currentRouteName.value === 'admin.dashboard') return items;

  // Split admin.users.index -> ['admin', 'users', 'index']
  const parts = currentRouteName.value.split('.');

  // Handle specific resource logic
  if (parts[1]) {
    const resource = parts[1].charAt(0).toUpperCase() + parts[1].slice(1).replace('-', ' ');
    const indexRoute = `admin.${parts[1]}.index`;

    // Check if index route exists in ziggy (prevent crash if index doesn't exist)
    let indexHref = '#';
    try { indexHref = route(indexRoute); } catch { }

    items.push({
      label: resource,
      href: indexHref,
      active: currentRouteName.value === indexRoute
    });

    // Handle sub-action (create, edit, show)
    if (parts[2] && parts[2] !== 'index') {
      const action = parts[2].charAt(0).toUpperCase() + parts[2].slice(1);
      items.push({ label: action, active: true });
    }
  }

  return items;
});
</script>

<template>
  <nav
    class="flex mb-6"
    aria-label="Breadcrumb"
  >
    <ol
      role="list"
      class="flex items-center space-x-2"
    >
      <li>
        <div>
          <Link
            :href="route('admin.dashboard')"
            class="text-zinc-400 hover:text-zinc-500 dark:hover:text-zinc-300"
          >
            <HomeIcon
              class="h-5 w-5 flex-shrink-0"
              aria-hidden="true"
            />
            <span class="sr-only">Dashboard</span>
          </Link>
        </div>
      </li>
      <li
        v-for="item in breadcrumbs.slice(1)"
        :key="item.label"
      >
        <div class="flex items-center">
          <ChevronRightIcon
            class="h-5 w-5 flex-shrink-0 text-zinc-300 dark:text-zinc-600"
            aria-hidden="true"
          />
          <Link
            v-if="!item.active"
            :href="item.href || '#'"
            class="ml-2 text-sm font-medium text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200"
          >
            {{ item.label }}
          </Link>
          <span
            v-else
            class="ml-2 text-sm font-medium text-indigo-600 dark:text-indigo-400"
            aria-current="page"
          >
            {{ item.label }}
          </span>
        </div>
      </li>
    </ol>
  </nav>
</template>
