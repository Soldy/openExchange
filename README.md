t ptrtest
phptest-1.2


.env

```php

DB_CONNECTION=mysql
DB_HOST=host
DB_PORT=3306
DB_DATABASE={database}
DB_USERNAME={userName}
DB_PASSWORD={password}

```


##http server option 1. 


apache vhost.conf

```apache


<VirtualHost *:80>
    ServerAdmin webmaster@exchange.com
    ServerName exchange.com
    DocumentRoot /var/www/exchange/public
    <Directory "/var/www/exchange/public">
            Options Indexes FollowSymLinks MultiViews
            AllowOverride All
            Order allow,deny
            allow from all
            Require all granted
            ReWriteEngine On
    </Directory>
    ErrorLog ${APACHE_LOG_DIR}/error.exchange.vm.log
    CustomLog ${APACHE_LOG_DIR}/access.exchange.vm.log combined
</VirtualHost>


```

##https server option 2.

```bash

php artisan serve --port=8080

```






