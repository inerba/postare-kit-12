import preset from '../../../../vendor/filament/filament/tailwind.config.preset';

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
    ],
};
