[ikea_img]: www/img/ikea.png
# Könyvespolc összeszerelési útmutató

A könyvespolc telepítése rendkívül egyszerű, ez az útmutató segít az összeszerelésben.

![alt text][ikea_img]

## Virtuális szerver gyökérkönyvtár

A webszerveren a virtuális szervert nem a forráskód mappájára, hanem a `www` mappára kell irányítani. A többi mappa, így többek között a `lib` könyvrás is rejtve marad a könyespolcot böngésző felhasználók elől.

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

## Composer
Az oldal használ könyvtárakat, ezeket le kell töleni.
```shell
$ cd lib
$ composer update
```
Használt könyvtárak teljes listája:
* `ramsey/uuid`: UUID generáló
* `dwoo/dwoo`: Template rendszer

## Adatbázis létehozása
Miután a config file ki lett töltve, a rendszer automatikusan létrehozza a szükséges táblákat, csak meg kell hívni a létrehozó php-t.
Ehhez a gyökérkönyvrából (ahol ez a guide is található) le kell futtatni a következő parancsot:
```shell
$ php jobs/init_db.php
```

## Jogosultságok beállítása
A template fileok comoileolásához szükséges egy írható `templates/compiled` mappa.
Ennek a mappának a létrehozásához, és a megfelelő jogosultságok beállításához meg kell hívni a következő parancsot:
```shell
$ php jobs/init_templates.php
```

## TypeScript fordítása
Az oldal működéséhez a TypeScript fileokat le kell fordítani javascript fileokra.
Ezzez a fordítási config elő van készítve, csak meg kell hívni a következő parancsot:
```shell
$ tsc -p tsconfig.json
```
Ha ez nincs feltelepítve, akkor a `node package manager` segítségével feltelepíthető

## .htaccess
Az oldal működéséhez nagyon fontos, hogy a webszerveren engedélyezve legyen a `.hraccess` fileok használata.

Apache szerver esetén ehhez engedélyezni kell a mappában az overrideolást a `AllowOverride All` opcióval:
```apache
<Directory /path/to/konyvespolc>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
</Directory>
```