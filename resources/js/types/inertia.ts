import { User } from './models';

export interface PageProps {
    auth: {
        user: User | null;
    };
    flash: {
        success?: string;
        error?: string;
        warning?: string;
        info?: string;
    };
    errors: Record<string, string>;
    [key: string]: unknown;
}

export interface SharedProps {
    auth: {
        user: User | null;
    };
    flash: {
        success?: string;
        error?: string;
        warning?: string;
        info?: string;
    };
}
