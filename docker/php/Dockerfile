ARG VERSION

FROM docker-registry.priv.atolcd.com/atolcd/php-dev:${VERSION}

ARG MAIL_HOST

COPY php-mail.conf /usr/local/etc/php/conf.d/mailconf.ini

RUN echo "memory_limit=-1" > /usr/local/etc/php/conf.d/memory.ini

RUN sed -i "s@MAIL_HOST@${MAIL_HOST}@" /usr/local/etc/php/conf.d/mailconf.ini && \
    apk add --no-cache nodejs yarn rsync

RUN apk add --no-cache postgresql-client

WORKDIR /var/www
