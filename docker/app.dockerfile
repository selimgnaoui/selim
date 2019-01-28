FROM php:5.6.35-fpm

RUN apt-get update && apt-get install -y libmcrypt-dev git bash-completion nano default-jre unzip \
    mysql-client libmagickwand-dev libcurl4-gnutls-dev libxslt1-dev libicu52 libicu-dev libkrb5-dev libc-client-dev \
    libfreetype6-dev libmcrypt-dev libjpeg-dev libpng-dev wget ssh sudo --no-install-recommends \
    && pecl install imagick \
    && pecl install mongodb-1.5.3 \
    && echo "yes" | pecl install memcache-3.0.8 \
    && pecl install redis-2.2.7 \
    && echo '' | pecl install apcu-4.0.8 \
    && docker-php-ext-enable imagick \
    && docker-php-ext-install mcrypt pdo_mysql curl mbstring xsl intl soap zip \
    && docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-install imap \
    && docker-php-ext-enable mongodb redis apcu memcache \
    && docker-php-ext-configure gd --enable-gd-native-ttf --with-freetype-dir=/usr/include/freetype2 --with-png-dir=/usr/include --with-jpeg-dir=/usr/include \
    && docker-php-ext-install gd \
    && docker-php-ext-enable gd

RUN pecl install xdebug-2.5.5 \
    && docker-php-ext-enable xdebug \
    && echo 'xdebug.remote_port=9000' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo 'xdebug.remote_enable=1' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo 'xdebug.remote_connect_back=1' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/bin/composer \
    && mkdir -p /usr/local/etc/php/conf.d \
    && touch /usr/local/etc/php/conf.d/php.ini \
    && echo "date.timezone = Europe/Berlin" >> /usr/local/etc/php/php.ini \
    && echo "memory_limit = 8G" >> /usr/local/etc/php/php.ini \
    && echo "max_execution_time = 180" >> /usr/local/etc/php/php.ini

RUN version=$(php -r "echo PHP_MAJOR_VERSION.PHP_MINOR_VERSION;") \
    && curl -A "Docker" -o /tmp/blackfire-probe.tar.gz -D - -L -s https://blackfire.io/api/v1/releases/probe/php/linux/amd64/$version \
    && mkdir -p /tmp/blackfire \
    && tar zxpf /tmp/blackfire-probe.tar.gz -C /tmp/blackfire \
    && mv /tmp/blackfire/blackfire-*.so $(php -r "echo ini_get('extension_dir');")/blackfire.so \
    && printf "extension=blackfire.so\nblackfire.agent_socket=tcp://blackfire:8707\n" > $PHP_INI_DIR/conf.d/blackfire.ini \
    && rm -rf /tmp/blackfire /tmp/blackfire-probe.tar.gz

RUN apt-get install -y apt-transport-https \
    && curl -sL https://deb.nodesource.com/setup_9.x | bash - \
    && curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
    && echo "deb https://dl.yarnpkg.com/debian/ stable main" > /etc/apt/sources.list.d/yarn.list \
    && apt-get update && apt-get install -y nodejs yarn \
    && ln -s /usr/bin/node /usr/local/bin/node \
    && ln -s /usr/bin/less /usr/local/bin/less \
    && ln -s /usr/lib/node_modules /usr/local/lib/node_modules \
    && npm install -g less@1.7.5

RUN chown -R www-data:www-data /var/www
ENV PATH="/var/www/vendor/bin:${PATH}"
