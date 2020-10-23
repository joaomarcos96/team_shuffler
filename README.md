# Team Shuffler

Team Shuffler helps you create players and shuffle them to create teams.

## Getting started

Run the commands below to get the app up and running

* Copy `.env.example` to `.env` and fill it with your own credentials:
        
        $ cp .env.example .env

* Install the dependencies

        $ composer install

* Migrate the database (create it first, it's not created yet)

        $ php artisan migrate

* Run the app

        $ php artisan serve
