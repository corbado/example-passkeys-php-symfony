FROM php:8-alpine
ARG TARGETOS
ARG TARGETARCH

ENV PROJECT_ID \
    API_SECRET \
    CLI_SECRET \
    WEBHOOK_USERNAME \
    WEBHOOK_PASSWORD \
    MYSQL_USERNAME \
    MYSQL_PASSWORD

RUN apk add git

RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql

RUN curl -sS https://getcomposer.org/installer -o composer-setup.php && php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN curl -o /tmp/corbado_cli.tar.gz -sSL https://github.com/corbado/cli/releases/download/v1.0.2/corbado_cli_v1.0.2_${TARGETOS}_${TARGETARCH}.tar.gz && tar xfz /tmp/corbado_cli.tar.gz && mv corbado /usr/local/bin/corbado && chmod +x /usr/local/bin/corbado
WORKDIR /var/www/html

ENTRYPOINT ./bin/startup.sh
