import { describe, it, expect, vi } from 'vitest';
import { useAuth } from '@/Composables/useAuth';
import { usePage } from '@inertiajs/vue3';

vi.mock('@inertiajs/vue3', () => ({
    usePage: vi.fn(),
}));

describe('useAuth', () => {
    it('returns null user when not authenticated', () => {
        (usePage as any).mockReturnValue({
            props: {
                auth: { user: null }
            }
        });

        const { user, isAuthenticated } = useAuth();
        expect(user.value).toBeNull();
        expect(isAuthenticated.value).toBe(false);
    });

    it('returns user and isAuthenticated true when logged in', () => {
        const mockUser = { id: 1, name: 'John', type: 'user' };
        (usePage as any).mockReturnValue({
            props: {
                auth: { user: mockUser }
            }
        });

        const { user, isAuthenticated } = useAuth();
        expect(user.value).toEqual(mockUser);
        expect(isAuthenticated.value).toBe(true);
    });

    it('identifies admin correctly', () => {
        (usePage as any).mockReturnValue({
            props: {
                auth: {
                    user: { id: 1, type: 'admin', roles: [] }
                }
            }
        });

        const { isAdmin } = useAuth();
        expect(isAdmin.value).toBe(true);
    });

    it('identifies admin via roles', () => {
        (usePage as any).mockReturnValue({
            props: {
                auth: {
                    user: { id: 1, type: 'user', roles: [{ name: 'admin' }] }
                }
            }
        });

        const { isAdmin } = useAuth();
        expect(isAdmin.value).toBe(true);
    });
});
