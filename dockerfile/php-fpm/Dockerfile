FROM php:8.1-fpm

RUN apt update -y && apt upgrade -y

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions

RUN install-php-extensions opcache
RUN install-php-extensions intl
RUN install-php-extensions gd
RUN install-php-extensions exif
RUN install-php-extensions imagick
RUN install-php-extensions mysqli
RUN install-php-extensions pdo_mysql
RUN install-php-extensions xmlrpc
RUN install-php-extensions redis
RUN install-php-extensions xdebug
RUN install-php-extensions DOM
RUN install-php-extensions zip

RUN cd /tmp
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === 'e21205b207c3ff031906575712edab6f13eb0b361f2085f1f1237b7126d785e826a450292b6cfd1d64d92e6563bbde02') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer
RUN cd -
