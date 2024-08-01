
#API

## API Documentation

### Requirments
-   At least php8.0 on your machine
-   Composer

<!-- FERRAMENTAS QUE É USADO PEST, AUDITLOG,  -->

### Starting in this project

1. Clone this project
2. Run the `composer install` command
3. Run the `composer update` command
4. Run the `cp .env.example .env` command
5. Run the `docker network create dashboard-maps-polygon` command
6. Run the `docker compose up` command
7. Run the `docker exec -it dashboard-maps-polygon_api php artisan key:generate` command
8. Run the `docker exec -it dashboard-maps-polygon_api php artisan storage:link` command
8. Run the `docker exec -it dashboard-maps-polygon_api php artisan migrate:fresh --seed` command



