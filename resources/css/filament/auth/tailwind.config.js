import preset from '../../../../vendor/filament/filament/tailwind.config.preset';

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './vendor/bezhansalleh/filament-exceptions/resources/views/**/*.blade.php', // Language Switch Views
    ],
};
