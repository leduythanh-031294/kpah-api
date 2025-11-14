# Use the official PHP-FPM image (version 8.2) on Alpine
FROM php:8.2-fpm-alpine

# Install necessary packages: Caddy và PHP session extension
RUN apk update && \
    apk add --no-cache curl caddy && \
    docker-php-ext-install session

# **BỔ SUNG VÀ SỬA LỖI 502:** Tạo file cấu hình FPM để lắng nghe 127.0.0.1:9000
RUN echo "[www]" > /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo "listen = 127.0.0.1:9000" >> /usr/local/etc/php-fpm.d/zz-docker.conf && \
    echo "catch_workers_output = yes" >> /usr/local/etc/php-fpm.d/zz-docker.conf

# **FINAL CADDYFILE CONFIGURATION:** Caddy kết nối đến cổng 9000
RUN echo "http://:8080" > /etc/caddy/Caddyfile && \
    echo "tls internal" >> /etc/caddy/Caddyfile && \
    echo "root * /srv" >> /etc/caddy/Caddyfile && \
    echo "reverse_proxy /* 127.0.0.1:9000 {" >> /etc/caddy/Caddyfile && \
    echo "    header_up Host {host}" >> /etc/caddy/Caddyfile && \
    echo "    header_up X-Forwarded-For {remote}" >> /etc/caddy/Caddyfile && \
    echo "}" >> /etc/caddy/Caddyfile && \
    echo "file_server" >> /etc/caddy/Caddyfile

# Sao chép và đặt thư mục làm việc là /srv
COPY . /srv
WORKDIR /srv

# Expose port 8080 cho Caddy
EXPOSE 8080

# Start PHP-FPM và Caddy
CMD php-fpm -D && caddy run --config /etc/caddy/Caddyfile --adapter caddyfile
