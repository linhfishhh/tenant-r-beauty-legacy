FROM 323985919046.dkr.ecr.ap-southeast-1.amazonaws.com/dockerhub/php:7.1-fpm-ext
COPY --from=composer:1.10.21 /usr/bin/composer /usr/bin/composer
ADD . /var/www/

RUN cd /var/www && composer clear-cache && composer install --prefer-dist

RUN cd /var/www && php artisan view:clear
RUN chmod 777 /var/www/storage/logs/* -R || true
RUN chmod 777 /var/www/vendor/* -R || true
RUN chmod 777 /var/www/_ide_helper.php || true
RUN cd /var/www && php artisan ide-helper:generate
