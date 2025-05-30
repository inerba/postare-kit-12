import preset from '../../../../vendor/filament/filament/tailwind.config.preset';

const colors = require('tailwindcss/colors');

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './app/Mason/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './resources/views/mason/**/*.blade.php',
        './resources/views/components/**/*.blade.php',
        './vendor/awcodes/matinee/resources/views/**/*.blade.php',
        './vendor/awcodes/filament-tiptap-editor/resources/**/*.blade.php',
        './vendor/awcodes/mason/resources/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './vendor/bezhansalleh/filament-exceptions/resources/views/**/*.blade.php', // Language Switch Views
        './vendor/awcodes/mason/resources/**/*.blade.php',
        './vendor/awcodes/palette/resources/views/**/*.blade.php',
    ],
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
        },
    },
};
