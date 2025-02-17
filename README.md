# Translation Management Service

## Setup and Installation

### Using Docker
- Ensure you have Docker and Docker Compose installed.
- Navigate to the project root directory and run:
   ```sh
   docker-compose up -d --build

- Run migrations and seed the database:
    docker exec -it translation-app php artisan migrate --seed
- Generate application key:
    docker exec -it translation-app php artisan key:generate
- Copy .env.example to .env and configure database credentials.
- The application should now be running at http://localhost:8000.

### Manual Setup (Without Docker)
- Install PHP, Composer, and MySQL.

- Clone the repository and navigate to the project directory:
    git clone <repo-url>
    cd translation-management
- Install dependencies:
    composer install
- Copy .env.example to .env and configure database credentials.
- Run migrations and seed data:
    php artisan migrate --seed
- Generate application key:
    php artisan key:generate
- Start the application:
    php artisan serve
- Log in and get an API token:
    curl -X POST http://localhost:8000/api/login -H "Content-Type: application/json" -d '{"email": "text@example.com", "password": "password"}'
- To run tests, execute:
    php artisan test

- Postman collection is on root.
