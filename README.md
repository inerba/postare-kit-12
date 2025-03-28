# Postare Kit 12

Un moderno starter kit basato sul TALL stack (Tailwind CSS, Alpine.js, Laravel, Livewire) con Filament per il backend.

## 🚀 Tecnologie Utilizzate

- **Laravel 12.x** - Framework PHP
- **Filament 3.3** - Admin Panel e CRUD Builder
- **Tailwind CSS 3.4** - Framework CSS Utility-First
- **Alpine.js** - Framework JavaScript leggero
- **Vite** - Build tool e bundler
- **PHP 8.2+** - Linguaggio di programmazione

## Plugin preinstallati

- [Spatie Media Library](https://filamentphp.com/plugins/filament-spatie-media-library)
- [Exception Viewer](https://github.com/bezhansalleh/filament-exceptions)
- [Activity logger for filament](https://github.com/z3d0x/filament-logger)
- [Shield](https://github.com/bezhanSalleh/filament-shield)
- [Filament Impersonate](https://github.com/stechstudio/filament-impersonate)
- [DB CONFIG](https://github.com/postare/db-config)
- [Mason](https://github.com/awcodes/mason)
- [Matinee](https://github.com/awcodes/Matinee)
- [Filament Tiptap Editor](https://github.com/awcodes/filament-tiptap-editor)
- [Palette](https://github.com/awcodes/palette)

## 📋 Requisiti di Sistema

- PHP >= 8.2
- Composer
- Node.js >= 18
- NPM >= 9
- MySQL >= 8.0 o PostgreSQL >= 13

## 🛠️ Installazione

1. Clona il repository:

```bash
git clone [url-repository]
cd postare-kit-12
```

2. Installa le dipendenze PHP:

```bash
composer install
```

3. Installa le dipendenze NPM:

```bash
npm install
```

4. Copia il file .env:

```bash
cp .env.example .env
```

5. Genera la chiave dell'applicazione:

```bash
php artisan key:generate
```

6. Configura il database nel file `.env`

7. Esegui le migrazioni e i seeder:

```bash
php artisan migrate --seed
```
8. Aggiungi l'utente creato in vfase di seed tra i super user:
```bash
php artisan shield:super-admin
```

9.  Compila gli assets:

```bash
npm run build
```

## 🚀 Sviluppo

Rigenerare i permiessi di Shield:

```bash
php artisan shield:generate --all --ignore-existing-policies --panel=auth
```

Questo comando avvierà:

- Server Laravel
- Queue worker
- Vite dev server

# Simple Menu Manager

### Included Menu Item Types

- **Link**: a simple customizable link.
- **Page**: automatically generates a link by selecting a page
- **Placeholder**: a placeholder, perfect for organizing submenus.

### Extensibility

You can quickly and easily create new menu item types using the included dedicated command. This makes it an ideal solution for projects requiring a scalable and customizable menu system.

## Command to Create Custom Handlers

Creating new menu item types is quick and easy thanks to the included dedicated command:

```bash
# Syntax: php artisan make:menu-handler {name} {panel?}

# Example: A menu item for your blog categories
php artisan make:menu-handler BlogCategory
```

Replace `{name}` with the name of your new menu item type.
The command will generate a new handler class that you can customize to suit your specific needs.
If you’re using multiple panels, include the `{panel}` argument to specify the target panel.

### Generated Handler Class

When you use the custom handler command, this is what the generated menu handler class will look like:

```php
namespace App\Filament\SimpleMenu\Handlers;

use Filament\Forms\Components;
use Postare\SimpleMenuManager\Filament\Resources\MenuResource\MenuTypeHandlers\MenuTypeInterface;
use Postare\SimpleMenuManager\Filament\Resources\MenuResource\Traits\CommonFieldsTrait;

class BlogCategoryHandler implements MenuTypeInterface
{
    use CommonFieldsTrait;

    public function getName(): string
    {
        // If necessary, you can modify the name of the menu type
        return "Blog Category";
    }

    public static function getFields(): array
    {
        // Add the necessary fields for your menu type in this array
        return [
            // Components\TextInput::make('url')
            //     ->label('URL')
            //     ->required()
            //     ->columnSpanFull(),

            // Common fields for all menu types
            Components\Section::make(__('simple-menu-manager::simple-menu-manager.common.advanced_settings'))
                ->schema(self::commonLinkFields())
                ->collapsed(),
        ];
    }
}
```

You can add all the fields you need using the familiar and standard FilamentPHP components, giving you full flexibility to tailor your menu items to your project’s requirements.

### Adding the Livewire Component to Your Page

Don’t forget to specify the menu's slug when adding the Livewire component to your page:

```html
<livewire:menu slug="main-menu" />
```

Below is the structure of the Livewire component:

```php
<div @class([
    'hidden' => !$menu,
])>
    <x-dynamic-component :component="'menus.' . $slug . $variant" :name="$menu->name" :items="$menu->items" />
</div>
```

As you can see, the implementation is straightforward. Thanks to `<x-dynamic-component>`, you have the freedom to create custom menu components tailored to your needs. You can also define different menu variants simply by appending them to the component's name.

#### Component Example

Create the following file structure to define your custom menu:

- `index.blade.php` in `resources/views/components/menus/main-menu`

```php
@props([
    'name' => null,
    'items' => [],
])

<div class="flex flex-col -mx-6 lg:mx-8 lg:flex-row lg:items-center">
    @foreach ($items as $item)
        <x-menus.main-menu.item :item="$item" />
    @endforeach
</div>
```

- `item.blade.php` in `resources/views/components/menus/main-menu`

```php
@props([
    'item' => null,
    'active' => false,
])

@php
    $itemClasses = 'leading-none px-3 py-2 mx-3 mt-2 text-gray-700 transition-colors duration-300 transform rounded-md hover:bg-gray-100 lg:mt-0 dark:text-gray-200 dark:hover:bg-gray-700';
@endphp

@if (isset($item['children']))
<x-menus.dropdown>
    <x-slot:trigger>
            {{ $item['label'] }}
        </x-slot>

        @foreach ($item['children'] as $child)
        <x-menus.main-menu.item :item="$child" class="px-4 py-4 mx-0 rounded-none" />
        @endforeach
</x-menus.dropdown>
@else
    @if (isset($item['url']))
    <a href="{{ $item['url'] }}"
        @if (isset($item['target'])) target="{{ $item['target'] }}" @endif
        @if(isset($item['rel'])) rel="{{ $item['rel'] }}" @endif
        {{ $attributes->class([$itemClasses, '' =>active_route($item['url'])]) }}">
        {{ $item['label'] }}
    </a>
    @else
    <span {{ $attributes->class([$itemClasses, '' => $active]) }}>
        {{ $item['label'] }}
    </span>
    @endif
@endif

```

- `dropdown.blade.php` in `resources/views/components/menus`

```php
@props([
    'trigger' => null,
])

<div
    x-data="{
        open: true,
        toggle() {
            if (this.open) {
                return this.close()
            }

            this.$refs.button.focus()

            this.open = true
        },
        close(focusAfter) {
            if (! this.open) return

            this.open = false

            focusAfter && focusAfter.focus()
        },
    }"
    x-on:keydown.escape.prevent.stop="close($refs.button)"
    x-on:focusin.window="! $refs.panel.contains($event.target) && close()"
    x-id="['dropdown-button']"
    class="relative"
>
    <!-- Button -->
    <button
        x-ref="button"
        x-on:click="toggle()"
        type="button"
        :aria-expanded="open"
        :aria-controls="$id('dropdown-button')"
        class="relative flex items-center justify-center gap-2 px-3 py-2 text-gray-700 transition-colors duration-300 transform rounded-md whitespace-nowrap hover:bg-gray-100 lg:mt-0 dark:text-gray-200 dark:hover:bg-gray-700"
    >
        {{ $trigger }}

        @svg('heroicon-m-chevron-down', ['class' => 'size-4', 'x-bind:class' => '{ "rotate-180": open }'])
    </button>

    <!-- Panel -->
    <div
        x-ref="panel"
        x-show="open"
        x-transition.origin.top.left
        x-on:click.outside="close($refs.button)"
        :id="$id('dropdown-button')"
        x-cloak
        class="absolute left-0 z-10 flex flex-col mt-2 overflow-hidden origin-top-left bg-white border border-gray-200 rounded-lg shadow-sm outline-none min-w-48"
    >
        {{ $slot }}
    </div>
</div>
```

### Variants Explained

The Simple Menu Manager supports menu **variants**, allowing you to reuse the same menu structure with different designs or behaviors.

#### How to Use Variants

Pass the `variant` parameter in the Livewire component:

```html
<livewire:menu slug="main-menu" variant="footer" />
```

This tells the system to look for the corresponding Blade file:

`resources/views/components/menus/{slug}/{variant}.blade.php`

## 🧪 Testing

Il progetto utilizza Pest per i test. Per eseguire i test:

```bash
./vendor/bin/pest
```

## 📦 Struttura del Progetto

```
postare-kit-12/
├── app/                # Logica dell'applicazione
├── config/            # File di configurazione
├── database/          # Migrazioni e seeder
├── lang/              # File di traduzione
├── resources/         # Assets e viste
├── routes/            # Definizione delle route
├── storage/           # File di storage
└── tests/             # Test dell'applicazione
```

## 🔧 Strumenti di Sviluppo

- **Laravel Pint** - Formattatore di codice PHP
- **Laravel Debugbar** - Debug toolbar
- **Prettier** - Formattatore di codice JavaScript/CSS
- **Tailwind CSS** - Framework CSS
- **PostCSS** - Processore CSS

## 📝 Convenzioni di Codice

- Segui PSR-12 per il codice PHP
- Utilizza Laravel Pint per la formattazione
- Segui le convenzioni di naming di Laravel
- Utilizza type hints e return types

## 🔒 Sicurezza

- Implementa sempre la validazione dei dati
- Utilizza CSRF protection
- Implementa rate limiting
- Segui le best practices di Laravel per la sicurezza

## 📚 Documentazione

Per ulteriori informazioni, consulta:

- [Documentazione Laravel](https://laravel.com/docs)
- [Documentazione Filament](https://filamentphp.com/docs)
- [Documentazione Tailwind CSS](https://tailwindcss.com/docs)
- [Documentazione Alpine.js](https://alpinejs.dev/docs)

## 📄 Licenza

Questo progetto è open-source e disponibile sotto la licenza MIT.
