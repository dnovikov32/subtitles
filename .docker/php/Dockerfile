FROM php:8.2-fpm

ARG PHP_STDOUT="/proc/self/fd/2"

ENV EXTRA_PERMISSIONS="/run/php-fpm \
                       /var/lib/php \
                       /var/log/php-fpm \
                       /home/docker/.composer"

RUN apt update && apt install locales -y && \
    echo "Europe/Moscow" > /etc/timezone && \
    rm -f /etc/localtime && \
    dpkg-reconfigure -f noninteractive tzdata && \
    sed -i '/en_US.UTF-8/s/^# //g' /etc/locale.gen && locale-gen

RUN apt install -y \
    g++ \
    git \
    htop \
    iputils-ping \
    libfontconfig1 \
    libfreetype6-dev \
    libicu-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libpng-dev \
    libpq-dev \
    libssl-dev \
    libxml2-dev \
    libxrender1 \
    libzip-dev \
    locate \
    mc \
    nano \
    sudo \
    telnet \
    zip \
    zlib1g-dev \
    wget

RUN docker-php-ext-configure gd --with-jpeg=/usr/include --with-freetype=/usr/include

RUN docker-php-ext-install -j$(nproc) \
    bcmath \
    ctype \
    dom \
    exif \
    fileinfo \
    gd \
    gettext \
    iconv \
    intl \
    opcache \
    pdo \
    pdo_pgsql \
    phar \
    simplexml \
    zip

RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

RUN \
    sed -ri "s|;?realpath_cache_size(\s+)?=.*|realpath_cache_size = 4096K|g" /usr/local/etc/php/php.ini && \
    sed -ri "s|;?realpath_cache_ttl(\s+)?=.*|realpath_cache_ttl = 600|g" /usr/local/etc/php/php.ini && \
    sed -ri "s|;?max_execution_time(\s+)?=.*|max_execution_time = 60|g" /usr/local/etc/php/php.ini && \
    sed -ri "s|;?date.timezone(\s+)?=.*|date.timezone = Europe/Moscow|g" /usr/local/etc/php/php.ini && \
    sed -ri "s|;?expose_php(\s+)?=.*|expose_php = Off|g" /usr/local/etc/php/php.ini && \
    sed -ri "s|;?log_errors(\s+)?=.*|log_errors = On|g" /usr/local/etc/php/php.ini && \
    sed -ri "s|;?soap.wsdl_cache_enabled(\s+)?=.*|soap.wsdl_cache_enabled = 0|g" /usr/local/etc/php/php.ini && \
    sed -ri "s|;?soap.wsdl_cache_dir(.*)|;soap.wsdl_cache_dir\1|g" /usr/local/etc/php/php.ini && \
    sed -ri "s|;?log_errors_max_len(\s+)?=.*|log_errors_max_len = 0|g" /usr/local/etc/php/php.ini && \
    sed -ri "s|;?error_log(\s+)?=.*|error_log = ${PHP_STDOUT}|g" /usr/local/etc/php/php.ini

RUN \
    sed -ri "s|;?opcache.memory_consumption.*=.*|opcache.memory_consumption = 256|g" /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini && \
    sed -ri "s|;?opcache.max_accelerated_files.*=.*|opcache.max_accelerated_files = 20000|g" /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini && \
    sed -ri "s|;?opcache.validate_timestamps.*=.*|opcache.validate_timestamps = 0|g" /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini

RUN \
    sed -ri "s|;?daemonize(\s+)?=.*|daemonize = no|g" /usr/local/etc/php-fpm.conf && \
    sed -ri "s|;?emergency_restart_threshold(\s+)?=.*|emergency_restart_threshold = 2|g" /usr/local/etc/php-fpm.conf && \
    sed -ri "s|;?emergency_restart_interval(\s+)?=.*|emergency_restart_interval = 1m|g" /usr/local/etc/php-fpm.conf && \
    sed -ri "s|;?process_control_timeout(\s+)?=.*|process_control_timeout = 5|g" /usr/local/etc/php-fpm.conf && \
    sed -ri "s|;?error_log(\s+)?=.*|error_log = ${PHP_STDOUT}|g" /usr/local/etc/php-fpm.conf

RUN \
    sed -ri "s|;?user(\s+)?=.*|user = docker|g" /usr/local/etc/php-fpm.d/www.conf && \
    sed -ri "s|;?group(\s+)?=.*|group = docker|g" /usr/local/etc/php-fpm.d/www.conf && \
    sed -ri "s|^php_admin_(.*)|;php_admin_\1|g" /usr/local/etc/php-fpm.d/www.conf && \
    sed -ri "s|;?listen(\s+)?=.*|listen = 0.0.0.0:9000|g" /usr/local/etc/php-fpm.d/www.conf && \
    sed -ri "s|^listen.allowed_clients(.*)|;listen.allowed_clients\1|g" /usr/local/etc/php-fpm.d/www.conf && \
    sed -ri "s|;?clear_env(\s+)?=.*|clear_env = no|g" /usr/local/etc/php-fpm.d/www.conf && \
    sed -ri "s|;?catch_workers_output(\s+)?=.*|catch_workers_output = yes|g" /usr/local/etc/php-fpm.d/www.conf && \
    sed -ri "s|;?pm.max_children(\s+)?=.*|pm.max_children = 5|g" /usr/local/etc/php-fpm.d/www.conf && \
    sed -ri "s|;?pm.start_servers(\s+)?=.*|pm.start_servers = 2|g" /usr/local/etc/php-fpm.d/www.conf && \
    sed -ri "s|;?pm.min_spare_servers(\s+)?=.*|pm.min_spare_servers = 1|g" /usr/local/etc/php-fpm.d/www.conf && \
    sed -ri "s|;?pm.max_spare_servers(\s+)?=.*|pm.max_spare_servers = 3|g" /usr/local/etc/php-fpm.d/www.conf && \
    sed -ri "s|;?pm.max_requests(\s+)?=.*|pm.max_requests = 500|g" /usr/local/etc/php-fpm.d/www.conf && \
    sed -ri "s|;?request_terminate_timeout(\s+)?=.*|request_terminate_timeout = 120s|g" /usr/local/etc/php-fpm.d/www.conf && \
    sed -ri "s|;?slowlog(\s+)?=.*|slowlog = ${PHP_STDOUT}|g" /usr/local/etc/php-fpm.d/www.conf && \
    sed -ri "s|;?access.log(\s+)?=.*|access.log = ${PHP_STDOUT}|g" /usr/local/etc/php-fpm.d/www.conf

RUN mkdir -p /home/docker && \
    mkdir -p /run/php-fpm && \
    mkdir -p /var/lib/php && \
    mkdir -p /var/log/php-fpm

RUN curl -sS https://getcomposer.org/installer | php -- \
        --filename=composer \
        --install-dir=/usr/local/bin && \
        echo "alias composer='composer'" >> /home/docker/.bashrc

WORKDIR /app
