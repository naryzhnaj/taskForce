CREATE DATABASE taskforse
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;
USE taskforse;

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(128) UNIQUE NOT NULL,
    icon VARCHAR(128)
);

CREATE TABLE cities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(128) NOT NULL,
    lat DEC(10,7),
    longitude DEC(10,7),
    FULLTEXT INDEX (title)
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    city_id INT,
    name VARCHAR(60) NOT NULL,
    FULLTEXT INDEX (name),
    email VARCHAR(60) NOT NULL,
    UNIQUE INDEX (email),
    password VARCHAR(128) NOT NULL,
    rating DEC(2,1) DEFAULT 0,
    orders INT DEFAULT 0,
    failures INT DEFAULT 0,
    popularity INT DEFAULT 0,
    dt_add DATETIME DEFAULT NOW(),
    FOREIGN KEY (city_id) REFERENCES cities(id)
);

CREATE TABLE favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    favorite_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (favorite_id) REFERENCES users(id)
);

CREATE TABLE specialization (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    category_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    author_id INT,
    city_id INT,
    executor_id INT,
    category_id INT,
    title VARCHAR(128) NOT NULL,
    description VARCHAR(255) NOT NULL,
    end_date DATETIME,
    budget INT UNSIGNED,
    address VARCHAR(128),
    lat DEC(10,7),
    longitude DEC(10,7),
    image VARCHAR(255),
    status CHAR(11) NOT NULL DEFAULT 'new',
    dt_add DATETIME DEFAULT NOW(),
    FOREIGN KEY (author_id) REFERENCES users(id),
    FOREIGN KEY (executor_id) REFERENCES users(id),
    FOREIGN KEY (city_id) REFERENCES cities(id),
    FOREIGN KEY (category_id) REFERENCES categories(id),
    INDEX (status),
    INDEX (city_id),
    INDEX (category_id),
    FULLTEXT INDEX (title)
);

CREATE TABLE responds (
    id INT AUTO_INCREMENT PRIMARY KEY,
    author_id INT,
    task_id INT,
    dt_add DATETIME DEFAULT NOW(),
    price INT NOT NULL,
    comment VARCHAR(255),
    status CHAR(11) NOT NULL DEFAULT 'new',
    FOREIGN KEY (task_id) REFERENCES tasks(id),
    FOREIGN KEY (author_id) REFERENCES users(id)
);

CREATE TABLE chats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT,
    task_id INT,
    receiver_id INT,
    comment VARCHAR(255) NOT NULL,
    time DATETIME DEFAULT NOW(),
    FOREIGN KEY (task_id) REFERENCES tasks(id),
    FOREIGN KEY (receiver_id) REFERENCES users(id),
    FOREIGN KEY (sender_id) REFERENCES users(id)
);

CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task_id INT,
    user_id INT,
    dt_add DATETIME DEFAULT NOW(),
    value TINYINT NOT NULL,
    comment VARCHAR(255),
    FOREIGN KEY (task_id) REFERENCES tasks(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    address VARCHAR(60),
    phone CHAR(11),
    skype CHAR(20),
    contact VARCHAR(60),
    avatar VARCHAR(255),
    bio VARCHAR(255),
    birth_date DATE NOT NULL,
    portfolio VARCHAR(255),
    is_free BOOLEAN DEFAULT TRUE,
    last_activity DATETIME,
    notifications_allowed BOOLEAN DEFAULT TRUE,
    is_visible BOOLEAN DEFAULT TRUE,
    contacts_visible BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
