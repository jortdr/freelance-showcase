# Freelance Website (HBO-ICT Showcase Project)

This is the repository of my Showcase Project for HBO-ICT @ Windesheim.

## Installation

Follow the steps below to install and run.

### Prerequisites

- Docker
- PHP 8.3
- Composer
- Docker Compose
- NPM

### Steps

1. **Clone the repository**

Either download the repository as a ZIP file or clone it using Git.

2. **Copy the example env file and configure it:**

    ```sh
    cp .env.example .env
    ```

3. **Composer Install and NPM**

    ```sh
    composer install
    npm install
    ```

4. **Start the Docker containers:**

    ```sh
    ./vendor/bin/sail up -d
    ```

5. **Generate the application key:**

    ```sh
    ./vendor/bin/sail artisan key:generate
    ```

6. **Run the database migrations:**

    ```sh
    ./vendor/bin/sail artisan migrate
    ```

7. **Seed the database:**

    ```sh
    ./vendor/bin/sail artisan db:seed
    ```

Congrats, if you did it correctly, you should be able to access the website using localhost.

If you have Laravel Valet, you can also `park` the project and access it through the `.test` domain specified in the
`.env` file.

## Running Tests

### Cypress

1. **Start the application:**

    ```sh
    ./vendor/bin/sail up -d
    ```

2. **Open Cypress:**

    ```sh
    npx run cypress
    ```

### Laravel (PHPUnit)

1. **Run the tests:**

    ```sh
    ./vendor/bin/sail artisan test
    ```

## Additional Commands

- **Stop the Docker containers:**

    ```sh
    ./vendor/bin/sail down
    ```
