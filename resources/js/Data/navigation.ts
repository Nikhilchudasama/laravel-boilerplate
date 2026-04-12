export interface MenuItem {
    title: string;
    icon: string;
    route_name?: string;
    permission?: string;
    role?: string;
    external?: boolean;
    subMenu?: MenuItem[];
}

const menus: MenuItem[] = [
    {
        title: 'Dashboard',
        icon: 'home',
        route_name: 'admin.dashboard',
    },
    {
        title: 'Users',
        icon: 'users',
        route_name: 'admin.users.index',
        permission: 'view_users',
    },
    {
        title: 'Roles',
        icon: 'roles',
        route_name: 'admin.roles.index',
        permission: 'view_roles',
    },
    {
        title: 'Activity Log',
        icon: 'activity',
        route_name: 'admin.activity-log.index',
        permission: 'view_activity_logs',
    },
    {
        title: 'Performance',
        icon: 'performance',
        route_name: 'admin.pulse',
        role: 'admin',
        external: true,
    },
    {
        title: 'System Logs',
        icon: 'logs',
        route_name: 'admin.log-viewer',
        role: 'admin',
        external: true,
    },
];

export default menus;
