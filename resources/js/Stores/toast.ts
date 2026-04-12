import { reactive } from 'vue';

export interface Toast {
    id: number;
    message: string;
    type: 'success' | 'error' | 'info' | 'warning';
    duration?: number;
}

const state = reactive({
    items: [] as Toast[],
});

let nextId = 1;

const toast = {
    // Expose items so components can render them
    get items() {
        return state.items;
    },

    /**
     * Core add method
     */
    add(message: string, type: Toast['type'] = 'success', duration = 5000) {
        const id = nextId++;
        state.items.push({ id, message, type, duration });

        if (duration > 0) {
            setTimeout(() => {
                this.remove(id);
            }, duration);
        }

        return id;
    },

    success(message: string, duration?: number) {
        return this.add(message, 'success', duration);
    },

    error(message: string, duration?: number) {
        return this.add(message, 'error', duration);
    },

    info(message: string, duration?: number) {
        return this.add(message, 'info', duration);
    },

    warning(message: string, duration?: number) {
        return this.add(message, 'warning', duration);
    },

    remove(id: number) {
        const index = state.items.findIndex((item) => item.id === id);
        if (index > -1) {
            state.items.splice(index, 1);
        }
    },
};

export default toast;
