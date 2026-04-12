
/**
 * Format a date string into a human-readable format based on the user's localized settings.
 * Defaults to the browser's locale and timezone.
 */
export const formatDate = (date: string | Date | null, options: Intl.DateTimeFormatOptions = {}) => {
    if (!date) return '';

    const d = typeof date === 'string' ? new Date(date) : date;

    const defaultOptions: Intl.DateTimeFormatOptions = {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        ...options
    };

    return new Intl.DateTimeFormat(navigator.language, defaultOptions).format(d);
};

/**
 * Simple date only formatter
 */
export const dateOnly = (date: string | Date | null) => {
    return formatDate(date, { hour: undefined, minute: undefined });
};
