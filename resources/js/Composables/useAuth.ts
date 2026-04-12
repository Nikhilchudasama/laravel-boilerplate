import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import type { User } from '@/types/models';

export function useAuth() {
    const page = usePage();
    // Explicitly define the auth shape expected, using unknown cast for safety between global/local model types
    const user = computed<User | null>(() => ((page.props.auth as unknown) as { user: User })?.user || null);

    const isAuthenticated = computed(() => user.value !== null);

    const isAdmin = computed(() => {
        if (!user.value) return false;
        return user.value.type === 'admin' || user.value.roles?.some(role => role.name === 'admin') || false;
    });

    const isUser = computed(() => {
        if (!user.value) return false;
        return user.value.type === 'user';
    });

    return {
        user,
        isAuthenticated,
        isAdmin,
        isUser,
    };
}
