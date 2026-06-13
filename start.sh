#!/bin/sh
set -e

cd /app

# Répertoires nécessaires
mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache

# Permissions pour php-fpm
chown -R www-data:www-data /app/storage /app/bootstrap/cache 2>/dev/null || true
chmod -R 775 storage bootstrap/cache

# Symlink storage public
php artisan storage:link --force

# Migrations
php artisan migrate --force

# Caches Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Config nginx (inline pour compatibilité Nix — pas d'include mime.types)
cat > /tmp/nginx.conf << 'EOF'
events {}

http {
    types {
        text/html                             html htm;
        text/css                              css;
        application/javascript                js;
        application/json                      json;
        image/png                             png;
        image/jpeg                            jpeg jpg;
        image/gif                             gif;
        image/webp                            webp;
        image/svg+xml                         svg;
        image/x-icon                          ico;
        font/woff                             woff;
        font/woff2                            woff2;
        application/octet-stream              bin;
    }

    server {
        listen 8000;
        root /app/public;
        index index.php;

        location / {
            try_files $uri $uri/ /index.php?$query_string;
        }

        location ~ \.php$ {
            fastcgi_pass 127.0.0.1:9000;
            fastcgi_index index.php;
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
            fastcgi_param DOCUMENT_ROOT $document_root;
            include /etc/nginx/fastcgi_params;
        }

        location ~ /\.(?!well-known).* {
            deny all;
        }
    }
}
EOF

# Démarrer php-fpm en arrière-plan (--nodaemonize car géré par le &)
php-fpm --nodaemonize &

# Démarrer nginx au premier plan
exec nginx -c /tmp/nginx.conf -g "daemon off;"
