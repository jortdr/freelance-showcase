import js from "@eslint/js";
import eslintConfigPrettier from "eslint-config-prettier";
import pluginVue from "eslint-plugin-vue";

export default [
    js.configs.recommended,
    ...pluginVue.configs["flat/recommended"],
    // https://eslint.vuejs.org/user-guide/#conflict-with-prettier
    // We are using "prettier" instead of "@vue/prettier" to prevent any potential conflicts.
    // Make sure "prettier" is the last element in this list.
    eslintConfigPrettier,
    {
        files: ["resources/**/*.js", "resources/**/*.vue"],
        languageOptions: {
            ecmaVersion: 2024,
        },
        rules: {
            "no-console": process.env.VITE_APP_ENV === "production" ? "warn" : "off",
            "no-debugger": process.env.VITE_APP_ENV === "production" ? "warn" : "off",
            "no-unused-vars": "warn",
            "no-undef": "warn",
            "vue/valid-template-root": "off",
            "vue/no-unused-components": "warn",
            "vue/multi-word-component-names": "off",
            "vue/no-reserved-component-names": "off",
            "vue/no-side-effects-in-computed-properties": "off",
            "vue/no-v-html": "off",
            "vue/require-default-prop": "off",
        },
        plugins: {
            vue: pluginVue,
            prettier: {},
        },
    },
    {
        ignores: [
            "dist/**/*",
            "vendor/**/*",
            "node_modules/**/*",
            "babel.config.js",
            "eslint.config.js",
            "vite.config.js",
            "resources/**/tailwind.config.js",
            "resources/**/tailwind.settings.colors.js"
        ],
    },
];
