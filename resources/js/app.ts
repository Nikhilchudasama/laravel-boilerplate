import './bootstrap';
import '../css/app.css';
import type { User } from '@/types/models';

import { createApp, h, DefineComponent } from 'vue';
import { createInertiaApp, Head, Link } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from 'ziggy-js';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => title ? `${title} - ${appName}` : appName,
    resolve: (name) => {
        const page = resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./Pages/**/*.vue')
        );

        // eslint-disable-next-line @typescript-eslint/no-explicit-any
        page.then((module: any) => {
            // Apply AdminLayout for all pages in the Admin directory
            // Except for the Login page
            if (name.startsWith('Admin/') && !name.startsWith('Admin/Auth/')) {
                module.default.layout = module.default.layout || AdminLayout;
            }
        });

        return page;
    },
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .component('Head', Head)
            .component('Link', Link);

        // Global Permission Helpers
        app.config.globalProperties.$can = (permission: string) => {
            const user = (props.initialPage.props.auth as { user?: User })?.user;
            if (!user) return false;

            // Admin type has all permissions
            if (user.type === 'admin') return true;

            // Check if user has admin role (roles is array of objects)
            if (user.roles?.some((role: { name: string }) => role.name === 'admin')) return true;

            // Check if user has the specific permission (permissions is array of objects)
            return user.permissions?.some((perm: { name: string }) => perm.name === permission) ?? false;
        };

        app.config.globalProperties.$hasRole = (role: string) => {
            const user = (props.initialPage.props.auth as { user?: User })?.user;
            if (!user) return false;
            // roles is array of objects with 'name' property
            return user.roles?.some((r: { name: string }) => r.name === role) ?? false;
        };

        app.mount(el);
    },
    progress: {
        color: '#4f46e5',
        showSpinner: true,
    },
});
