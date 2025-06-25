#!/bin/bash
# ================================================
#   Manual variables
# ================================================
BASE_DIR="/var/klodweb/www"
APACHE_SITE="ssl-klodweb"
# APACHE_CONF_FILE="/etc/apache2/sites-available/ssl-klodweb.conf"

# BDD INFO
# Password is self generated if omitted.
# (put DB_PASS in # if in production)
DB_HOST="localhost"
DB_USER="klodadmin"
DB_PASS='Pw3Lqb6fuLspT7IrYp'
DB_NAME="klodwebsite"

# Generate a password if undefined before
DB_PASS="${DB_PASS:=$(openssl rand -base64 12)}"

# ================================================
#   Requisites installation
# ================================================
apt-get update
# Basic LAMP Stack
apt-get install mariadb-server apache2 php php-mysqli -y
# Additionals php modules 
apt-get install -y php-curl

echo ">>> End of requisites installations."

# ================================================
#   Launch daemons
# ================================================

service apache2 start
service mariadb start

echo ">>> All daemons started."

# ================================================
#   MariaDB Configuration
# ================================================

# Import SQL File (should be up to date)
mysql < website.sql

# User creation
mysql -h "$DB_HOST" -e "CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASS';"

if [ $? -eq 0 ]; then
    echo "L'utilisateur $DB_USER a ete cree avec succes."
else
    echo "echec de la creation de l'utilisateur $DB_USER."
    exit 1
fi

# User right attribution
mysql -h "$DB_HOST" -e "GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'localhost';"

if [ $? -eq 0 ]; then
    echo "Les privileges ont ete accordes a $DB_USER sur la base de donnees $DB_NAME."
else
    echo "echec de l'attribution des privileges a $DB_USER."
    exit 1
fi

# Refresh privileges
mysql -h "$DB_HOST" -e "FLUSH PRIVILEGES;"

echo ">>> End of MariaDB configuration."

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
service apache2 restart

echo ">>> End of Apache configuration."

# ==========================================================================
#   FINI
# ==========================================================================

echo ">>> End of KlodWeb configuration !"
