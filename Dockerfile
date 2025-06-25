FROM debian:bookworm-slim

COPY . /var/klodweb
RUN chmod +x /var/klodweb/setup/install.sh

WORKDIR /var/klodweb/setup

EXPOSE 443

CMD ["/bin/bash", "-c", "./install.sh; exec bash"]