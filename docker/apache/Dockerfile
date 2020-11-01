ARG VERSION
ARG PHP_FPM_HOST=php
ARG CONF_FILE=demo.apache.conf
ARG APACHE_PORT=80

FROM httpd:${VERSION}

ARG PHP_FPM_HOST
ARG CONF_FILE
ARG APACHE_PORT

# Copy apache vhost file to proxy php requests to php-drupal container
COPY ${CONF_FILE} /usr/local/apache2/conf/${CONF_FILE}

RUN sed -i "s@PHP_FPM_HOST@${PHP_FPM_HOST}@" "/usr/local/apache2/conf/${CONF_FILE}" \
 && sed -i "s@APACHE_PORT@${APACHE_PORT}@" "/usr/local/apache2/conf/${CONF_FILE}"\
 && sed -i "s@Listen 80@Listen ${APACHE_PORT}@" "/usr/local/apache2/conf/httpd.conf"

RUN echo "Include /usr/local/apache2/conf/${CONF_FILE}" \
    >> /usr/local/apache2/conf/httpd.conf
