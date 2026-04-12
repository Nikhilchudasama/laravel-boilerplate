import { ref, onMounted, watch } from 'vue';

export function useTheme() {
    const isDark = ref(false);

    const initTheme = () => {
        // Check localStorage first
        const savedTheme = localStorage.getItem('theme');

        if (savedTheme) {
            isDark.value = savedTheme === 'dark';
        } else {
            // Check system preference
            isDark.value = window.matchMedia('(prefers-color-scheme: dark)').matches;
        }

        applyTheme();
    };

    const applyTheme = () => {
        if (isDark.value) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    };

    const toggleTheme = () => {
        isDark.value = !isDark.value;
        localStorage.setItem('theme', isDark.value ? 'dark' : 'light');
        applyTheme();
    };

    const setTheme = (dark: boolean) => {
        isDark.value = dark;
        localStorage.setItem('theme', dark ? 'dark' : 'light');
        applyTheme();
    };

    // Initialize on mount
    onMounted(() => {
        initTheme();
    });

    // Watch for changes
    watch(isDark, () => {
        applyTheme();
    });

    return {
        isDark,
        toggleTheme,
        setTheme,
    };
}
