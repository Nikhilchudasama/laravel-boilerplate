/**
 * Format a date string to a human-readable format
 */
export function formatDate(date: string | Date, format: 'short' | 'long' | 'time' = 'short'): string {
    const d = typeof date === 'string' ? new Date(date) : date;

    if (format === 'short') {
        return d.toLocaleDateString();
    }

    if (format === 'long') {
        return d.toLocaleDateString(undefined, {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
        });
    }

    if (format === 'time') {
        return d.toLocaleString();
    }

    return d.toLocaleDateString();
}

/**
 * Format a date to relative time (e.g., "2 hours ago")
 */
export function formatRelativeTime(date: string | Date): string {
    const d = typeof date === 'string' ? new Date(date) : date;
    const now = new Date();
    const diffInSeconds = Math.floor((now.getTime() - d.getTime()) / 1000);

    if (diffInSeconds < 60) {
        return 'just now';
    }

    const diffInMinutes = Math.floor(diffInSeconds / 60);
    if (diffInMinutes < 60) {
        return `${diffInMinutes} minute${diffInMinutes > 1 ? 's' : ''} ago`;
    }

    const diffInHours = Math.floor(diffInMinutes / 60);
    if (diffInHours < 24) {
        return `${diffInHours} hour${diffInHours > 1 ? 's' : ''} ago`;
    }

    const diffInDays = Math.floor(diffInHours / 24);
    if (diffInDays < 30) {
        return `${diffInDays} day${diffInDays > 1 ? 's' : ''} ago`;
    }

    const diffInMonths = Math.floor(diffInDays / 30);
    if (diffInMonths < 12) {
        return `${diffInMonths} month${diffInMonths > 1 ? 's' : ''} ago`;
    }

    const diffInYears = Math.floor(diffInMonths / 12);
    return `${diffInYears} year${diffInYears > 1 ? 's' : ''} ago`;
}

/**
 * Format a number as currency
 */
export function formatCurrency(amount: number, currency: string = 'USD'): string {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency,
    }).format(amount);
}

/**
 * Format a number with thousand separators
 */
export function formatNumber(num: number): string {
    return new Intl.NumberFormat().format(num);
}

/**
 * Truncate text to a specified length
 */
export function truncate(text: string, length: number = 50, suffix: string = '...'): string {
    if (text.length <= length) {
        return text;
    }
    return text.substring(0, length) + suffix;
}
