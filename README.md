# Vehicle Availability System

This project is a Vehicle Availability System built with Symfony. It allows users to manage vehicle availabilities, perform searches, and manage the vehicle inventory.

## Table of Contents

- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Running Tests](#running-tests)
- [Factories and Dummy Data](#factories-and-dummy-data)

## Installation

### Prerequisites

- PHP 8.0 or higher
- Composer
- MySQL or another supported database

### Steps

1. Clone the repository:

```bash
git clone https://github.com/Laura-NR/test_wikicampers_symfony.git
cd test_wikicampers_symfony
```

2. Install dependencies
composer install

3. Create and configure the .env file:
cp .env .env.local

4. Set up the database:
* php bin/console doctrine:database:create
* php bin/console doctrine:migrations:migrate
* php bin/console doctrine:fixtures:load


## Configuration 

* DATABASE_URL="mysql://root:@127.0.0.1:3306/wikicampers?charset=utf8mb4"
* APP_ENV=dev
* APP_SECRET=your_secret_key

## Usage 

- Starting the Server
php bin/console server:run

Or using Symfony CLI:
symfony server:start

- Accessing the Application
Once the server is running, you can access the application at http://localhost:8000.

## Running Tests

- Setting up the Test Environment
Ensure your .env.test file is correctly configured for your test database.

* DATABASE_URL="mysql://root:@127.0.0.1:3306/test_wikicampers?charset=utf8mb4"
* APP_ENV=test
* APP_SECRET=your_test_secret_key

- Running Tests
To run the tests, execute: php bin/phpunit

## Factories and Dummy Data
This project uses the Zenstruck Foundry to generate factories and dummy data.

