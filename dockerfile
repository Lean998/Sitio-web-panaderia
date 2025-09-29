# Base PHP
FROM php:8.2-cli

# Instalar dependencias de sistema y PHP
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

# Definir directorio de trabajo
WORKDIR /app

# Copiar package.json y package-lock.json primero para aprovechar cache
COPY package*.json ./

# Instalar dependencias JS
RUN npm install

# Copiar resto del proyecto
COPY . .

# Generar los assets con Vite
RUN npm run build

# Instalar dependencias PHP
RUN composer install --no-dev --optimize-autoloader

# Crear carpeta de storage y permisos
RUN mkdir -p storage/framework/{sessions,views,cache} storage/logs \
    && chown -R www-data:www-data storage bootstrap/cache

# Exponer puerto
EXPOSE 10000

# Comando final: correr migraciones y levantar servidor
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=10000
