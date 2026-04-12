import pluginVue from 'eslint-plugin-vue';
import tseslint from 'typescript-eslint';
import globals from 'globals';

export default [
  {
      languageOptions: {
          globals: {
              ...globals.browser,
              ...globals.node,
          },
      },
  },
  ...tseslint.configs.recommended,
  ...pluginVue.configs['flat/recommended'],
  {
    files: ['*.vue', '**/*.vue'],
    languageOptions: {
      parserOptions: {
        parser: '@typescript-eslint/parser'
      }
    }
  },
  {
      rules: {
          'vue/require-default-prop': 'off',
          'vue/multi-word-component-names': 'off',
      }
  }
];
