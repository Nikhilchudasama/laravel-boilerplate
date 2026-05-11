# CI/CD Pipeline Setup Guide

## Overview

This project uses **4 GitHub Actions workflow files** to enforce code quality and automate deployments.

```
.github/workflows/
├── 1-ci-tests.yml              ← Run on every PR / push (blocks merge on failure)
├── 2-cd-deploy.yml             ← Deploy to staging/production after merge
├── 3-code-quality-autofix.yml  ← Auto-fix lint on PR approval
└── 4-security-scan.yml         ← Dependency audit + CodeQL on PR + weekly
```

---

## Pipeline Flow

```
Developer opens PR
       │
       ▼
┌──────────────────────────────────────────────────────┐
│  Workflow 1 — CI Tests  (runs in parallel)           │
│                                                      │
│  php-lint ──► php-tests          js-lint ──► js-tests│
│  php-types                       js-types            │
│  php-type-coverage               build               │
│                │                      │              │
│                └──────────┬───────────┘              │
│                           ▼                          │
│                    ✅ ci-gate                         │
└──────────────────────────────────────────────────────┘
       │
       │  (Branch protection requires ci-gate to pass)
       │
       ▼
Reviewer approves PR
       │
       ▼
┌──────────────────────────────────────────────────────┐
│  Workflow 3 — Auto-fix                               │
│  Pint + Rector + ESLint auto-fixes applied           │
│  → Opens a "fixer PR" if any changes were needed     │
└──────────────────────────────────────────────────────┘
       │
       ▼
PR merged into main / staging
       │
       ▼
┌──────────────────────────────────────────────────────┐
│  Workflow 2 — CD Deploy                              │
│  1. Re-runs all tests on merged code                 │
│  2. Builds Vite assets                               │
│  3a. Deploys to STAGING  (branch: staging)           │
│  3b. Deploys to PRODUCTION (branch: main)            │
│      • Enables maintenance mode before deploy        │
│      • Disables maintenance mode after (even on fail)│
└──────────────────────────────────────────────────────┘
```

---

## Required GitHub Secrets

Go to **Settings → Secrets and variables → Actions** and add:

### Staging Deployment

| Secret Name     | Description                                     |
|-----------------|-------------------------------------------------|
| `SSH_PRIVATE_KEY` | Private SSH key (copy from `~/.ssh/git_contabo`) |
| `SSH_HOST`      | Staging server IP or hostname                   |
| `SSH_USER`      | SSH username (e.g., `deploy`, `ubuntu`, `root`) |
| `DEPLOY_PATH`   | Absolute path on server (e.g., `/var/www/staging`) |
| `STAGING_URL`   | Full URL (e.g., `https://staging.yourdomain.com`) |

### Production Deployment

| Secret Name          | Description                                      |
|----------------------|--------------------------------------------------|
| `SSH_PRIVATE_KEY_PROD` | Private SSH key for production server          |
| `SSH_HOST_PROD`      | Production server IP or hostname                 |
| `SSH_USER_PROD`      | SSH username on production                       |
| `DEPLOY_PATH_PROD`   | Absolute path (e.g., `/var/www/production`)      |
| `PRODUCTION_URL`     | Full URL (e.g., `https://yourdomain.com`)        |

### Adding Your SSH Key

Your private key is at `~/.ssh/git_contabo`. Copy its contents:

```bash
cat ~/.ssh/git_contabo
```

Paste the **entire output** (including `-----BEGIN ... KEY-----` lines) as the secret value.

On your server, add the **public key** to `~/.ssh/authorized_keys`:

```bash
cat ~/.ssh/git_contabo.pub >> ~/.ssh/authorized_keys
```

---

## Branch Protection Rules (REQUIRED)

These rules **block merging** if CI fails. Set them at:  
**Settings → Branches → Add rule → branch name: `main`**

### Rules to enable:

- ✅ **Require a pull request before merging**
  - ✅ Require approvals: `1`
  - ✅ Dismiss stale pull request approvals when new commits are pushed
- ✅ **Require status checks to pass before merging**
  - Search and add: `✅ CI Gate (all checks passed)`
  - ✅ Require branches to be up to date before merging
- ✅ **Do not allow bypassing the above settings** (even for admins)

Repeat for the `staging` branch.

---

## What Each Workflow Does

### `1-ci-tests.yml` — Runs on every PR
| Job | Tool | Purpose |
|-----|------|---------|
| `php-lint` | Pint + Rector | Check PHP coding style |
| `php-types` | PHPStan/Larastan | Static type analysis |
| `php-tests` | Pest | Unit + Feature tests |
| `php-type-coverage` | Pest | Enforce ≥ 70% type coverage |
| `js-lint` | ESLint | Check JS/TS/Vue style |
| `js-types` | vue-tsc | TypeScript type checking |
| `js-tests` | Vitest | Vue component unit tests |
| `build` | Vite | Production asset build |
| `ci-gate` | — | All-or-nothing gate check |

### `2-cd-deploy.yml` — Runs on merge to `main` / `staging`
1. Re-runs the full test suite on the merged commit
2. Builds production Vite assets
3. Deploys via SSH using rsync + artisan commands
4. Wraps production in maintenance mode (safe zero-downtime-ish deploy)

### `3-code-quality-autofix.yml` — Runs on PR approval
- Applies `pint --parallel` (PHP style fixes)
- Applies `rector process` (code upgrades)
- Applies `npm run lint` (ESLint fixes)
- Creates a "fixer PR" so changes are visible before merging

### `4-security-scan.yml` — Runs on PR + every Monday
- `composer audit` — check PHP packages for CVEs
- `npm audit` — check Node packages for CVEs
- CodeQL analysis (requires GitHub Advanced Security)

---

## Local Commands (mirrors CI)

```bash
# Run all tests (same as CI)
composer test

# Individual checks
./vendor/bin/pint --parallel --test     # PHP lint
./vendor/bin/rector --dry-run           # Rector dry-run
./vendor/bin/phpstan analyse            # Static analysis
./vendor/bin/pest --parallel            # PHP tests
npm run test:lint                       # ESLint
npm run test:types                      # TypeScript check
npm run test                            # Vitest
npm run build                           # Production build

# Auto-fix (same as what CI does on approval)
./vendor/bin/pint --parallel
./vendor/bin/rector process
npm run lint
```

---

## Server Requirements

Your deploy server needs:

- PHP 8.2+ with extensions: `mbstring`, `xml`, `curl`, `pdo`, `sqlite3`, `gd`, `zip`, `bcmath`, `intl`
- Composer 2.x
- Node.js 20+ and npm
- Git
- The project already cloned and `.env` configured
- Queue worker running (e.g., Supervisor)

---

## Troubleshooting

| Issue | Fix |
|-------|-----|
| `Permission denied (publickey)` | Ensure public key is in server's `~/.ssh/authorized_keys` |
| `Host key verification failed` | Add the server's fingerprint to known_hosts first |
| `Migrate failed` | Check `APP_KEY` is set in server `.env` |
| Tests fail only in CI | Ensure `.env.example` has all required testing env vars |
| ESLint max-warnings exceeded | Run `npm run lint` locally and fix all warnings |
