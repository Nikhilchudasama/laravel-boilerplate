// Common permissions
export const PERMISSIONS = {
    // Users
    VIEW_USERS: 'view users',
    CREATE_USERS: 'create users',
    EDIT_USERS: 'edit users',
    DELETE_USERS: 'delete users',

    // Roles
    VIEW_ROLES: 'view roles',
    CREATE_ROLES: 'create roles',
    EDIT_ROLES: 'edit roles',
    DELETE_ROLES: 'delete roles',

    // Admin Panel
    ACCESS_ADMIN_PANEL: 'access-admin-panel',
} as const;

export type Permission = typeof PERMISSIONS[keyof typeof PERMISSIONS];
