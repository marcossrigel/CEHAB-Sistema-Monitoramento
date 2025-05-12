FROM php:8.2-apache

# Instala a extensão mysqli
RUN docker-php-ext-install mysqli

# Ativa o módulo de reescrita do Apache (opcional)
RUN a2enmod rewrite

# Copia os arquivos para dentro do container
COPY . /var/www/html/

# Ajusta permissões
RUN chown -R www-data:www-data /var/www/html

# Expõe a porta padrão do Apache
EXPOSE 80
