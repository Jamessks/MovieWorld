FROM nginx:latest

RUN apt-get update && apt-get install -y \
    procps \
    gettext-base \
    curl \
    unzip \
    ca-certificates \
    php-cli \
    php-zip \
    php-mbstring \
    php-curl \
    php-xml \
    php-bcmath \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ARG APP_NAME

ENV APP_NAME=${APP_NAME}

WORKDIR /var/www/$APP_NAME

COPY ./composer.json /var/www/$APP_NAME/composer.json
COPY ./composer.lock /var/www/$APP_NAME/composer.lock

RUN composer install --no-dev --optimize-autoloader

COPY . /var/www/$APP_NAME

RUN chown -R www-data:www-data /var/www/$APP_NAME/public
RUN chmod -R 755 /var/www/$APP_NAME/public

CMD envsubst '${APP_NAME}' < /etc/nginx/conf.d/default.conf.template > /etc/nginx/conf.d/default.conf && exec nginx -g 'daemon off;'

# Expose port 80
EXPOSE 80