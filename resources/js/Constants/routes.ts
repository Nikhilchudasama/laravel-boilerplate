// Admin Routes
export const ADMIN_ROUTES = {
    DASHBOARD: 'admin.dashboard',

    // Users
    USERS_INDEX: 'admin.users.index',
    USERS_CREATE: 'admin.users.create',
    USERS_STORE: 'admin.users.store',
    USERS_EDIT: 'admin.users.edit',
    USERS_UPDATE: 'admin.users.update',
    USERS_DESTROY: 'admin.users.destroy',
    USERS_EXPORT: 'admin.users.export',

    // Roles
    ROLES_INDEX: 'admin.roles.index',
    ROLES_CREATE: 'admin.roles.create',
    ROLES_STORE: 'admin.roles.store',
    ROLES_EDIT: 'admin.roles.edit',
    ROLES_UPDATE: 'admin.roles.update',
    ROLES_DESTROY: 'admin.roles.destroy',
    ROLES_EXPORT: 'admin.roles.export',

    // Profile
    PROFILE: 'admin.profile',
    PROFILE_UPDATE: 'admin.profile.update',
    SECURITY: 'admin.security',
    PASSWORD_UPDATE: 'admin.password.update',

    // Impersonation
    IMPERSONATE: 'admin.impersonate',
    LEAVE_IMPERSONATION: 'admin.leave-impersonation',
} as const;

// Frontend Routes
export const FRONTEND_ROUTES = {
    HOME: 'home',
    DASHBOARD: 'dashboard',
    PROFILE: 'profile',
    PROFILE_UPDATE: 'profile.update',

    // Auth
    LOGIN: 'login',
    REGISTER: 'register',
    LOGOUT: 'logout',
} as const;

export type AdminRoute = typeof ADMIN_ROUTES[keyof typeof ADMIN_ROUTES];
export type FrontendRoute = typeof FRONTEND_ROUTES[keyof typeof FRONTEND_ROUTES];
