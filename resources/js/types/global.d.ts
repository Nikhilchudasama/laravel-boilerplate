import { AxioInstance } from 'axios';
import { route as routeFn } from 'ziggy-js';
import { User } from './models';

declare global {
    var route: typeof routeFn;
    interface Window {
        axios: AxioInstance;
    }
}

declare module '@inertiajs/core' {
    interface PageProps extends PageProps, AppPageProps { }
}



export type AppPageProps = {
    auth: {
        user: User | null;
    };
    [key: string]: unknown;
};
