import { route as routeFn } from 'ziggy-js';

declare module '@vue/runtime-core' {
    interface ComponentCustomProperties {
        $can: (permission: string) => boolean;
        $hasRole: (role: string) => boolean;
        route: typeof routeFn;
    }
}

export { }
