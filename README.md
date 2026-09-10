# NewsPortal

Lihtne PHP ja MySQL abil loodud uudisteportaal. Rakendus võimaldab kuvada uudiseid ja kategooriaid, registreerida kasutajaid, sisse logida ning lisada uudistele kommentaare. Administraator saab hallata uudiseid ja oma kontot eraldi admin-paneelis.

## Funktsioonid

- avalehel viimaste uudiste kuvamine;
- kõigi uudiste vaatamine;
- uudiste filtreerimine kategooria järgi;
- uudise detailvaade koos kommentaaridega;
- kasutaja registreerimine ja sisselogimine;
- sisseloginud kasutajatele kommentaaride lisamine;
- administraatori autentimine eraldi admin-paneelis;
- uudiste lisamine, muutmine ja kustutamine;
- uudisele pildi lisamine;
- administraatori kasutajanime ja parooli muutmine;
- paroolide salvestamine `password_hash()` abil;
- andmebaasipäringutes ettevalmistatud päringute kasutamine vormiandmete puhul.

## Tehnoloogiad

- PHP 7.4 või uuem;
- MySQL või MariaDB;
- Apache;
- PDO;
- HTML ja CSS.

Projekt ei kasuta Composerit ega JavaScripti raamistikku.

## Projekti struktuur

```text
.
├── index.php                 # Avaliku saidi sisenemispunkt
├── admin/index.php           # Admin-paneeli sisenemispunkt
├── controller/               # Avaliku saidi kontroller
├── model/                    # Uudiste, kategooriate, kasutajate ja kommentaaride loogika
├── route/                    # Avaliku saidi marsruutimine
├── view/                     # Avaliku saidi vaated
├── admin/controllerAdmin/    # Admin-paneeli kontroller
├── admin/modelAdmin/         # Admin-paneeli andmebaasiloogika
├── admin/routeAdmin/          # Admin-paneeli marsruutimine
├── admin/viewAdmin/           # Admin-paneeli vaated
├── inc/Database.php           # PDO ühendus MySQL-iga
└── images/                    # Piltide jaoks mõeldud kataloog
```

Rakendus järgib lihtsustatud MVC-struktuuri: marsruuter valib kontrolleri, kontroller kasutab mudeleid ja laadib sobiva vaate.

## Paigaldamine lokaalselt

### 1. Eeltingimused

Paigalda üks järgmistest komplektidest:

- XAMPP;
- Laragon;
- muu Apache, PHP ja MySQL sisaldav lokaalne arenduskeskkond.

Kontrolli, et PHP-s oleksid lubatud vähemalt `pdo` ja `pdo_mysql` laiendused.

### 2. Projekti paigutamine

Kopeeri projekt Apache'i dokumendikataloogi. XAMPP-i puhul näiteks:

```text
C:\xampp\htdocs\newsportal
```

### 3. Andmebaasi loomine

Loo MySQL-is andmebaas nimega `newsportal` ja käivita järgmine SQL. Kui sinu MySQL kasutaja, parool või host erineb, uuenda väärtused failis `inc/Database.php`.

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
    CONSTRAINT fk_news_category
        FOREIGN KEY (category_id) REFERENCES category(id),
    CONSTRAINT fk_news_user
        FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE comments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    news_id INT UNSIGNED NOT NULL,
    text TEXT NOT NULL,
    date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_comments_user
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_comments_news
        FOREIGN KEY (news_id) REFERENCES news(id) ON DELETE CASCADE
);
```

Lisa vähemalt üks kategooria:

```sql
INSERT INTO category (name) VALUES
    ('Tehnoloogia'),
    ('Maailm'),
    ('Kultuur');
```

### 4. Andmebaasiühenduse seadistamine

Ava `inc/Database.php` ja kontrolli järgmisi väärtusi:

```php
private $host = 'localhost';
private $dbname = 'newsportal';
private $username = 'root';
private $password = '';
```

Ära kasuta vaikimisi `root` kasutajat tootmiskeskkonnas. Tootmises loo rakendusele eraldi piiratud õigustega MySQL kasutaja.

### 5. Administraatori loomine

Registreeri kasutaja avaliku saidi kaudu aadressil `index.php?route=register`. Seejärel muuda tema staatus MySQL-is administraatoriks:

```sql
UPDATE users
SET status = 'admin'
WHERE email = 'admin@example.com';
```

Administraatori parooli ei tohi andmebaasi lisada tavalise tekstina. Registreerimisvorm loob parooliräsi automaatselt.

### 6. Rakenduse avamine

Käivita Apache ja MySQL ning ava brauseris:

```text
http://localhost/newsportal/
```

Admin-paneel asub aadressil:

```text
http://localhost/newsportal/admin/
```

## Marsruudid

### Avalik sait

| URL | Kirjeldus |
| --- | --- |
| `index.php` või `index.php?route=start` | Avaleht ja viimased uudised |
| `index.php?route=allnews` | Kõik uudised |
| `index.php?route=category` | Kõik kategooriad |
| `index.php?route=catnews&id=1` | Ühe kategooria uudised |
| `index.php?route=readnews&id=1` | Uudise detailvaade |
| `index.php?route=register` | Registreerimine |
| `index.php?route=account-login` | Kasutaja sisselogimine |
| `index.php?route=account-logout` | Väljalogimine |

Kommentaar lisatakse POST-päringuga marsruudile `index.php?route=addcomment&id=1`. Kommentaari saavad lisada ainult sisseloginud kasutajad.

### Admin-paneel

| URL | Kirjeldus |
| --- | --- |
| `admin/index.php?route=login` | Administraatori sisselogimine |
| `admin/index.php?route=dashboard` | Uudiste haldus |
| `admin/index.php?route=news-form` | Uue uudise lisamine |
| `admin/index.php?route=news-form&id=1` | Uudise muutmine |
| `admin/index.php?route=account` | Administraatori konto muutmine |
| `admin/index.php?route=logout` | Administraatori väljalogimine |

## Kasutusvoog

1. Administraator loob kategooriad otse andmebaasis.
2. Administraator logib sisse aadressil `/admin/`.
3. Administraator lisab uudise, määrab pealkirja, teksti ja kategooria ning võib lisada pildi.
4. Külastajad näevad uudiseid avalikul saidil.
5. Kasutaja registreerub ja logib sisse, et uudistele kommentaare lisada.

## Olulised märkused

- Projektis ei ole kaasas automaatseid migratsioone ega näidisandmebaasi faili; andmebaas tuleb luua ülaltoodud SQL-i abil.
- Pildid salvestatakse `news.picture` väljale binaarandmetena. Üleslaaditava faili suurust ja sisu tuleks enne tootmises kasutamist rangemalt valideerida.
- Vormidel puudub praegu CSRF-kaitse. Enne avalikku tootmiskasutust lisa CSRF-tokenid ja kontrolli päringumeetodeid kõikides muutvates tegevustes.
- Administraatori uudise kustutamine toimub GET-parameetri kaudu. Tootmises tasub see muuta POST-päringuks koos CSRF-kaitsega.
- Andmebaasi ühenduse veateade sisaldab praegu tehnilist infot. Tootmises logi detailne viga serverisse ja kuva kasutajale üldine veateade.
- Veendu, et PHP konfiguratsioon lubaks failide üleslaadimist ja `upload_max_filesize` oleks piltide jaoks piisav.


