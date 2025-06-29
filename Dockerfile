FROM php:apache

COPY . /var/klodweb

WORKDIR /var/klodweb

RUN apt-get update
RUN apt-get install -y ssl-cert
RUN apt-get install -y libcurl4-openssl-dev pkg-config
RUN docker-php-ext-install mysqli curl
RUN chmod +x /var/klodweb/setup/install.sh && /var/klodweb/setup/install.sh

EXPOSE 443

CMD ["bash", "-c", "apache2ctl -D FOREGROUND"]