FROM php:8.1-apache

# Copia os arquivos para dentro do container
COPY . /var/www/html/

# Ativa o módulo de reescrita do Apache (se precisar de URL amigável)
RUN a2enmod rewrite

# Expõe a porta padrão do Apache
EXPOSE 80

FROM php:8.2-apache

# Instala a extensão mysqli
RUN docker-php-ext-install mysqli

# Copia os arquivos do projeto
COPY . /var/www/html/

# Dá permissão correta
RUN chown -R www-data:www-data /var/www/html
