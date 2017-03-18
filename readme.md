## Requirements
* php
* php composer
* node (> v6), npm
* mysql
## Installation
1. git clone project
2. `npm install` *(this will take some time)*
3. `composer install` *(might be `php composer install` depending on your installation)*
4. Create `.env` file, copy it from `.env.example` file. Configure DB connection.
5. `php artisan migrate` to create tables. `php artisan db:seed` to seed DB.
6. `npm run`
