# Development Guide

This guide covers local development setup and best practices for the Laravel Boilerplate.

## Initial Setup

### 1. Prerequisites
Ensure you have installed:
- PHP 8.3+
- Composer
- Node.js 18+ & NPM
- MySQL/PostgreSQL/SQLite
- Redis (optional but recommended)

### 2. Clone and Install

```bash
git clone <repository-url>
cd testnew
composer install
npm install
```

### 3. Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

Configure your `.env`:
```env
APP_NAME="Laravel Boilerplate"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://testnew.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=testnew
DB_USERNAME=root
DB_PASSWORD=

CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

### 4. Database Setup

```bash
php artisan migrate
php artisan db:seed
```

For demo data:
```bash
php artisan db:seed --class=DemoDataSeeder
```

### 5. Start Development Servers

**Terminal 1 - Laravel:**
```bash
php artisan serve
```

**Terminal 2 - Vite:**
```bash
npm run dev
```

Visit `http://localhost:8000`

## Development Workflow

### Running Tests

**All tests:**
```bash
php artisan test
```

**Specific test file:**
```bash
php artisan test tests/Feature/UserManagementTest.php
```

**Specific test:**
```bash
php artisan test --filter test_admin_can_create_user
```

**With coverage:**
```bash
php artisan test --coverage
```

### Code Quality

**Format PHP code:**
```bash
composer pint
```

**Run static analysis:**
```bash
composer phpstan
```

**Run Rector (dry run):**
```bash
composer rectord
```

**Apply Rector changes:**
```bash
composer rector
```

**Type check TypeScript:**
```bash
npm run type-check
```

### Database Management

**Fresh migration:**
```bash
php artisan migrate:fresh --seed
```

**Rollback:**
```bash
php artisan migrate:rollback
```

**Create migration:**
```bash
php artisan make:migration create_something_table
```

**Create seeder:**
```bash
php artisan make:seeder SomethingSeeder
```

### Queue Management

**Run queue worker:**
```bash
php artisan queue:work
```

**Process one job:**
```bash
php artisan queue:work --once
```

**Clear failed jobs:**
```bash
php artisan queue:flush
```

## Project Structure

### Backend (Laravel)

```
app/
├── Console/
│   └── Commands/           # Artisan commands
├── Domain/                 # Domain-driven design
│   ├── Access/            # Roles & Permissions
│   ├── Activity/          # Activity logging
│   ├── Auth/              # Authentication
│   └── Users/             # User management
│       ├── Actions/       # Business logic
│       ├── Data/          # DTOs
│       ├── Http/          # Controllers, Requests
│       ├── Models/        # Eloquent models
│       └── UserQueries.php # Query builder
├── Http/
│   ├── Controllers/       # Base controllers
│   └── Middleware/        # HTTP middleware
└── Support/               # Shared utilities
    ├── BaseAction.php
    ├── BaseQueries.php
    └── Traits/
```

### Frontend (Vue 3 + TypeScript)

```
resources/js/
├── Components/
│   ├── Admin/            # Admin-specific components
│   ├── Form/             # Form components
│   ├── Frontend/         # User-facing components
│   └── UI/               # Reusable UI components
├── Layouts/
│   ├── AdminLayout.vue   # Admin panel layout
│   └── FrontendLayout.vue # User-facing layout
├── Pages/
│   ├── Admin/            # Admin panel pages
│   └── Frontend/         # User-facing pages
├── Types/                # TypeScript types
└── app.ts                # Application entry
```

## Creating New Features

### 1. Create a New Domain

```bash
mkdir -p app/Domain/YourDomain/{Actions,Data,Http/Controllers,Models}
```

### 2. Create Model

```bash
php artisan make:model Domain/YourDomain/Models/YourModel -m
```

### 3. Create Data Transfer Object

```php
// app/Domain/YourDomain/Data/YourData.php
namespace App\Domain\YourDomain\Data;

use Spatie\LaravelData\Data;

class YourData extends Data
{
    public function __construct(
        public string $name,
        public ?string $description = null,
    ) {}
}
```

### 4. Create Action

```php
// app/Domain/YourDomain/Actions/CreateYourAction.php
namespace App\Domain\YourDomain\Actions;

use App\Support\BaseAction;

class CreateYourAction extends BaseAction
{
    public function execute(YourData $data): YourModel
    {
        return $this->transaction(function () use ($data) {
            return YourModel::create($data->toArray());
        });
    }
}
```

### 5. Create Controller

```bash
php artisan make:controller Domain/YourDomain/Http/Controllers/YourController
```

### 6. Create Vue Page

```vue
<!-- resources/js/Pages/Admin/Your/Index.vue -->
<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineOptions({ layout: AdminLayout });
</script>

<template>
  <Head title="Your Feature" />
  
  <div>
    <!-- Your content -->
  </div>
</template>
```

### 7. Add Routes

```php
// routes/admin.php
Route::resource('your-resource', YourController::class);
```

### 8. Write Tests

```bash
php artisan make:test YourFeatureTest
```

## Debugging

### Laravel Debugbar

Install for development:
```bash
composer require barryvdh/laravel-debugbar --dev
```

### Vue DevTools

Install browser extension:
- [Chrome](https://chrome.google.com/webstore/detail/vuejs-devtools/nhdogjmejiglipccpnnnanhbledajbpd)
- [Firefox](https://addons.mozilla.org/en-US/firefox/addon/vue-js-devtools/)

### Logging

```php
// Log to storage/logs/laravel.log
\Log::info('Debug message', ['data' => $data]);
\Log::error('Error message', ['exception' => $e]);
```

### Tinker

```bash
php artisan tinker
```

```php
>>> User::count()
>>> User::factory()->create()
```

## Common Tasks

### Clear All Caches

```bash
php artisan optimize:clear
```

### Generate IDE Helper

```bash
composer require --dev barryvdh/laravel-ide-helper
php artisan ide-helper:generate
php artisan ide-helper:models
php artisan ide-helper:meta
```

### Update Dependencies

```bash
composer update
npm update
```

### Generate Sitemap

```bash
php artisan sitemap:generate
```

## Troubleshooting

### Permission Issues

```bash
chmod -R 775 storage bootstrap/cache
```

### Node Modules Issues

```bash
rm -rf node_modules package-lock.json
npm install
```

### Composer Issues

```bash
rm -rf vendor composer.lock
composer install
```

### Database Connection Issues

Check `.env` database credentials and ensure MySQL/PostgreSQL is running.

### Vite Not Hot Reloading

1. Check `vite.config.ts` server configuration
2. Ensure `APP_URL` in `.env` matches your local URL
3. Clear browser cache

## Best Practices

### PHP/Laravel
- Follow PSR-12 coding standards
- Use type declarations
- Write descriptive variable names
- Keep controllers thin, use Actions
- Use DTOs for data transfer
- Write tests for all features
- Use transactions for data integrity

### Vue/TypeScript
- Use Composition API
- Define prop types
- Use TypeScript interfaces
- Keep components small and focused
- Use composables for reusable logic
- Follow Vue style guide

### Git
- Write clear commit messages
- Create feature branches
- Keep commits atomic
- Squash before merging

## Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Vue 3 Documentation](https://vuejs.org)
- [Inertia.js Documentation](https://inertiajs.com)
- [TypeScript Documentation](https://www.typescriptlang.org/docs)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
