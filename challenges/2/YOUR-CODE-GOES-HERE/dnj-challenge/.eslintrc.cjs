/* eslint-env node */
require('@rushstack/eslint-patch/modern-module-resolution')

module.exports = {
  root: true,
  extends: [
    'plugin:vue/vue3-essential',
    'eslint:recommended',
    '@vue/eslint-config-typescript/recommended',
    '@vue/eslint-config-prettier',
  ],
  rules: {
    semi: [2, 'never'],
    '@typescript-eslint/ban-types': 'off',
    'no-console': 'off',
    'vue/max-attributes-per-line': 'off',
    'vue/multi-word-component-names': 'off',
    camelcase: 'off',
    '@typescript-eslint/camelcase': 'off',
    'no-unused-vars': 'off',
    '@typescript-eslint/no-unused-vars': ['error'],
    'prettier/prettier': [
      'error',
      {
        htmlWhitespaceSensitivity: 'ignore',
        semi: false,
        singleQuote: true,
        endOfLine: 'auto',
      },
    ],
  },
}
