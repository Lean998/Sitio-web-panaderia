FROM php:8.2-cli

# Instalar dependencias necesarias de PHP y sistema
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libpq-dev \
    libzip-dev \
    zip \
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copiar solo los archivos de Node primero para aprovechar cache
COPY package*.json ./
RUN npm install

# Copiar el resto del proyecto
COPY . .

# Generar los assets de Vite
RUN npm run build

# Instalar dependencias PHP
RUN composer install --no-dev --optimize-autoloader

EXPOSE 10000

# Ejecutar migraciones y levantar el servidor
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000
