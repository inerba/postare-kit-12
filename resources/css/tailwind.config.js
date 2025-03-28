/** @type {import('tailwindcss').Config} */
const colors = require('tailwindcss/colors');

module.exports = {
    // Specifica i percorsi dei file in cui Tailwind deve cercare le classi CSS
    content: ['./resources/views/**/*.blade.php', './app/Mason/**/*.php'],
    // Abilita la modalità scura basata su un selettore CSS
    darkMode: 'selector',
    theme: {
        extend: {
            colors: {
                // Definisci i colori personalizzati per il tema
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
                black: colors.black,
                muted: colors.neutral[400],
                'default-background': colors.neutral[50],
            },
            fontFamily: {
                sans: ['DM Sans', 'sans-serif'], // Assicura che DM Sans sia il font sans-serif predefinito
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
