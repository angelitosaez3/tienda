# Usar la imagen oficial de PHP con Apache
FROM php:7.4-apache

# Instalar extensiones necesarias para MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copiar el contenido del directorio actual a /var/www/html
COPY . /var/www/html

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Establecer permisos adecuados
RUN chown -R www-data:www-data /var/www/html

# Exponer el puerto 80
EXPOSE 80

