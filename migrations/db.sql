CREATE TABLE IF NOT EXISTS ed_config (
    id int(11) NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    value longtext NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS ed_admin (
    id int(11) NOT NULL AUTO_INCREMENT,
    first_name varchar(255) NOT NULL,
    last_name varchar(255) NOT NULL,
    email varchar(255) NOT NULL,
    password varchar(255) NOT NULL,
    createdAt timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updatedAt timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS ed_user (
    id int(11) NOT NULL AUTO_INCREMENT,
    first_name varchar(255) NOT NULL,
    last_name varchar(255) NOT NULL,
    email varchar(255) NOT NULL,
    password varchar(255) NOT NULL,
    createdAt timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updatedAt timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS ed_events (
    id int(11) NOT NULL AUTO_INCREMENT,
    attendees TEXT DEFAULT NULL,
    name varchar(255) NOT NULL,
    event_date datetime NOT NULL,
    createdAt timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updatedAt timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO ed_config (id, name, value)
VALUES
    (1, 'site_name', 'Edusogno'),
    (2, 'site_title', 'Edusogno'),
    (3, 'site_keywords', 'education, task, php, events'),
    (4, 'site_desc', 'We designed Edusogno with one simple goal Create a respectable, dedicated space (unlike other online sites) where students across the globe come together to share knowledge'),
    (5, 'site_email', 'hello@edusogno.com'),
    (6, 'smtp_or_mail', 'smtp'),
    (7, 'smtp_username', 'hello@edusogno.com'),
    (8, 'smtp_host', 'mail.edusogno.com'),
    (9, 'smtp_password', 'ObFC1IdNn'),
    (10, 'smtp_port', '587'),
    (11, 'smtp_encryption', 'tls'),
    (12, 'date_style', 'time_ago'),
    (13, 'site_url', 'http://edusogno'),
    (14, 'strong_password', '1');


ALTER TABLE ed_user ADD COLUMN user_id VARCHAR(100) UNIQUE NOT NULL AFTER id;
ALTER TABLE ed_admin ADD COLUMN admin_id VARCHAR(100) UNIQUE NOT NULL AFTER id;
ALTER TABLE ed_events ADD COLUMN event_id VARCHAR(100) UNIQUE NOT NULL AFTER id;

CREATE TABLE IF NOT EXISTS ed_verification (
                                         id int(11) NOT NULL AUTO_INCREMENT,
                                         email VARCHAR(255) NOT NULL,
                                         code varchar(255) NOT NULL,
                                         expires int(20) NOT NULL,
                                         PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO ed_admin (admin_id, first_name, last_name, email, password)
VALUE ('EDA0001', 'Wan', 'Peninsula','wan@wan.com', '$2y$10$MjgwYjUzMmJmYTU5ODFiZ.5fhsiDlH3jmSe8KHW9c8R0mWFyB9rNS');



