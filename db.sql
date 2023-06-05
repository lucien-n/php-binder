-- SQLBook: Code

DROP DATABASE binder;

CREATE DATABASE IF NOT EXISTS binder;

USE binder;

CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(36) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    username VARCHAR(30) NOT NULL,
    gender TINYINT(2) NOT NULL,
    liked_gender TINYINT(2) NOT NULL,
    image VARCHAR(1024) NOT NULL DEFAULT 'https://placehold.co/600x400?text=BinderUser',
    age INT(3) NOT NULL,
    bio VARCHAR(255) NOT NULL DEFAULT 'This user has no bio',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_seen TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX uuid_index (UUID)
);

CREATE TABLE IF NOT EXISTS messages (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(36) NOT NULL,
    sender_uuid VARCHAR(36) NOT NULL,
    receiver_uuid VARCHAR(36) NOT NULL,
    message VARCHAR(511) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_sender_uuid FOREIGN KEY (sender_uuid) REFERENCES users(uuid),
    CONSTRAINT fk_receiver_uuid FOREIGN KEY (receiver_uuid) REFERENCES users(uuid)
);

CREATE TABLE IF NOT EXISTS dislikes (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    disliker_uuid VARCHAR(36) NOT NULL,
    disliked_uuid VARCHAR(36) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_disliker_uuid FOREIGN KEY (disliker_uuid) REFERENCES users(uuid),
    CONSTRAINT fk_disliked_uuid FOREIGN KEY (disliked_uuid) REFERENCES users(uuid)
);

CREATE TABLE IF NOT EXISTS pending (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    liker_uuid VARCHAR(36) NOT NULL,
    liked_uuid VARCHAR(36) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_liker_uuid FOREIGN KEY (liker_uuid) REFERENCES users(uuid),
    CONSTRAINT fk_liked_uuid FOREIGN KEY (liked_uuid) REFERENCES users(uuid)
);

CREATE TABLE IF NOT EXISTS matchs (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    uuid_one VARCHAR(36) NOT NULL,
    uuid_two VARCHAR(36) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_uuid_one FOREIGN KEY (uuid_one) REFERENCES users(uuid),
    CONSTRAINT fk_uuid_two FOREIGN KEY (uuid_two) REFERENCES users(uuid)
);
