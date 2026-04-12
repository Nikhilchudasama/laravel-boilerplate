/**
 * Validate email address
 */
export function isValidEmail(email: string): boolean {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

/**
 * Validate password strength
 * At least 8 characters, 1 uppercase, 1 lowercase, 1 number
 */
export function isStrongPassword(password: string): boolean {
    const minLength = password.length >= 8;
    const hasUppercase = /[A-Z]/.test(password);
    const hasLowercase = /[a-z]/.test(password);
    const hasNumber = /[0-9]/.test(password);

    return minLength && hasUppercase && hasLowercase && hasNumber;
}

/**
 * Validate URL
 */
export function isValidUrl(url: string): boolean {
    try {
        new URL(url);
        return true;
    } catch {
        return false;
    }
}

/**
 * Validate required field
 */
export function isRequired(value: unknown): boolean {
    if (typeof value === 'string') {
        return value.trim().length > 0;
    }
    return value !== null && value !== undefined;
}

/**
 * Validate minimum length
 */
export function minLength(value: string, min: number): boolean {
    return value.length >= min;
}

/**
 * Validate maximum length
 */
export function maxLength(value: string, max: number): boolean {
    return value.length <= max;
}

/**
 * Validate numeric value
 */
export function isNumeric(value: string): boolean {
    return !isNaN(Number(value));
}

/**
 * Validate phone number (basic)
 */
export function isValidPhone(phone: string): boolean {
    const phoneRegex = /^[\d\s\-\+\(\)]+$/;
    return phoneRegex.test(phone) && phone.replace(/\D/g, '').length >= 10;
}
