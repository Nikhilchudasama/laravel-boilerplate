# Laravel Boilerplate

A modern, production-ready Laravel boilerplate with Vue 3, TypeScript, Inertia.js, and comprehensive features for building scalable web applications.

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?logo=laravel)
![Vue](https://img.shields.io/badge/Vue-3.x-4FC08D?logo=vue.js)
![TypeScript](https://img.shields.io/badge/TypeScript-5.x-3178C6?logo=typescript)
![Tests](https://img.shields.io/badge/Tests-148%20passing-success)

## ✨ Features

### Core Stack
- **Laravel 12** - Latest PHP framework
- **Vue 3** + **TypeScript** - Modern frontend
- **Inertia.js** - SPA without API complexity
- **Tailwind CSS** - Utility-first styling
- **Vite** - Lightning-fast build tool

### Architecture
- **Domain-Driven Design (DDD)** - Organized codebase
- **Spatie Packages** - Permissions, Media Library, Data, Query Builder, Backup, Activity Log
- **Transaction Support** - Database integrity with BaseQueries

### Authentication & Authorization
- Multi-panel architecture (Admin + User dashboards)
- Role-based permissions (Spatie Permission)
- **Multi-Factor Authentication (MFA)** - With SVG QR code generation
- User impersonation
- Password expiration
- Smart redirection logic

### Admin Panel
- Dynamic menu based on permissions
- User management (CRUD, search, export)
- **Bulk Actions** - Multi-select deactivation and deletion
- **Excel Export** - Professional data export via Laravel Excel
- Role management with permissions
- Profile management with avatar uploads
- Activity logging
- DataTables with search/pagination

### Frontend Features
- User registration & login
- User dashboard
- Profile management
- Security settings
- Toast notifications
- Dark mode support

### Developer Experience
- **Static Analysis** - Larastan/PHPStan (Level 5+)
- **Code Quality** - Rector, Pint (Strict styling)
- **Zero-Warning Linting** - ESLint + Vue Plugin
- **Type Safety** - 100% TypeScript + PHP type declarations
- **Tests** - 148 comprehensive tests (Pest + Vitest)

### Production Ready
- Security headers middleware
- Rate limiting (login, register, password reset)
- Health check endpoint
- Custom maintenance mode page
- Branded email templates
- Sitemap generator
- Demo data seeders

## 📋 Requirements

- PHP 8.3+
- Composer
- Node.js 18+ & NPM
- MySQL 8.0+ / PostgreSQL 13+ / SQLite
- Redis (optional, for caching/queues)

## 🚀 Installation

### 1. Clone the repository
```bash
git clone <repository-url>
cd testnew
```

### 2. Install dependencies
```bash
composer install
npm install
```

### 3. Environment setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure database
Edit `.env` and set your database credentials:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Run migrations and seed
```bash
php artisan migrate
php artisan db:seed
```

### 6. Build assets
```bash
npm run build
# or for development
npm run dev
```

### 7. Start the server
```bash
php artisan serve
```

Visit `http://localhost:8000`

## 🧪 Testing

We carry a rigorous testing suite covering both backend and frontend.

**Run the umbrella verification (Unit + Types + Linting):**
```bash
composer run test
```

**Run specific test suites:**
```bash
composer run test:unit      # Pest Feature/Unit tests
npm run test                # Vitest Component tests
```

## 🔧 Development

### Code Quality Pipeline

Our local pipeline ensures code quality before any push:

**Lint & Format (Automatic):**
```bash
composer run lint           # PHP (Pint/Rector) + Frontend (ESLint)
```

**Static Analysis & Type Checking:**
```bash
composer run test:types     # PHP (Larastan) + Vue (vue-tsc)
```

**VS Code Integration:**
The project includes a `.vscode/settings.json` that automatically runs ESLint's `--fix` command on save.

### generate Demo Data
```bash
php artisan db:seed --class=DemoDataSeeder
```

Login with:
- Email: `admin@demo.com`
- Password: `password`

### Generate Sitemap
```bash
php artisan sitemap:generate
```

## 📁 Project Structure

```
app/
├── Domain/              # Domain-driven design structure
│   ├── Access/         # Roles & Permissions
│   ├── Activity/       # Activity logging
│   ├── Auth/           # Authentication
│   └── Users/          # User management
├── Http/
│   ├── Controllers/
│   └── Middleware/
└── Support/            # Shared utilities

resources/
├── js/
│   ├── Components/     # Reusable Vue components
│   ├── Layouts/        # Layout components
│   ├── Services/       # Business logic & API (date, etc)
│   └── Pages/          # Inertia pages
│       ├── Admin/      # Admin panel pages
│       └── Frontend/   # User-facing pages
└── views/
    ├── emails/         # Email templates
    └── errors/         # Error pages

tests/
├── Feature/            # Laravel Feature tests
├── Unit/               # Laravel Unit tests
└── Vue/                # Vitest Component tests
```

## 🔐 Default Credentials

After running seeders:

**Admin:**
- Email: `admin@example.com`
- Password: `password`

**Demo Admin (DemoDataSeeder):**
- Email: `admin@demo.com`
- Password: `password`

## 📚 Documentation

- [Deployment Guide](docs/DEPLOYMENT.md)
- [Development Guide](docs/DEVELOPMENT.md)
- [Contributing Guidelines](CONTRIBUTING.md)

## 🛡️ Security

- Security headers (CSP, HSTS, XSS protection)
- Rate limiting on sensitive endpoints
- CSRF protection
- SQL injection prevention
- XSS protection (v-html usage is audited and suppressed only where safe)

## 📝 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## 🤝 Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## 📞 Support

For issues and questions, please use the [issue tracker](https://github.com/your-repo/issues).
