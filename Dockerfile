FROM debian:bookworm-slim

COPY . /var/klodweb

RUN chmod +x /var/klodweb/setup/install.sh && /var/klodweb/setup/install.sh

WORKDIR /var/klodweb

EXPOSE 443

CMD ["apache2ctl", "-D", "FOREGROUND"]