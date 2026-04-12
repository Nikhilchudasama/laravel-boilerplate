import { describe, it, expect, vi } from 'vitest';
import { usePermissions } from '@/Composables/usePermissions';
import { useAuth } from '@/Composables/useAuth';

vi.mock('@/Composables/useAuth', () => ({
    useAuth: vi.fn(),
}));

describe('usePermissions', () => {
    it('returns true for any permission if user is admin', () => {
        (useAuth as any).mockReturnValue({
            user: { value: { type: 'admin' } }
        });

        const { hasPermission } = usePermissions();
        expect(hasPermission('any_permission')).toBe(true);
    });

    it('checks specific permission for regular user', () => {
        (useAuth as any).mockReturnValue({
            user: {
                value: {
                    type: 'user',
                    permissions: [{ name: 'edit_users' }]
                }
            }
        });

        const { hasPermission } = usePermissions();
        expect(hasPermission('edit_users')).toBe(true);
        expect(hasPermission('delete_users')).toBe(false);
    });

    it('checks roles correctly', () => {
        (useAuth as any).mockReturnValue({
            user: {
                value: {
                    roles: [{ name: 'editor' }]
                }
            }
        });

        const { hasRole } = usePermissions();
        expect(hasRole('editor')).toBe(true);
        expect(hasRole('admin')).toBe(false);
    });
});
