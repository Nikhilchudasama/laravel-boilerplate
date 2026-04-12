import { usePage } from '@inertiajs/vue3';

type ToastType = 'success' | 'error' | 'warning' | 'info';

export function useToast() {
    const page = usePage();

    const showToast = (message: string, type: ToastType = 'info') => {
        // Using Inertia's flash messages
        // The toast will be displayed by the layout component
        console.log(`[Toast ${type}]:`, message);
    };

    const success = (message: string) => {
        showToast(message, 'success');
    };

    const error = (message: string) => {
        showToast(message, 'error');
    };

    const warning = (message: string) => {
        showToast(message, 'warning');
    };

    const info = (message: string) => {
        showToast(message, 'info');
    };

    // Get flash messages from Inertia
    const flash = page.props.flash;

    return {
        showToast,
        success,
        error,
        warning,
        info,
        flash,
    };
}
