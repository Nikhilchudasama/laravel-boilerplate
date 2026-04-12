import { computed } from 'vue';
import { useAuth } from './useAuth';
import type { Permission, Role } from '@/types/models';

export function usePermissions() {
    const { user } = useAuth();

    const hasPermission = (permission: string): boolean => {
        if (!user.value) return false;

        // Admin has all permissions
        if (user.value.type === 'admin') return true;

        return user.value.permissions?.some((p: Permission) => p.name === permission) || false;
    };

    const hasAnyPermission = (permissions: string[]): boolean => {
        return permissions.some(permission => hasPermission(permission));
    };

    const hasAllPermissions = (permissions: string[]): boolean => {
        return permissions.every(permission => hasPermission(permission));
    };

    const hasRole = (role: string): boolean => {
        if (!user.value) return false;
        return user.value.roles?.some((r: Role) => r.name === role) || false;
    };

    const hasAnyRole = (roles: string[]): boolean => {
        return roles.some(role => hasRole(role));
    };

    const hasAllRoles = (roles: string[]): boolean => {
        return roles.every(role => hasRole(role));
    };

    const permissions = computed(() => user.value?.permissions || []);
    const roles = computed(() => user.value?.roles || []);

    return {
        hasPermission,
        hasAnyPermission,
        hasAllPermissions,
        hasRole,
        hasAnyRole,
        hasAllRoles,
        permissions,
        roles,
    };
}
