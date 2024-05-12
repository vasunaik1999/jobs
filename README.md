# Laravel + Vue Quasar Project

This project is a combination of a Laravel backend and a Vue Quasar frontend. It serves as a template for building web applications using the Laravel framework for the backend and Vue Quasar for the frontend.

## Prerequisites

Before you begin, ensure you have met the following requirements:

- [PHP](https://www.php.net/downloads)
- [Composer](https://getcomposer.org/download/)
- [Node.js](https://nodejs.org/)
- [NPM](https://www.npmjs.com/get-npm)
- [Git](https://git-scm.com/downloads)
- [MySQL](https://www.mysql.com/downloads/)
- [Quasar](https://quasar.dev/)

## Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/vasunaik1999/jobs.git

2. Navigate to the project directory:

  ```bash
  cd jobs_backend

3. Install Laravel dependencies:

  ```bash
  composer install

4. Copy the .env.example file to .env:

  ```bash
  cp .env.example .env

5. Generate the application key:
 
  ```bash
  php artisan key:generate

6. Set up your MySQL database in the .env file:

  ```bash
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=your_database_name
  DB_USERNAME=your_database_username
  DB_PASSWORD=your_database_password

7. Migrate the database:

  ```bash
  php artisan migrate

8. Navigate to the frontend directory:
  ```bash
  cd jobs_frontend

9. Install Quasar CLI globally by running the following command in your terminal or command prompt:

  ```bash
  npm install -g @quasar/cli

10. Install frontend dependencies:
  ```bash
  npm install

11. Start the development server:
   ```bash
  quasar dev

## Usage
To start using the application, follow these steps:

Visit http://localhost:9000 in your web browser to access the frontend.
