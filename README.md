# Laravel API Setup

This is the backend API for the project, powered by Laravel and using SQLite. The API handles authentication with Laravel Sanctum and is pre-configured to include a test user for ease of use.

You can find the frontend repository here: [Frontend Repository](https://github.com/safal123/interview-vue-client)

Follow the instructions to set up the api.

## Prerequisites

Ensure you have the following installed on your machine:

- **PHP** (v7.3 or higher)
- **Composer** (v2.x or higher)

---

## Backend (Laravel API) Setup

### 1. Clone the Repository

To clone the Laravel API repository, run the following command:

```bash
git clone https://github.com/safal123/laravel-interview-api.git

cd interview-api

composer install

cp .env.example .env

touch database/database.sqlite

#Add the following to your .env file

DB_CONNECTION=sqlite

SESSION_DOMAIN=localhost

php artisan key:generate

php artisan migrate --seed

php artisan serve
```
