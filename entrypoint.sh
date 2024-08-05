#!/bin/bash

# Define the database credentials
DB_HOST='db'
DB_USER='root'
DB_PASSWORD='password'
DB_NAME='ks'

# Wait for the MySQL service to be available
while ! mysqladmin ping -h"$DB_HOST" --silent; do
    echo "Waiting for MySQL to be available..."
    sleep 2
done

echo "MySQL is available, proceeding with the database setup."

# Drop and recreate the database
mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASSWORD" -e "DROP DATABASE IF EXISTS $DB_NAME; CREATE DATABASE $DB_NAME;"
echo "Database $DB_NAME created."

# Import the initial schema
if [ -f /docker-entrypoint-initdb.d/schema.sql ]; then
    echo "Importing initial schema..."
    mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" < /docker-entrypoint-initdb.d/schema.sql
    echo "Initial schema imported."
else
    echo "Schema file not found. Please ensure /docker-entrypoint-initdb.d/schema.sql exists."
    exit 1
fi

# Execute any additional scripts in /docker-entrypoint-initdb.d/
for script in /docker-entrypoint-initdb.d/*.sql; do
    if [ "$script" != "/docker-entrypoint-initdb.d/schema.sql" ]; then
        echo "Executing script $script..."
        mysql -h "$DB_HOST" -u "$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" < "$script"
        echo "Script $script executed."
    fi
done

echo "Database setup completed."

# Start the PHP application
echo "Starting PHP application..."
apache2-foreground
