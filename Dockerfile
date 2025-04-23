FROM php:8.3-fpm

# Argumentos
ARG user=www-data
ARG uid=1000

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    nodejs \
    npm

# Limpiar cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensiones PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Obtener Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Crear directorio del sistema
RUN mkdir -p /var/www

# Configurar permisos para el usuario www-data existente
RUN usermod -u $uid www-data && \
    groupmod -g $uid www-data && \
    mkdir -p /home/www-data && \
    chown -R www-data:www-data /var/www /home/www-data

# Establecer directorio de trabajo
WORKDIR /var/www

# Copiar archivos de la aplicación
COPY . /var/www

# Ajustar permisos
RUN chown -R www-data:www-data /var/www

# Cambiar usuario actual a www-data
USER www-data

# Exponer puerto 9000
EXPOSE 9000

CMD ["php-fpm"] 