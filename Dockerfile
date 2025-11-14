# Use the official PHP-FPM image (version 8.2) on Alpine
FROM php:8.2-fpm-alpine

# Install necessary packages: Caddy and PHP session extension
RUN apk update && \
    apk add --no-cache curl caddy && \
    docker-php-ext-install session

# FINAL CADDYFILE CONFIGURATION: Creates the Caddyfile with reverse_proxy for correct Host Headers.
# FIX: Removed the 'root' subdirective from inside 'reverse_proxy' to solve Caddy parse error.
RUN echo "http://:8080" > /etc/caddy/Caddyfile && \
    echo "tls internal" >> /etc/caddy/Caddyfile && \
    echo "root * /srv" >> /etc/caddy/Caddyfile && \
    echo "reverse_proxy /* unix//var/run/php-fpm.sock {" >> /etc/caddy/Caddyfile && \
    echo "    header_up Host {host}" >> /etc/caddy/Caddyfile && \
    echo "    header_up X-Forwarded-For {remote}" >> /etc/caddy/Caddyfile && \
    echo "}" >> /etc/caddy/Caddyfile && \
    echo "file_server" >> /etc/caddy/Caddyfile

# Copy all project files into the Caddy web root
COPY . /srv

# Expose port 8080 for Render to detect
EXPOSE 8080

# Start PHP-FPM in the background and run Caddy in the foreground
CMD php-fpm -D && caddy run --config /etc/caddy/Caddyfile --adapter caddyfile
