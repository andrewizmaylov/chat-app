import globals from 'globals';
import pluginJs from '@eslint/js';
import pluginVue from 'eslint-plugin-vue';


export default [
  {files: ['**/*.{js,mjs,cjs,vue}']},
  {languageOptions: {globals: globals.browser}},
  pluginJs.configs.recommended,
  ...pluginVue.configs['flat/essential'],
  {
    rules: {
      // 'no-console': process.env.NODE_ENV === 'production' ? 'warn' : 'off',
      // 'no-debugger': process.env.NODE_ENV === 'production' ? 'warn' : 'off',
      'no-console': process.env.NODE_ENV === 'production' ? 'warn' : 'off',
      'no-debugger': process.env.NODE_ENV === 'production' ? 'warn' : 'off',
      'import/extensions': 'off',
      'no-unused-expressions': 'error',
      'no-unused-vars': ['error', {argsIgnorePattern: '^_'}],
      'max-len': ['error', {code: 180}],
      'quotes': ['error', 'single'],
      'semi': ['error', 'always'],
      'vue/component-definition-name-casing': ['error', 'PascalCase'],
      'vue/order-in-components': ['error', {
        'order': [
          'el',
          'name',
          'key',
          'parent',
          'functional',
          ['delimiters', 'comments'],
          ['components', 'directives', 'filters'],
          'extends',
          'mixins',
          ['provide', 'inject'],
          'ROUTER_GUARDS',
          'layout',
          'middleware',
          'validate',
          'scrollToTop',
          'transition',
          'loading',
          'inheritAttrs',
          'model',
          ['props', 'propsData'],
          'emits',
          'setup',
          'asyncData',
          'data',
          'fetch',
          'head',
          'computed',
          'watch',
          'watchQuery',
          'LIFECYCLE_HOOKS',
          'methods',
          ['template', 'render'],
          'renderError'
        ]
      }],
      'vue/max-attributes-per-line': ['error', {
        'singleline': {
          'max': 1
        },
        'multiline': {
          'max': 1
        }
      }],
      'vue/html-indent': ['error', 4, {
        'attribute': 1,
        'baseIndent': 1,
        'closeBracket': 0,
        'alignAttributesVertically': true,
        'ignores': []
      }],
      'vue/script-indent': ['error', 4, {'baseIndent': 0}],
      'vue/no-irregular-whitespace': ['error', {
        'skipStrings': true,
        'skipComments': false,
        'skipRegExps': false,
        'skipTemplates': false,
        'skipHTMLAttributeValues': false,
        'skipHTMLTextContents': false
      }],
      'vue/no-dupe-keys': [
        'error',
        {
          groups: [],
        },
      ],
      'vue/component-name-in-template-casing': [
        'error',
        'PascalCase',
        {
          registeredComponentsOnly: true,
        },
      ],
      'prefer-destructuring': [
        'error',
        {
          array: true,
          object: true,
        },
        {
          enforceForRenamedProperties: false,
        },
      ],
      'object-curly-newline': [
        'error',
        {
          ObjectExpression: {multiline: true, consistent: true},
          ObjectPattern: {multiline: true, consistent: true},
        },
      ],
      'lines-between-class-members': ['error', 'always', {exceptAfterSingleLine: true}],
      'padding-line-between-statements': [
        'error',
        {blankLine: 'always', prev: 'let', next: 'return'},
        {blankLine: 'always', prev: 'const', next: 'return'}
      ]
    }
  }
];