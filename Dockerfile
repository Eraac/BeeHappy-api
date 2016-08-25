FROM busybox

MAINTAINER Kévin Labesse kevin@labesse.me

COPY . /var/www/symfony

RUN chmod o+w /var/www/symfony/var/cache
RUN chmod o+w /var/www/symfony/var/logs

VOLUME /var/www/symfony
