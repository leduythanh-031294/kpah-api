# Sử dụng image PHP chính thức (version 8.2) kết hợp với Apache
FROM php:8.2-apache

# Sao chép tất cả các file PHP vào thư mục gốc của web server Apache.
COPY . /var/www/html/

# Apache sẽ tự động lắng nghe cổng 80 (cổng HTTP mặc định).
EXPOSE 80

# Apache sẽ tự động khởi động và phục vụ các file PHP.
# Không cần lệnh CMD phức tạp.