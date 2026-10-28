# Dziennik lekcyjny

Projekt zaliczeniowy z przedmiotu Języki programowania - PHP.

Grupa: Szymon Jabłoński, Gustaw Kałek, Marcel Sierocki

## Technologie

- PHP 8
- MariaDB
- HTML, CSS
- XAMPP

## Uruchomienie w XAMPP

1. Sklonować repozytorium do folderu `C:\xampp\htdocs\dziennik`:
   `git clone https://github.com/GustawKalek/programowanie-php.git`
2. W XAMPP Control Panel uruchomić Apache i MySQL.
3. W phpMyAdmin (http://localhost/phpmyadmin) zaimportować plik `sql/schema.sql`, a potem `sql/seed.sql` (konta testowe, hasła w `projekt/konta_testowe.txt`).
4. Skopiować `includes/config.example.php` jako `includes/config.php` i w razie potrzeby zmienić dane do bazy.
5. Otworzyć w przeglądarce: http://localhost/dziennik/programowanie-php/projekt/app
