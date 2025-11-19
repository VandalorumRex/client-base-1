# JSON API для работы с данными, хранящимися в XML-файле.

API должно соответствовать спецификации JSON:API: https://jsonapi.org/

Данные хранятся в XML-файле в формате «Яндекс Недвижимость»: https://yandex.ru/support/realty/requirements/requirements-sale-housing.html

Реализована возможность получать, изменять, добавлять, удалять данные из файла.

## Installation

1. ```bash
git clone https://github.com/VandalorumRex/client-base-1.git cb1
cd cb1
```
2. If Composer is installed globally, run

```bash
composer install
```

You can now either use your machine's webserver to view the default home page, or start
up the built-in webserver with:

```bash
bin/cake server -p 8765
```

Then visit `http://localhost:8765` to see the welcome page.

## Update

Since this skeleton is a starting point for your application and various files
would have been modified as per your needs, there isn't a way to provide
automated upgrades, so you have to do any updates manually.

## Configuration

Read and edit the environment specific `config/app_local.php` and set up the
`'Datasources'` and any other configuration relevant for your application.
Other environment agnostic settings can be changed in `config/app.php`.

## Layout

The app skeleton uses [Milligram](https://milligram.io/) (v1.3) minimalist CSS
framework by default. You can, however, replace it with any other library or
custom styles.

## Apache config

```conf
<VirtualHost *:80>  
    ServerAdmin admin@example.ru  
    DocumentRoot "G:/projects/hh/client.base/cb1/webroot"  
    ServerName cb1.local  
    <Directory "G:/projects/hh/client.base/cb1/webroot/">  
        Options Indexes MultiViews FollowSymLinks  
        AllowOverride All  
        Require all granted  
    </Directory>  
</VirtualHost>
```
