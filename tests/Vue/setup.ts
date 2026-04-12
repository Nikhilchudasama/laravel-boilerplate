import { vi } from 'vitest';

// Mock Inertia
vi.mock('@inertiajs/vue3', () => ({
    usePage: vi.fn(() => ({
        props: {
            auth: {
                user: null,
            },
            flash: {},
            errors: {},
        },
    })),
    router: {
        visit: vi.fn(),
        get: vi.fn(),
        post: vi.fn(),
        put: vi.fn(),
        patch: vi.fn(),
        delete: vi.fn(),
        reload: vi.fn(),
    },
}));
