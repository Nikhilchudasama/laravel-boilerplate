import { ref } from 'vue';

const isDark = ref(localStorage.getItem('theme') === 'dark');

const updateTheme = (dark: boolean) => {
    isDark.value = dark;
    localStorage.setItem('theme', dark ? 'dark' : 'light');
};

const initTheme = () => {
    const savedTheme = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

    if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
        updateTheme(true);
    } else {
        updateTheme(false);
    }
};

const toggleTheme = () => {
    updateTheme(!isDark.value);
};

export { isDark, initTheme, toggleTheme };
