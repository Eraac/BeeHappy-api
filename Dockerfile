FROM busybox

MAINTAINER Kévin Labesse kevin@labesse.me

COPY . /var/www/symfony

RUN chmod u+rws,g+rws,o+rws /var/www/symfony/var/cache
RUN chmod u+rws,g+rws,o+rws /var/www/symfony/var/logs
RUN chmod u+rws,g+rws,o+rws /var/www/symfony/var/sessions

VOLUME /var/www/symfony
