FROM debian:bookworm-slim

COPY . /var/klodweb

WORKDIR /var/klodweb

RUN chmod +x /var/klodweb/setup/install.sh && /var/klodweb/setup/install.sh

EXPOSE 443

CMD ["apache2ctl", "-D", "FOREGROUND"]