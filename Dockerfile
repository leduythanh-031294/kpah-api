# Sử dụng image PHP chính thức (version 8.2) kết hợp với FPM
FROM php:8.2-fpm-alpine

# Cài đặt các gói cần thiết:
# - Caddy: Web server nhẹ
# - curl: Dùng để tải Caddy
# - php-session: Để lưu trữ Session (cần thiết cho API của bạn)
RUN apk update && \
    apk add --no-cache curl caddy && \
    docker-php-ext-install session

# Sao chép các file vào thư mục phục vụ web của Caddy (/srv)
COPY . /srv

# Sao chép file cấu hình Caddyfile vào nơi mặc định
COPY Caddyfile /etc/caddy/Caddyfile

# Đảm bảo PHP-FPM khởi động và Caddy chạy ở foreground
# Caddy sẽ tự động sử dụng cấu hình Caddyfile đã được sao chép
CMD php-fpm -D && caddy run --config /etc/caddy/Caddyfile --adapter caddyfile
