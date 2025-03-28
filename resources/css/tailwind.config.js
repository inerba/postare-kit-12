/** @type {import('tailwindcss').Config} */
const colors = require('tailwindcss/colors');

module.exports = {
    content: ['./resources/views/**/*.blade.php'],
    darkMode: 'selector',
    theme: {
        extend: {
            colors: {
                brand: colors.amber,
                neutral: colors.neutral,
                error: colors.red,
                warning: colors.amber,
                info: colors.blue,
                success: colors.emerald,
                'brand-primary': colors.amber[500],
                'brand-primary-dark': colors.amber[600],
                'brand-primary-light': colors.amber[400],
                'brand-secondary': colors.lime[500],
                'brand-secondary-dark': colors.lime[600],
                'brand-secondary-light': colors.lime[400],
                'default-font': colors.neutral[900],
                'subtext-color': colors.neutral[500],
                'neutral-border': colors.neutral[200],
                white: colors.white,
                'default-background': colors.neutral[50],
            },
            fontFamily: {
                sans: ['DM Sans', 'sans-serif'], // Ensure DM Sans is the default sans-serif font
                caption: 'DM Sans',
                'caption-bold': 'DM Sans',
                body: 'DM Sans',
                'body-bold': 'DM Sans',
                'heading-3': 'DM Sans',
                'heading-2': 'DM Sans',
                'heading-1': 'DM Sans',
                'monospace-body': 'monospace',
            },
        },
    },

    // safelist: ['grid-cols-2', 'grid-cols-3', 'grid-cols-4', 'grid-cols-5', 'grid-cols-6'],
    plugins: [require('@tailwindcss/typography'), require('@tailwindcss/container-queries')],
};
