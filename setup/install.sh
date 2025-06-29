#!/bin/bash
# ================================================
#   Manual variables
# ================================================
BASE_DIR="/var/klodweb/www"
APACHE_SITE="ssl-klodweb"

# BDD INFO
# Password is self generated if omitted.
# (put DB_PASS in # if in production)
DB_HOST="localhost"
DB_USER="klodadmin"
DB_PASS='Pw3Lqb6fuLspT7IrYp'
DB_NAME="klodwebsite"

# ================================================
#	Apache Configuration
# ================================================
for site in /etc/apache2/sites-enabled/*; do
    a2dissite "$(basename "$site")"
done

# Activer le module SSL si ce n'est pas dejà fait
a2enmod ssl > /dev/null 2>&1

# Creer la configuration pour klodgame
echo "<VirtualHost *:443>
    DocumentRoot $BASE_DIR
    
    SSLEngine on
    SSLCertificateFile /etc/ssl/certs/ssl-cert-snakeoil.pem
    SSLCertificateKeyFile /etc/ssl/private/ssl-cert-snakeoil.key
    
    <Directory $BASE_DIR>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog \${APACHE_LOG_DIR}/error.log
    CustomLog \${APACHE_LOG_DIR}/access.log combined
    
</VirtualHost>" > /etc/apache2/sites-available/$APACHE_SITE.conf

a2ensite $APACHE_SITE

echo ">>> End of Apache configuration."

# ==========================================================================
#   FINI
# ==========================================================================

echo ">>> End of KlodWeb configuration !"
