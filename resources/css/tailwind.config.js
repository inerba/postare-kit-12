/** @type {import('tailwindcss').Config} */
const colors = require('tailwindcss/colors');

module.exports = {
    // Specifica i percorsi dei file in cui Tailwind deve cercare le classi CSS
    content: [
        './resources/views/**/*.blade.php',
        './app/Mason/**/*.php',
        './app/Traits/**/*.php',
        './app/Filament/**/*.php',
    ],
    // Abilita la modalità scura basata su un selettore CSS
    darkMode: 'selector',
    theme: {
        extend: {
            colors: {
                // Definisci i colori personalizzati per il tema
                gray: colors.neutral,
                white: colors.white,
                black: colors.black,

                // Colori secchi
                primary: {
                    DEFAULT: '#457b9d',
                    50: '#f4f7fb',
                    100: '#e8eff6',
                    200: '#cddeea',
                    300: '#a1c2d8',
                    400: '#6ea2c2',
                    500: '#457b9d',
                    600: '#396b90',
                    700: '#2f5775',
                    800: '#2a4a62',
                    900: '#274053',
                    950: '#1a2937',
                },
                secondary: '#1D3557',
                tertiary: '#A8DADC',
                quaternary: '#F1FAEE',
                accent: {
                    DEFAULT: '#e66d39',
                    50: '#fdf5ef',
                    100: '#fbe8d9',
                    200: '#f6cfb2',
                    300: '#f0ae81',
                    400: '#ea834d',
                    500: '#e66d39',
                    600: '#d64a20',
                    700: '#b1381d',
                    800: '#8e2e1e',
                    900: '#72291c',
                    950: '#3e120c',
                },
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
