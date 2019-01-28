#!/usr/bin/env bash

while ! mysqladmin ping -h 127.0.0.1 -u root -ppassword --silent; do
    sleep 1
done

mysql -u root -ppassword -h 127.0.0.1 <<EOF
    GRANT ALL PRIVILEGES ON *.* TO 'vorteile_circle'@'%';
EOF