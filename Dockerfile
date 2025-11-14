# Use the official image that includes Caddy and PHP-FPM
FROM caddy:2-php

# Copy all project files into the Caddy web root
COPY . /srv

# The Caddyfile handles the request routing and PHP processing.
# The server is started by default by the base image.
# Render will handle the port configuration automatically.
