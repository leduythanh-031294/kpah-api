Sử dụng image PHP chính thức (version 8.2) kết hợp với FPM

FROM php:8.2-fpm-alpine

Cài đặt các gói cần thiết:

- Caddy: Web server nhẹ

- curl: Dùng để tải Caddy

- php-session: Để lưu trữ Session

RUN apk update && 

apk add --no-cache curl caddy && 

docker-php-ext-install session

BỔ SUNG: Tạo cấu hình Caddyfile trực tiếp trong quá trình build

RUN echo "http://:8080" >> /etc/caddy/Caddyfile && 

echo "root * /srv" >> /etc/caddy/Caddyfile && 

echo "tls internal" >> /etc/caddy/Caddyfile && 

echo "php_fastcgi unix//var/run/php-fpm.sock" >> /etc/caddy/Caddyfile && 

echo "file_server" >> /etc/caddy/Caddyfile

Sao chép các file vào thư mục phục vụ web của Caddy (/srv)

COPY . /srv

Khai báo cổng 8080 cho Docker

EXPOSE 8080

Đảm bảo PHP-FPM khởi động và Caddy chạy ở foreground

Caddy sẽ sử dụng cấu hình Caddyfile đã được tạo

CMD php-fpm -D && caddy run --config /etc/caddy/Caddyfile --adapter caddyfile
