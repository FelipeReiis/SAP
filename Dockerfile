# Usa a imagem oficial do PHP 8.3 FPM
FROM php:8.3-fpm

# Define o diretório de trabalho no container
WORKDIR /var/www

# Instala dependências de sistema essenciais
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Instala as extensões core do PHP para Laravel e os drivers do PostgreSQL (pdo_pgsql, pgsql)
RUN docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd

# Instala a extensão do Redis via PECL e habilita no PHP
RUN pecl install redis && docker-php-ext-enable redis

# Copia o Composer (gerenciador de pacotes do PHP) da última imagem oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Cria um usuário de sistema para rodar os comandos do Composer e Artisan 
# (Substitua '1000' pelo seu ID de usuário local se necessário)
RUN useradd -G www-data,root -u 1000 -d /home/laravel laravel \
    && mkdir -p /home/laravel/.composer \
    && chown -R laravel:laravel /home/laravel

# Copia os arquivos do projeto para o container
COPY . .

# Ajusta as permissões críticas que o Laravel exige
RUN mkdir -p /var/www/storage /var/www/bootstrap/cache \
    && chown -R laravel:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Altera para o usuário criado para maior segurança
USER laravel

# Expõe a porta 9000 e inicia o PHP-FPM
EXPOSE 9000
CMD ["php-fpm"]