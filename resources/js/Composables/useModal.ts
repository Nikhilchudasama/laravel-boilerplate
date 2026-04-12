import { ref } from 'vue';

export function useModal() {
    const isOpen = ref(false);

    const open = () => {
        isOpen.value = true;
        // Prevent body scroll when modal is open
        document.body.style.overflow = 'hidden';
    };

    const close = () => {
        isOpen.value = false;
        // Restore body scroll
        document.body.style.overflow = '';
    };

    const toggle = () => {
        if (isOpen.value) {
            close();
        } else {
            open();
        }
    };

    return {
        isOpen,
        open,
        close,
        toggle,
    };
}
