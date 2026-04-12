export interface UserFormData {
    name: string;
    email: string;
    password?: string;
    password_confirmation?: string;
    type: 'admin' | 'user';
    active: boolean | number;
    roles?: (string | number)[];
}

export interface RoleFormData {
    name: string;
    permissions?: (string | number)[];
}

export interface ProfileFormData {
    name: string;
    email: string;
    timezone?: string;
    avatar?: File | null;
    _method?: string;
}

export interface PasswordFormData {
    current_password: string;
    password: string;
    password_confirmation: string;
}

export interface LoginFormData {
    email: string;
    password: string;
    remember?: boolean;
}

export interface RegisterFormData {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
}

export interface ForgotPasswordFormData {
    email: string;
}

export interface ResetPasswordFormData {
    token: string;
    email: string;
    password: string;
    password_confirmation: string;
}

export interface ValidationErrors {
    [key: string]: string[];
}
