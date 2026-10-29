# ---------------------------------------------------------------
#  Image aplikasi: PHP 8.3 + Apache + pdo_mysql
#  DocumentRoot diarahkan ke folder public/ (kode aplikasi di luar web root)
# ---------------------------------------------------------------
FROM php:8.3-apache

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN docker-php-ext-install pdo_mysql \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && echo "ServerName localhost" > /etc/apache2/conf-available/servername.conf \
    && a2enconf servername

# Pengaturan PHP untuk pengujian lokal (error tampil di browser, zona waktu WIB)
RUN { \
        echo "date.timezone=Asia/Jakarta"; \
        echo "display_errors=On"; \
        echo "error_reporting=E_ALL"; \
    } > "$PHP_INI_DIR/conf.d/zz-app.ini"

WORKDIR /var/www/html
COPY . /var/www/html

EXPOSE 80
