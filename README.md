# NewsPortal

Lihtne PHP ja MySQL abil loodud uudisteportaal. Veebisaidil saab vaadata uudiseid ja kategooriaid, registreerida kasutajaid, kontole sisse logida ning kommentaare lisada. Administraator saab uudiseid eraldi admin-paneelis hallata.

## Mida tuleb alla laadida

Windowsis on kõige lihtsam kasutada **XAMPP-i**:

1. Laadige XAMPP alla ametlikult veebisaidilt: <https://www.apachefriends.org/index.html>
2. Paigaldage see koos komponentidega **Apache**, **MySQL** ja **PHP**.
3. Composerit, Node.js-i ega muid eraldi teeke ei ole selle projekti jaoks vaja.

XAMPP-i asemel võib kasutada ka Laragoni või muud keskkonda, kus on Apache, PHP ja MySQL/MariaDB.

## Nõuded

- PHP 7.4 või uuem;
- MySQL või MariaDB;
- Apache;
- PHP laiendused `pdo` ja `pdo_mysql`;
- failide üleslaadimise tugi, kui uudistele on vaja pilte lisada.

## Käivitamine XAMPP-i abil

### 1. Kopeerige projekt

Pakkige projekt XAMPP-i kataloogi `htdocs`. Tulemuseks peab olema näiteks järgmine asukoht:

```text
C:\xampp\htdocs\newsportal
```

Oluline: fail `index.php` peab asuma otse kaustas `newsportal`.

### 2. Käivitage Apache ja MySQL

1. Avage **XAMPP Control Panel**.
2. Vajutage `Apache` rea kõrval nuppu **Start**.
3. Vajutage `MySQL` rea kõrval nuppu **Start**.

### 3. Looge andmebaas

Avage brauseris <http://localhost/phpmyadmin>, valige vahekaart **SQL** ja käivitage järgmine skript:

```sql
CREATE DATABASE IF NOT EXISTS newsportal
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE newsportal;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    status ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    registration_date DATE NOT NULL,
    pass VARCHAR(255) NOT NULL
);

CREATE TABLE category (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL UNIQUE
);

CREATE TABLE news (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    text TEXT NOT NULL,
    picture MEDIUMBLOB NULL,
    category_id INT UNSIGNED NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    CONSTRAINT fk_news_category FOREIGN KEY (category_id) REFERENCES category(id),
    CONSTRAINT fk_news_user FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE comments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    news_id INT UNSIGNED NOT NULL,
    text TEXT NOT NULL,
    date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_comments_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_comments_news FOREIGN KEY (news_id) REFERENCES news(id) ON DELETE CASCADE
);

INSERT INTO category (name) VALUES
    ('Tehnoloogia'),
    ('Maailm'),
    ('Kultuur');
```

### 4. Kontrollige andmebaasiühendust

Avage fail `inc/Database.php`. XAMPP-i standardse paigalduse korral peavad seal olema järgmised väärtused:

```php
private $host = 'localhost';
private $dbname = 'newsportal';
private $username = 'root';
private $password = '';
```

Kui MySQL-i kasutajal on parool või kasutatakse teist porti, muutke need väärtused failis vastavaks.

### 5. Avage veebisait

Avalik veebisait:

<http://localhost/newsportal/>

Admin-paneel:

<http://localhost/newsportal/admin/>

Kui kuvatakse andmebaasiühenduse viga, kontrollige, kas MySQL töötab, andmebaasi nimi on `newsportal` ning `inc/Database.php` seadistused vastavad MySQL-i seadistustele.

## Administraatori loomine

1. Avage registreerimisleht: <http://localhost/newsportal/index.php?route=register>
2. Registreerige tavaline kasutaja.
3. Avage phpMyAdminis andmebaas `newsportal`, valige vahekaart SQL ja käivitage järgmine päring. Asendage email enda kasutaja emailiga:

```sql
UPDATE users
SET status = 'admin'
WHERE email = 'admin@example.com';
```

4. Logige admin-paneeli sisse aadressil <http://localhost/newsportal/admin/> registreeritud kasutaja emaili ja parooliga.

Rakendus salvestab parooli räsi kujul. Ärge lisage parooli andmebaasi tavalise tekstina.

## Olulised lehed

| Aadress | Otstarve |
| --- | --- |
| `index.php` | Avaleht |
| `index.php?route=allnews` | Kõik uudised |
| `index.php?route=category` | Kõik kategooriad |
| `index.php?route=register` | Registreerimine |
| `index.php?route=account-login` | Kasutaja sisselogimine |
| `admin/index.php?route=login` | Administraatori sisselogimine |
| `admin/index.php?route=dashboard` | Uudiste haldus |
| `admin/index.php?route=news-form` | Uue uudise lisamine |
| `admin/index.php?route=account` | Administraatori konto seaded |

## Projekti struktuur

```text
index.php                 # Avaliku osa sisenemispunkt
admin/index.php           # Admin-paneeli sisenemispunkt
controller/               # Avaliku osa kontrollerid
model/                    # Kasutajate, uudiste ja kommentaaride mudelid
route/                    # Avaliku osa marsruutimine
view/                     # Avaliku osa vaated
admin/controllerAdmin/    # Admin-paneeli kontrollerid
admin/modelAdmin/         # Admin-paneeli mudelid
admin/routeAdmin/         # Admin-paneeli marsruutimine
admin/viewAdmin/          # Admin-paneeli vaated
inc/Database.php          # PDO ühendus MySQL-iga
images/                   # Piltide kataloog
```

## Käivitamine ilma XAMPP-ita

Kui PHP ja MySQL on eraldi paigaldatud, käivitage MySQL, seadistage `inc/Database.php`, looge andmebaas ülaltoodud SQL-i abil ja käivitage projekti kaustas PowerShellis:

```powershell
php -S localhost:8000
```

Seejärel avage <http://localhost:8000/>. XAMPP-i kaudu käivitamisel kasutage eespool toodud aadressi.

## Olulised märkused

- Projektis ei ole Composerit ega valmis migratsioonifaili. Tabelid tuleb luua selles README-s oleva SQL-skripti abil.
- Enne projekti internetis avaldamist lisage CSRF-kaitse ja rangem üleslaaditavate piltide kontroll.
- Ärge kasutage tootmisserveris MySQL-i kasutajat `root` tühja parooliga.
- Suurte piltide üleslaadimiseks suurendage vajaduse korral PHP seadistuses `upload_max_filesize` väärtust.
