[ikea_img]: www/img/ikea.png
# Könyvespolc összeszerelési útmutató

A könyvespolc telepítése rendkívül egyszerű, ez az útmutató segít az összeszerelésben.

![alt text][ikea_img]

## Config kitöltése
1. Le kell másolni a `config_template.php` filet `config.php` néven.
2. SQL kitöltése
```php
"mysql" => [
    "host" => "localhost",
    "username" => "example_username",
    "password" => "my_password",
    "database" => "example_db"
]
```
3. reCapctha
    * Be kell jelentkezni a `https://www.google.com/recaptcha/admin` oldalra
    * Létre kell hozni egy új `reCAPTCHA v2` típusú captcha kódot
    * A kulcsok közül a site key és a secret key is szükséges.
```php
"recaptcha" => [
    "site_key" => "my_site_key",
    "secret" => "my_private_key"
]
```
4. Ki kell választani a feltöltött fileok tárolási helyét
    * `local`: fileok tárolása helyi tárhelyen
    * `azure`: fileok feltöltése azure blob storage rendszerbe
```php
"uploaded_files" => "local"
```
6. (opcionális) Ha a profilképeket azureban szeretnénk tárolni, helyi hárhely helyett, akkor a configban kitölthetőek az azure adatok. Ez nem körelező!
```php
"azure" => [
    "storage" => [
        "container_name" => "bookshelf",
        "connection_string" => "my_connection_string"
    ]
]
```

## Adatbázis létehozása
Miután a config file ki lett töltve, a rendszer automatikusan létrehozza a szükséges táblákat, csak meg kell hívni a létrehozó php-t.
Ehhez a gyökérkönyvrából (ahol ez a guide is található) le kell futtatni a következő parancsot:
```bash
php jobs/init_db.php
```

## Jogosultságok beállítása
A template fileok comoileolásához szükséges egy írható `templates/compiled` mappa.
Ennek a mappának a létrehozásához, és a megfelelő jogosultságok beállításához meg kell hívni a következő parancsot:
```bash
php jobs/init_templates.php
```

## TypeScript fordítása
Az oldal működéséhez a TypeScript fileokat le kell fordítani javascript fileokra.
Ezzez a fordítási config elő van készítve, csak meg kell hívni a következő parancsot:
```bash
tsc -p tsconfig.json
```
Ha ez nincs feltelepítve, akkor a `node package manager` (fúj) segítségével feltelepíthető