# Laravel MFA Authentication API

## Project Overview
This project is a Laravel-based REST API for multi-factor authentication (MFA). It includes user authentication, customer management, and secure API endpoints.

---
## Setup Instructions

### Prerequisites
Ensure you have the following installed:
- PHP (>= 8.0)
- Composer
- MySQL
- Laravel
- Git

### Installation Steps
1. **Clone the repository:**
   ```sh
   git clone https://github.com/akshayjaggi146/mfa-api.git
   cd mfa-api
   ```

2. **Install dependencies:**
   ```sh
   composer install
   ```

3. **Set up environment variables:**
   ```sh
   cp .env.example .env
   ```

4. **Generate application key:**
   ```sh
   php artisan key:generate
   ```

5. **Configure Database:**
   - Update `.env` with your database credentials:
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=mfa_db
     DB_USERNAME=root
     DB_PASSWORD=
     ```
   - Run migrations:
     ```sh
     php artisan migrate --seed
     ```

6. **Set up Mail Configuration:**
   - Update `.env` with mail credentials:
     ```env
     MAIL_MAILER=smtp
     MAIL_HOST=smtp.mailtrap.io
     MAIL_PORT=2525
     MAIL_USERNAME=your_username
     MAIL_PASSWORD=your_password
     MAIL_ENCRYPTION=tls
     MAIL_FROM_ADDRESS=no-reply@example.com
     MAIL_FROM_NAME="MFA API"
     ```

7. **Run the application:**
   ```sh
   php artisan serve
   ```

---
## API Endpoints

### Authentication
- **Register:** `POST /api/register`
- **Login:** `POST /api/login`
- **Logout:** `POST /api/logout`
- **User Profile:** `GET /api/user`

### Customers
- **Get All Customers:** `GET /api/customers`
- **Create Customer:** `POST /api/customers`
- **Get Customer by ID:** `GET /api/customers/{id}`
- **Update Customer:** `PUT /api/customers/{id}`
- **Delete Customer:** `DELETE /api/customers/{id}`

---
## Running Test Cases

1. **Set up testing environment:**
   ```sh
   cp .env .env.testing
   ```

2. **Update `.env.testing` database configuration:**
   ```env
   DB_CONNECTION=mysql
   DB_DATABASE=mfa_test_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

3. **Run tests:**
   ```sh
   php artisan test
   ```

---
## Deployment
- Use a production database and update `.env` accordingly.
- Set `APP_ENV=production` and `APP_DEBUG=false`.
- Configure a web server (Apache/Nginx) to serve the Laravel application.



