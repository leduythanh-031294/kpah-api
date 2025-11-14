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

# **BỔ SUNG:** Khai báo cổng 8080
EXPOSE 8080

# Đảm bảo PHP-FPM khởi động và Caddy chạy ở foreground
# Caddy sẽ sử dụng cấu hình Caddyfile đã được sao chép
CMD php-fpm -D && caddy run --config /etc/caddy/Caddyfile --adapter caddyfile

### ➡️ Hành động tiếp theo (Lần thử cuối cùng với cấu hình này)

1.  **Sửa `Caddyfile`:** Cập nhật nội dung file `Caddyfile` trên GitHub (Mục 1).
2.  **Sửa `Dockerfile`:** Cập nhật nội dung file `Dockerfile` trên GitHub (Mục 2).
3.  **Commit changes:** Nhấn **"Commit changes"** sau khi sửa cả hai file.
4.  **Triển khai lại:** Quay lại Render.com và nhấn **"Manual Deploy"** -> **"Deploy latest commit"**.

Việc thiết lập cổng cố định `8080` này sẽ loại bỏ hoàn toàn sự phụ thuộc vào biến môi trường `$PORT` và Render sẽ có thể tìm thấy cổng mở. Tôi hy vọng lần này sẽ thành công!
