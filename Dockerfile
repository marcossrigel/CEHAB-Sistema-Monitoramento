FROM php:8.1-apache

# Copia os arquivos para dentro do container
COPY . /var/www/html/

# Ativa o módulo de reescrita do Apache (se precisar de URL amigável)
RUN a2enmod rewrite

# Expõe a porta padrão do Apache
EXPOSE 80
