export interface User {
    id: string;
    name: string;
    email: string;
    email_verified_at: string | null;
    type: 'admin' | 'user';
    active: boolean;
    timezone: string;
    last_login_at: string | null;
    last_login_ip: string | null;
    password_changed_at: string | null;
    avatar_url: string;
    created_at: string;
    updated_at: string;
    google2fa_secret?: string;
    roles?: Role[];
    permissions?: Permission[];
}

export interface Role {
    id: number;
    name: string;
    guard_name: string;
    created_at: string;
    updated_at: string;
    permissions?: Permission[];
}

export interface Permission {
    id: number;
    name: string;
    guard_name: string;
    created_at: string;
    updated_at: string;
}

export interface PaginatedData<T> {
    data: T[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number | null;
    to: number | null;
}

export interface Media {
    id: number;
    file_name: string;
    mime_type: string;
    size: number;
    url: string;
    created_at: string;
}
