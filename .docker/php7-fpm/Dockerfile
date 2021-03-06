FROM php:7.2-fpm
ARG TIMEZONE

RUN apt-get update \
	&& apt-get install -y openssl unzip cron supervisor \
  # Install Composer
	&& curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
	&& composer --version \
  # Set timezone
	&& ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone \
	&& printf '[PHP]\ndate.timezone = "%s"\n', ${TIMEZONE} > /usr/local/etc/php/conf.d/tzone.ini \
	&& "date" \
  # Type docker-php-ext-install to see available extensions
	&& docker-php-ext-install pdo pdo_mysql \
  # install xdebug
	&& pecl install xdebug \
	&& docker-php-ext-enable xdebug \
	&& echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
	&& echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
	&& echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
	&& echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
	&& echo "xdebug.remote_connect_back=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
	&& echo "xdebug.idekey=\"PHPSTORM\"" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
	&& echo "xdebug.remote_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

ADD supervisord.conf /etc/supervisor/conf.d/supervisord.conf

ADD hubspot-cron /etc/cron.d/hubspot-cron
RUN chmod 0644 /etc/cron.d/hubspot-cron \
    && crontab /etc/cron.d/hubspot-cron

WORKDIR /var/www/symfony

COPY ./start /
ENTRYPOINT /start
