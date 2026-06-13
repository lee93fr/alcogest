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
            fastcgi_param QUERY_STRING       $query_string;
            fastcgi_param REQUEST_METHOD     $request_method;
            fastcgi_param CONTENT_TYPE       $content_type;
            fastcgi_param CONTENT_LENGTH     $content_length;
            fastcgi_param SCRIPT_NAME        $fastcgi_script_name;
            fastcgi_param REQUEST_URI        $request_uri;
            fastcgi_param DOCUMENT_ROOT      $document_root;
            fastcgi_param SERVER_PROTOCOL    $server_protocol;
            fastcgi_param REQUEST_SCHEME     $scheme;
            fastcgi_param HTTPS              $https if_not_empty;
            fastcgi_param REMOTE_ADDR        $remote_addr;
            fastcgi_param REMOTE_PORT        $remote_port;
            fastcgi_param SERVER_ADDR        $server_addr;
            fastcgi_param SERVER_PORT        $server_port;
            fastcgi_param SERVER_NAME        $server_name;
            fastcgi_param REDIRECT_STATUS    200;
        }

        location ~ /\.(?!well-known).* {
            deny all;
        }
    }
}
EOF

# Config php-fpm inline (évite la dépendance au chemin /nix/store/...)
cat > /tmp/php-fpm.conf << 'FPMEOF'
[global]
error_log = /proc/self/fd/2
daemonize = no

[www]
listen = 127.0.0.1:9000
pm = dynamic
pm.max_children = 10
pm.start_servers = 2
pm.min_spare_servers = 1
pm.max_spare_servers = 5
clear_env = no
FPMEOF

# Démarrer php-fpm en arrière-plan
php-fpm -y /tmp/php-fpm.conf &

# Démarrer nginx au premier plan
exec nginx -c /tmp/nginx.conf -g "daemon off;"
