CREATE DATABASE taskforse
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;
USE taskforse;

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title CHAR(128) UNIQUE NOT NULL
);

CREATE TABLE cities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title CHAR(128) NOT NULL,
    FULLTEXT INDEX (title)
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name CHAR(128) NOT NULL,
    email CHAR(128) NOT NULL,
    password CHAR(128) NOT NULL,
    phone CHAR(128),
    skype CHAR(128),
    contact CHAR(128),
    avatar CHAR(255),
    vita CHAR(255),
    dob DATE NOT NULL,
    rating DEC(2,1) DEFAULT 0.0,
    orders INT DEFAULT 0,
    failures INT DEFAULT 0,
    is_free BOOLEAN DEFAULT TRUE,
    last_activity DATETIME,
    notifications_allowed BOOLEAN DEFAULT TRUE,
    is_visible BOOLEAN DEFAULT TRUE,
    contacts_visible BOOLEAN DEFAULT TRUE,
    popularity INT DEFAULT 0,
    portfolio CHAR(255),
    FULLTEXT INDEX (name),
    UNIQUE INDEX (email)
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
    title CHAR(128) NOT NULL,
    description CHAR(255) NOT NULL,
    end_date DATETIME,
    author_id INT,
    city_id INT,
    executor_id INT,
    category_id INT,
    budget INT UNSIGNED,
    location CHAR(128),
    image CHAR(255),
    status CHAR(128) NOT NULL DEFAULT 'new',
    post_time DATETIME DEFAULT NOW(),
    FOREIGN KEY (author_id) REFERENCES users(id),
    FOREIGN KEY (executor_id) REFERENCES users(id),
    FOREIGN KEY (city_id) REFERENCES cities(id),
    FOREIGN KEY (category_id) REFERENCES categories(id),
    INDEX (status),
    FULLTEXT INDEX (title)
);

CREATE TABLE responds (
    id INT AUTO_INCREMENT PRIMARY KEY,
    author_id INT,
    task_id INT,
    price INT NOT NULL,
    comment CHAR(255),
    status CHAR(128) NOT NULL DEFAULT 'new',
    FOREIGN KEY (task_id) REFERENCES tasks(id),
    FOREIGN KEY (author_id) REFERENCES users(id)
);

CREATE TABLE chats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    author_id INT,
    task_id INT,
    user_id INT,
    comment CHAR(255) NOT NULL,
    time DATETIME DEFAULT NOW(),
    FOREIGN KEY (task_id) REFERENCES tasks(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (author_id) REFERENCES users(id)
);

CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task_id INT,
    user_id INT,
    value TINYINT NOT NULL,
    comment CHAR(128),
    FOREIGN KEY (task_id) REFERENCES tasks(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);