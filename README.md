<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About this project

Project is a api for web application.

Project using package Laravel-permission allows me to manage user permissions and roles in a database, for detail click
here [Laravel Permission Spatie](https://spatie.be/docs/laravel-permission/v5/introduction)

Develop by Tran Ngoc Phuoc, BAP Software company Da Nang

Mail: phuoctn412@gmail.com

Phone: 0984641362

# IMPORTANT: You must do this after you clone my project

1. Open your terminal in project directory
2. Make .env file:
   `
   cp .env.example .env
   `
3. Run composer install to install dependency:
   `
   composer install
   `

   If you not install composer, click here to read document of composer here [Composer](https://getcomposer.org)
4. Generate key for run this application
   `
   php artisan key:generate
   `
5. Run seeder database:
   `
   php artisan migrate:fresh --seed
   `
6. User account in project
   `Email: superadmin@gmail.com, Password: superadmin@gmail.com
   `
7. Run queue job for sending email

   Open another terminal and enter: `php artisan queue:work`
8. Add ENV Variable into `.env` file for working api request from reactjs

   `SESSION_DOMAIN=localhost`

   `SANCTUM_STATEFUL_DOMAINS=localhost`
