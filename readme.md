## Installation

1. Clone the repo.
2. cd into the folder.
3. Run `composer install`
4. Create a `.env` file from `.env.example`
5. Set `DB_HOST` to `db_mysql_likes` (or as per docker composer setting)
6. Set `DB_DATABASE`, `DB_USERNAME` and `DB_PASSWORD`
7. Run `php artisan key:generte`
9. Add `EMAIL_SERVICE_URL` value to `http://localhost:44679` (base URL for email service) in `.env` file
8. Run `docker-compose up -d`
9. Bash into container and run `php artisan migrate`
10. Run test by `vendor/bin/phpunit`

## Usage
1. `http://localhost:44679/api/likes` accepts `POST` with data `{"user_id": 1}`

