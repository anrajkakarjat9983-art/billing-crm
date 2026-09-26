#!/bin/bash
set -euo pipefail

DB_NAME="${DB_NAME:-tameasy_billing_crm}"
DB_USER="${DB_USER:-tameasy_user}"
DB_PASS="${DB_PASS:-tameasy_user@123}"
PORT="${PORT:-10000}"

MYSQL_BIN="$(command -v mariadb || command -v mysql || true)"
ADMIN_BIN="$(command -v mariadb-admin || command -v mysqladmin || true)"
INSTALL_DB_BIN="$(command -v mariadb-install-db || true)"

if [ -z "$MYSQL_BIN" ] || [ -z "$ADMIN_BIN" ]; then
  echo "FATAL: MariaDB client not found in image"; exit 1
fi

mkdir -p /var/run/mysqld /var/lib/mysql /var/log/mysql
chown -R mysql:mysql /var/run/mysqld /var/lib/mysql /var/log/mysql

if [ ! -d /var/lib/mysql/mysql ]; then
  echo "==> Initializing MariaDB data directory"
  "$INSTALL_DB_BIN" --user=mysql --datadir=/var/lib/mysql >/dev/null
fi

echo "==> Starting MariaDB"
mysqld_safe --user=mysql --skip-name-resolve >/var/log/mysqld_safe.log 2>&1 &

# PHP's mysqli default socket path differs from MariaDB's; alias both
ln -sf /run/mysqld/mysqld.sock /tmp/mysql.sock 2>/dev/null || true
ln -sf /run/mysqld/mysqld.sock /var/run/mysqld/mysqld.sock 2>/dev/null || true

ready=0
for i in $(seq 1 90); do
  if "$ADMIN_BIN" ping --silent >/dev/null 2>&1; then ready=1; break; fi
  sleep 1
done

if [ "$ready" -ne 1 ]; then
  echo "FATAL: MariaDB did not become ready"; tail -50 /var/log/mysqld_safe.log 2>/dev/null || true; exit 1
fi

echo "==> Provisioning database '$DB_NAME'"
"$MYSQL_BIN" -uroot <<SQL
CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
CREATE USER IF NOT EXISTS '${DB_USER}'@'%' IDENTIFIED BY '${DB_PASS}';
CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';
GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'%';
GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'localhost';
FLUSH PRIVILEGES;
SQL

TABLE_COUNT="$("$MYSQL_BIN" -uroot -N -B -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='${DB_NAME}';")"
if [ "$TABLE_COUNT" -lt 50 ]; then
  echo "==> Importing schema (found ${TABLE_COUNT} tables)"
  "$MYSQL_BIN" -uroot "$DB_NAME" < /var/www/html/docker-init.sql
  echo "==> Schema import finished"
else
  echo "==> Database already initialised (${TABLE_COUNT} tables), skipping import"
fi

# Apache must listen on the port Render assigns
sed -i "s/^Listen 80$/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

chown -R www-data:www-data /var/www/html/uploads /var/www/html/application/logs /var/www/html/application/cache 2>/dev/null || true
chmod -R 777 /var/www/html/uploads /var/www/html/application/logs /var/www/html/application/cache 2>/dev/null || true

echo "==> Billing CRM ready on port ${PORT}"
exec apache2-foreground
