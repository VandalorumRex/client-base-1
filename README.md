# JSON API для работы с данными, хранящимися в XML-файле.

API должно соответствовать спецификации JSON:API: https://jsonapi.org/

Данные хранятся в XML-файле в формате «Яндекс Недвижимость»: https://yandex.ru/support/realty/requirements/requirements-sale-housing.html

Реализована возможность получать, изменять, добавлять, удалять данные из файла.

## Installation

1. Склонируйте репу
```bash
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


## Configuration

Откройте файл с админскими правами

### Windows:

```cmd
C:\Windows\System32\drivers\etc\hosts
```

### Linux или MacOS

```cmd
/etc/hosts
```

пропишите и сохраните:

```cmd
127.0.0.1       cb1.local
```

### Apache config

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
## Swagger (Описание команд)

```url
http://cb1.local/swagger/index.html
```
