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
