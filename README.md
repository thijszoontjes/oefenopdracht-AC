# Catalog Health Dashboard

Laravel + Filament · Northwind oefenproject

---

## Snel starten (zonder Docker)

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```

Open: http://localhost:8000/admin
**Login:** admin@example.com / password

---

## Snel starten (met Docker / Laravel Sail)

```bash
composer install
cp .env.example .env
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed
```

Open: http://localhost/admin

---

## Feed opnieuw importeren

```bash
php artisan catalog:import
# of via Sail:
./vendor/bin/sail artisan catalog:import
```

---

## Hoe Docker / Sail werkt

Laravel Sail start twee Docker-containers via docker-compose.yml:

| Container       | Rol                                      |
|-----------------|------------------------------------------|
| laravel.test    | PHP 8.4 + Nginx — de Laravel-applicatie  |
| mysql           | MySQL-database                           |

Elke ontwikkelaar draait exact dezelfde omgeving.
Geen "bij mij werkt het wel"-problemen meer.

Handige Sail-commando`s:
```bash
./vendor/bin/sail up -d        # containers starten
./vendor/bin/sail down         # containers stoppen
./vendor/bin/sail artisan ...  # artisan-commando in de container
./vendor/bin/sail bash         # shell openen in PHP-container
```

---

## Projectstructuur

```
app/
  Catalog/
    Import/
      CatalogFeedClient.php      <- haalt data op (fixture of API)
      CatalogImporter.php        <- mapt brondata naar intern model
    Analysis/
      CatalogHealthAnalyzer.php  <- voert alle regels uit, berekent score
      Contracts/IssueRule.php    <- interface voor alle regels
      Rules/                     <- 1 klasse per issue-regel (9 totaal)
  Models/CatalogItem.php
  Filament/
    Resources/
      CatalogItemResource.php
      CatalogItemResource/
        Pages/ListCatalogItems.php
        Pages/ViewCatalogItem.php
        Widgets/CatalogHealthStats.php
  Console/Commands/ImportCatalogFeed.php

database/
  fixtures/catalog-health-feed-curated-final.json
  migrations/..._create_catalog_items_table.php
```
