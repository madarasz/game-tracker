## Requirements
* php (> v5.6)
* php composer
* node (> v6)
* mysql
## Installation
1. git clone project
2. `npm install` to install npm dependencies *(this will take some time)*
3. `composer install` to install composer dependencies *(might be `php composer install` depending on your installation)*
4. Create `.env` file, copy it from `.env.example` file. Configure DB connection.
5. `php artisan migrate` to create tables. `php artisan db:seed` to seed DB.
6. `npm run dev` to compile js and css assets
7. `php artisan key:generate` to generate cipher key
8. `php artisan serve` to run dev server. Go to [http://localhost:8000](http://localhost:8000) to see application running.