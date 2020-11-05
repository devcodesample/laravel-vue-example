# Laravel Vue Example
This project consists of `Laravel` and `Vue` in the backend and frontend respectively.


##Laravel
This app provides REST APIs to create, update, delete, and search therapists in an area.
###System requirements

- PHP >= 7.2
- MySQL / MariaDB
- Apache / Nginx

###Installation instructions

- `cd` into the project directory
- Create an .env file: `cp .env.example .env`
- Install dependencies: `composer update`
- Run migrations: `php artisan migrate`

##Vue
This part of the app integrate the rest APIs and renders a view for the frontend.

###System requirements

- Node >= 10.x
- Nginx

###Installation instructions

- `cd` into the project directory
- Run `npm install`
