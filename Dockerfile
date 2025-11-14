FROM php:8.2-fpm-alpine
RUN apk update && apk add caddy
COPY . /srv
RUN rm /etc/caddy/Caddyfile
COPY Caddyfile /etc/caddy/Caddyfile
EXPOSE 80
CMD php-fpm && caddy run --config /etc/caddy/Caddyfile
