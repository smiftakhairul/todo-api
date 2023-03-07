# Todo API
Todo API Project for Managing Todo Lists.

## Installation
Install the project in your preferred directory and run the following commands.

```bash
git clone https://github.com/smiftakhairul/todo-api.git
cd todo-api

cp .env.example .env
composer install
php artisan key:generate
php artisan config:cache # clear config cache
php artisan migrate # change DB credentials in .env before running it.
```
## Usage
Make some dummy user entries using seeder.

```bash
php artisan db:seed --class=UserSeeder
```
Test features of the API's by running following command.
```bash
php artisan test
```
Serve the project.
```bash
php artisan serve
```
You can test the project using the following user credentials.
```bash
email: admin@demo.com
password: 12345678
```
