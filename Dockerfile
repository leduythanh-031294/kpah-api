# Use the official PHP-FPM image (version 8.2) on Alpine
FROM php:8.2-fpm-alpine

# Install necessary packages: Caddy and PHP session extension
RUN apk update && \
    apk add --no-cache curl caddy && \
    docker-php-ext-install session

# **SỬA LỖI 502:** Chuyển Caddy kết nối đến cổng TCP 9000
# Thường PHP-FPM trên Alpine chạy ở 9000.
RUN echo "http://:8080" > /etc/caddy/Caddyfile && \
    echo "tls internal" >> /etc/caddy/Caddyfile && \
    echo "root * /srv" >> /etc/caddy/Caddyfile && \
    echo "reverse_proxy /* 127.0.0.1:9000 {" >> /etc/caddy/Caddyfile && \
    echo "    header_up Host {host}" >> /etc/caddy/Caddyfile && \
    echo "    header_up X-Forwarded-For {remote}" >> /etc/caddy/Caddyfile && \
    echo "}" >> /etc/caddy/Caddyfile && \
    echo "file_server" >> /etc/caddy/Caddyfile

# Copy all project files into the Caddy web root
COPY . /srv

# Expose port 8080 cho Caddy và 9000 cho FPM
EXPOSE 8080
EXPOSE 9000

# Start PHP-FPM ở 9000 và chạy Caddy
# FPM mặc định lắng nghe ở cổng 9000
CMD php-fpm -D && caddy run --config /etc/caddy/Caddyfile --adapter caddyfile
