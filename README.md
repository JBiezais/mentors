## Enviroment Setup

1. PHP 8.2
1. MySQL 8.0
1. Nginx 
1. Node.js 19.^

## Project Setup


1. Clone repository locally;
1. Run `composer install` to install Composer dependencies;
1. Run `cp .env.example .env` to create your local environment file;
1. In .env file change SMTP config to correct email and APP_LINK to server domain.
1. Run `php artisan migrate --seed` to migrate database and seed it
1. Create new directory `mkdir storage/app/image/cropped`
1. Run `php artisan storage:link` to creates a symbolic link
1. Run `npm install` to install NPM dependencies;
1. Run `npm run build` to build/compile frontend assets;
1. Run `php artisan key:gen` to generate ne application key
1. Run command `/usr/local/bin/php /mentors/project/directory/artisan schedule:run >> /dev/null 2>&1` to run scheduled tasks every minute (Check if there is queued emails)

