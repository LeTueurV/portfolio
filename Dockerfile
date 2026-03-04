############################################
# 1️⃣ Frontend Build Stage (Vite)
############################################
FROM node:20-alpine AS node_builder

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY resources ./resources
COPY vite.config.js tsconfig.json* ./
RUN npm run build


############################################
# 2️⃣ PHP Stage
############################################
FROM php:8.3-cli-alpine

WORKDIR /app

############################################
# Install system + build dependencies
############################################
RUN apk add --no-cache \
    curl \
    git \
    unzip \
    zip \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    postgresql-dev \
    sqlite-dev \
    mysql-client \
    $PHPIZE_DEPS

############################################
# Install PHP extensions
############################################
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg && \
    docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    pdo_sqlite \
    gd \
    bcmath \
    mbstring \
    xml \
    zip

# Remove build deps to reduce image size
RUN apk del $PHPIZE_DEPS

############################################
# Install Composer
############################################
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

############################################
# Copy application
############################################
COPY . .

# Copy built assets
COPY --from=node_builder /app/public ./public

############################################
# Install Laravel dependencies
############################################
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

############################################
# Permissions
############################################
RUN chmod -R 775 storage bootstrap/cache

############################################
# Render uses dynamic PORT
############################################
ENV PORT=10000
EXPOSE 10000

############################################
# Start Laravel (important: use $PORT)
############################################
CMD php artisan serve --host=0.0.0.0 --port=$PORT