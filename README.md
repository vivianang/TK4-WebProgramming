# TokoPOS
Simple Point-of-Sales for Group Assignment 

# How to Start
## Docker
* `docker-compose up -d`
* Access from `127.0.0.1:8000`
## Non-Docker
* Change environment variable first (DB Settings, etc)
* `composer install`
* `php artisan migrate`
* `php artisan db:seed`
* `php artisan serve --host=0.0.0.0`

# Credentials
```
Admin:
    Username: admin@tokopos.id
    Password: admintokopos
Staff:
    Username: staff@tokopos.id
    Password: stafftokopos
```
