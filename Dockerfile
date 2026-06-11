# Stage 1: Build front-end assets
FROM node:20-alpine AS node-builder
WORKDIR /app
COPY package*.json ./
RUN if [ -f package-lock.json ]; then npm ci; else npm install; fi
COPY . .
RUN npm run build

# Stage 2: Production PHP-FPM & Nginx
FROM serversideup/php:8.3-fpm-nginx

# Set the document root for Nginx in serversideup image
ENV WEB_DOCUMENT_ROOT=/var/www/html/public

# Copy application files with appropriate ownership
COPY --chown=www-data:www-data . /var/www/html

# Copy compiled assets from node-builder stage
COPY --chown=www-data:www-data --from=node-builder /app/public/build /var/www/html/public/build

# Switch to www-data user to run composer install safely
USER www-data

# Install composer dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev
