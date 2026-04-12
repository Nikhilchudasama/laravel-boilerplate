<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

defineProps<{
    stats: {
        total_users: number;
        active_users: number;
        total_roles: number;
    };
    recentActivities: Array<{
        id: number;
        description: string;
        subject_type: string;
        causer_name: string;
        created_at: string;
    }>;
}>();
</script>

<template>
  <Head title="Dashboard" />

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-100 dark:border-gray-700">
      <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase tracking-wider">
        Total Users
      </h3>
      <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
        {{ stats.total_users }}
      </p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-100 dark:border-gray-700">
      <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase tracking-wider">
        Active Users
      </h3>
      <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-2">
        {{ stats.active_users }}
      </p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border border-gray-100 dark:border-gray-700">
      <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase tracking-wider">
        Total Roles
      </h3>
      <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400 mt-2">
        {{ stats.total_roles }}
      </p>
    </div>
  </div>

  <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-100 dark:border-gray-700">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
      <h3 class="text-lg font-medium text-gray-900 dark:text-white">
        Recent Activity
      </h3>
      <Link
        :href="route('admin.activity-log.index')"
        class="text-sm text-indigo-600 hover:text-indigo-500 font-medium"
      >
        View All
      </Link>
    </div>
    <div class="p-0">
      <div
        v-if="recentActivities.length > 0"
        class="divide-y divide-gray-200 dark:divide-gray-700"
      >
        <div
          v-for="activity in recentActivities"
          :key="activity.id"
          class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors"
        >
          <div class="flex justify-between items-start">
            <div>
              <p class="text-sm text-gray-900 dark:text-white font-medium">
                {{ activity.description }}
              </p>
              <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                By <span class="font-semibold">{{ activity.causer_name }}</span> • {{ activity.subject_type.split('\\').pop() }}
              </p>
            </div>
            <span class="text-xs text-gray-400 dark:text-gray-500 whitespace-nowrap ml-4">
              {{ activity.created_at }}
            </span>
          </div>
        </div>
      </div>
      <div
        v-else
        class="p-12 text-center"
      >
        <p class="text-gray-500 dark:text-gray-400">
          No recent activity found.
        </p>
      </div>
    </div>
  </div>
</template>

