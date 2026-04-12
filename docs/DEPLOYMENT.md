# Deployment Guide

This guide covers deploying the Laravel Boilerplate to production environments.

## Pre-Deployment Checklist

### 1. Environment Configuration
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Generate new `APP_KEY`
- [ ] Configure database credentials
- [ ] Set up mail configuration
- [ ] Configure cache/session drivers (Redis recommended)
- [ ] Set up queue driver
- [ ] Configure backup destinations

### 2. Security
- [ ] Enable HTTPS/SSL
- [ ] Configure security headers
- [ ] Review rate limiting settings
- [ ] Set up firewall rules
- [ ] Configure CORS if needed
- [ ] Review file permissions

### 3. Performance
- [ ] Enable OPcache
- [ ] Configure Redis for caching
- [ ] Set up queue workers
- [ ] Enable asset compression
- [ ] Configure CDN (optional)

## Server Requirements

### Minimum Requirements
- PHP 8.3+
- MySQL 8.0+ / PostgreSQL 13+
- Redis 6.0+
- Nginx / Apache
- Composer
- Node.js 18+ & NPM
- Supervisor (for queue workers)

### Recommended Server Specs
- 2+ CPU cores
- 4GB+ RAM
- 20GB+ SSD storage
- Ubuntu 22.04 LTS / Debian 12

## Deployment Steps

### 1. Server Setup

**Install PHP 8.3 and extensions:**
```bash
sudo apt update
sudo apt install php8.3-fpm php8.3-cli php8.3-mysql php8.3-redis \
  php8.3-mbstring php8.3-xml php8.3-curl php8.3-zip php8.3-gd \
  php8.3-intl php8.3-bcmath
```

**Install Composer:**
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

**Install Node.js:**
```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
```

**Install Redis:**
```bash
sudo apt install redis-server
sudo systemctl enable redis-server
```

**Install MySQL:**
```bash
sudo apt install mysql-server
sudo mysql_secure_installation
```

### 2. Application Deployment

**Clone repository:**
```bash
cd /var/www
git clone <repository-url> your-app
cd your-app
```

**Install dependencies:**
```bash
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

**Set up environment:**
```bash
cp .env.production.example .env
php artisan key:generate
```

**Configure `.env`:**
```env
APP_NAME="Your App"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=your_database
DB_USERNAME=your_user
DB_PASSWORD=your_secure_password

CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

**Run migrations:**
```bash
php artisan migrate --force
php artisan db:seed --force
```

**Optimize application:**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

**Set permissions:**
```bash
sudo chown -R www-data:www-data /var/www/your-app
sudo chmod -R 755 /var/www/your-app
sudo chmod -R 775 /var/www/your-app/storage
sudo chmod -R 775 /var/www/your-app/bootstrap/cache
```

### 3. Web Server Configuration

**Nginx configuration (`/etc/nginx/sites-available/your-app`):**
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/your-app/public;

    ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

**Enable site:**
```bash
sudo ln -s /etc/nginx/sites-available/your-app /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### 4. SSL Certificate (Let's Encrypt)

```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

### 5. Queue Workers (Supervisor)

**Create supervisor config (`/etc/supervisor/conf.d/your-app-worker.conf`):**
```ini
[program:your-app-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/your-app/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/your-app/storage/logs/worker.log
stopwaitsecs=3600
```

**Start workers:**
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start your-app-worker:*
```

### 6. Scheduled Tasks (Cron)

```bash
sudo crontab -e -u www-data
```

Add:
```
* * * * * cd /var/www/your-app && php artisan schedule:run >> /dev/null 2>&1
```

## Post-Deployment

### 1. Verify Installation
- [ ] Visit your domain
- [ ] Test login functionality
- [ ] Check admin panel
- [ ] Verify email sending
- [ ] Test file uploads
- [ ] Check queue processing

### 2. Monitoring
- Set up application monitoring (e.g., Laravel Pulse)
- Configure error tracking (e.g., Sentry, Flare)
- Set up uptime monitoring
- Configure backup monitoring

### 3. Backups
```bash
# Configure automatic backups
php artisan backup:run
```

Add to cron:
```
0 2 * * * cd /var/www/your-app && php artisan backup:run >> /dev/null 2>&1
```

## Updating the Application

```bash
cd /var/www/your-app
git pull origin main
composer install --optimize-autoloader --no-dev
npm install
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
sudo supervisorctl restart your-app-worker:*
```

## Troubleshooting

### Permission Issues
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### Clear All Caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Queue Not Processing
```bash
sudo supervisorctl status
sudo supervisorctl restart your-app-worker:*
```

## Security Best Practices

1. Keep PHP and dependencies updated
2. Use strong database passwords
3. Enable firewall (UFW)
4. Regular security audits
5. Monitor logs regularly
6. Keep backups offsite
7. Use environment-specific credentials
8. Enable 2FA for admin accounts

## Performance Optimization

1. Enable OPcache in `php.ini`
2. Use Redis for cache and sessions
3. Enable HTTP/2
4. Use CDN for static assets
5. Optimize database queries
6. Monitor with Laravel Pulse
7. Use queue workers for heavy tasks

## Support

For deployment issues, check:
- Laravel logs: `storage/logs/laravel.log`
- Nginx logs: `/var/log/nginx/error.log`
- PHP-FPM logs: `/var/log/php8.3-fpm.log`
