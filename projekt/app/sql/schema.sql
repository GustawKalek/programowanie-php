CREATE DATABASE IF NOT EXISTS dziennik CHARACTER SET utf8mb4 COLLATE utf8mb4_polish_ci;
USE dziennik;

CREATE TABLE IF NOT EXISTS uzytkownicy (
  id INT AUTO_INCREMENT PRIMARY KEY,
  imie VARCHAR(50) NOT NULL,
  nazwisko VARCHAR(50) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  haslo_hash VARCHAR(255) NOT NULL,
  rola ENUM('uczen', 'nauczyciel', 'admin') NOT NULL DEFAULT 'uczen',
  klasa VARCHAR(10) NULL,
  utworzono DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS przedmioty (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nazwa VARCHAR(50) NOT NULL UNIQUE,
  nauczyciel_id INT NULL,
  FOREIGN KEY (nauczyciel_id) REFERENCES uzytkownicy(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS uczniowie_przedmioty (
  uczen_id INT NOT NULL,
  przedmiot_id INT NOT NULL,
  PRIMARY KEY (uczen_id, przedmiot_id),
  FOREIGN KEY (uczen_id) REFERENCES uzytkownicy(id) ON DELETE CASCADE,
  FOREIGN KEY (przedmiot_id) REFERENCES przedmioty(id) ON DELETE CASCADE
);
