## Pames Backend

Backend for a game price comparison platform, built with Laravel.

## Next.js frontend

Frontend created using [Next.js](https://nextjs.org/), [Tailwind CSS](https://tailwindcss.com/)
and [NextAuth](https://next-auth.js.org/). You can find the
repository [here](https://github.com/remzikilnc/pames-front).

## Features
- RESTful API
- [Spatie / Laravel Permission](https://spatie.be/docs/laravel-permission/)

## Needs
- php sodium

## Endpoints

Authentication:
- /api/v1/login
- /api/v1/register
- /api/v1/token/refresh
- /api/v1/forgot-password

Needs authentication:
- /api/v1/reset-password
- /api/v1/email/verification-notification
- /api/v1/logout
- [RESOURCE] /api/v1/**users**

## Installation

> Note: the application does not have a `package.json` since this project purely a REST API that will not use any
> JavaScript or asset builders such as Vite.

1. `cp .env .env.example`
2. `Customize .env file for your own application settings`
2. `composer install`
3. `php artisan key:generate`
3. `php artisan jwt:secret` (generate a secret key that will be used to sign your tokens)
4. `php artisan migrate:fresh --seed`

## Authentication

[JWT](https://jwt.io/) authentication with [tymon/jwt-auth](https://github.com/tymondesigns/jwt-auth)

## [Spatie](https://spatie.be/docs/laravel-permission/)

This application comes with Laravel Spatie installed, with admin and user roles. You can access the
default permissions at database/default/permissions.php file.
