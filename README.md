## Project Setup


1. Clone repository locally;
1. Run `composer install` to install Composer dependencies;
1. Run `cp .env.example .env` to create your local environment file;
1. In .env file change SMTP config to correct email.
1. Run `php artisan migrate --seed` to migrate database and seed it
1. Run `php artisan storage:link` to creates a symbolic link
1. Run `npm install` to install NPM dependencies;
1. Run `npm run build` to build/compile frontend assets;
1. Run command `/usr/local/bin/php /mentors/project/directory/artisan schedule:run >> /dev/null 2>&1` to run scheduled tasks every minute (Check if there is queued emails)

