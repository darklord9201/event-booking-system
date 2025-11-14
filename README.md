# Getting started

## Installation

Clone the repository

    git clone https://github.com/darklord9201/event-booking-system.git
Switch to the repo folder

    cd event-booking-system

Install all the dependencies using composer

    composer install
Copy the example env file and make the required configuration changes (Like database) in the .env file

    cp .env.example .env
Generate a new application key

    php artisan key:generate
Migrate the database

    php artisan migrate

Start the local development server

    php artisan serve

## Note
The Postman collection can found inside the **postman** directory along with a environment file.
