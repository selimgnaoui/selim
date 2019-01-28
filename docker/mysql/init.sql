DROP DATABASE IF EXISTS vorteile;

CREATE DATABASE vorteile CHARACTER SET utf8 COLLATE utf8_general_ci;
CREATE USER 'vorteile'@'%' IDENTIFIED BY 'password';

GRANT ALL PRIVILEGES ON *.* TO 'vorteile'@'%';

DROP DATABASE IF EXISTS vorteile_test;

CREATE DATABASE vorteile_test CHARACTER SET utf8 COLLATE utf8_general_ci;
CREATE USER 'vorteile_test'@'%' IDENTIFIED BY 'password';

GRANT ALL PRIVILEGES ON *.* TO 'vorteile_test'@'%';