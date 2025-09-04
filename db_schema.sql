CREATE DATABASE IF NOT EXISTS color_game;
USE color_game;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  balance DECIMAL(10,2) DEFAULT 0
);

CREATE TABLE bets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  color ENUM('Red','Green','Violet') NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  placed_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  result ENUM('Win','Lose') DEFAULT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE transactions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  type ENUM('Add','Withdraw','Bet','Win','Loss') NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
);
