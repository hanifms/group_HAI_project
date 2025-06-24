# Travel Booking Application

A robust travel booking platform built with Laravel 12, featuring role-based access control, travel package management, booking system, and review functionality.

## Table of Contents

- [Features](#features)
- [System Requirements](#system-requirements)
- [Installation](#installation)
- [Project Structure](#project-structure)
- [User Roles and Permissions](#user-roles-and-permissions)
- [Database Schema](#database-schema)
- [Security Implementations](#security-implementations)
- [Testing](#testing)
- [Screenshots](#screenshots)
- [License](#license)

## Features

### For Users
- **Account Management**: Register, login, and manage profile details
- **Travel Package Browsing**: View all available travel packages with details
- **Booking System**: Book travel packages and track booking status
- **Reviews & Ratings**: Leave reviews and ratings for completed travels
- **Booking History**: View history of all bookings with status information

### For Administrators
- **User Management**: View, edit, and manage user accounts
- **Travel Package Management**: Create, update, and delete travel packages
- **Booking Management**: Update booking statuses and view all bookings
- **Review Monitoring**: View all user reviews across the platform

## System Requirements

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL or SQLite
- Laravel requirements (BCMath, Ctype, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML)

## Installation

Follow these steps to set up the project locally:

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/travel-booking-app.git
   cd travel-booking-app
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install NPM dependencies and build assets**
   ```bash
   npm install && npm run dev
   ```

4. **Set up environment file**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure the database**
   
   Edit the `.env` file to set your database connection details:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=travel_booking
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   
   For SQLite (simpler setup):
   ```
   DB_CONNECTION=sqlite
   # Comment out or remove other DB_* entries
   ```
   Then create the database file:
   ```bash
   touch database/database.sqlite
   ```

6. **Run migrations and seed the database**
   ```bash
   php artisan migrate --seed
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

8. **Access the application**
   
   Open your browser and navigate to `http://localhost:8000`

### Default Admin Account

After seeding the database, you can use the following credentials to login as admin:
- Email: admin@example.com
- Password: password

## Project Structure

The application follows Laravel's MVC architecture:

### Key Directories:
- **app/Models/**: Contains Eloquent models for User, TravelPackage, Booking, Review, etc.
- **app/Http/Controllers/**: Contains controllers separated by user role (Admin/User)
- **app/Http/Middleware/**: Contains middleware like AdminMiddleware for route protection
- **resources/views/**: Contains Blade templates organized by feature and user role
- **routes/web.php**: Contains all web routes structured by user role
- **database/migrations/**: Contains database schema definitions
- **database/seeders/**: Contains data seeders for initial application data

## User Roles and Permissions

### Regular Users
- Can view and book travel packages
- Can view their booking history
- Can leave reviews for completed travels
- Cannot access admin features

### Administrators
- Full CRUD access to user management
- Full CRUD access to travel package management
- Can update booking statuses
- Can view all user reviews

## Database Schema

The application uses several interrelated tables:

- **users**: Stores user account information
- **roles**: Defines user roles (admin, user)
- **travel_packages**: Stores travel package details
- **bookings**: Records bookings made by users
- **reviews**: Stores user reviews for completed bookings

Key relationships:
- User has many Bookings
- User has many Reviews
- Booking belongs to a User and a TravelPackage
- Review belongs to a User and a Booking

## Security Implementations

- **Authentication**: Secure authentication via Laravel Jetstream
- **Authorization**: Role-based access control for protected routes
- **CSRF Protection**: Protection against cross-site request forgery
- **SQL Injection Protection**: Use of Eloquent ORM and prepared statements
- **XSS Protection**: Blade template engine escapes output by default

## Testing

The application includes a comprehensive test suite to ensure all features work correctly and to catch regressions during development.

### Test Structure

- **Feature Tests**: Test the application as a user would interact with it
  - `tests/Feature/Admin/`: Tests for admin functionality
  - `tests/Feature/Bookings/`: Tests for booking-related features
  - `tests/Feature/Reviews/`: Tests for review functionality
  - `tests/Feature/TravelPackages/`: Tests for travel package features
- **Unit Tests**: Test individual components in isolation
  - `tests/Unit/Models/`: Tests for model relationships and methods
  - `tests/Unit/Helpers/`: Tests for helper functions

### Setting Up the Testing Database

We've included a helper script to set up the testing database:

just run the below

```bash
php setup_test_db.php
```

This script:
1. Creates a dedicated MySQL database for testing (`mytravelv2_testing`)
2. Runs migrations on the testing database
3. Seeds the database with test data

### Running Tests

After setting up the testing database, you can run the tests:

```bash
# Run all tests
php artisan test

# Run a specific test file
php artisan test --filter=UserTest

# Run tests with coverage report
php artisan test --coverage
```

### Testing Environment Configuration

The testing environment is configured in `.env.testing`. By default, it uses MySQL to ensure compatibility with the ENUM fields in our schema, particularly in the `bookings` table.

### Common Testing Issues

If you encounter issues with the ENUM fields in the `status` column of the `bookings` table, make sure:

1. You're using MySQL for testing (SQLite doesn't support ENUM)
2. The testing database was created correctly using the setup script
3. The migrations ran successfully

You can check the status of the bookings table ENUM field by running:

```bash
php check_bookings_enum.php
```

- **Unit Tests**: Test individual components in isolation
  - `tests/Unit/Models/`: Tests for model relationships and methods
  - `tests/Unit/Helpers/`: Tests for helper classes like SecurityHelper

### Running Tests

Run all tests:
```bash
php artisan test
```

Run specific test files or directories:
```bash
# Run all feature tests
php artisan test --path=tests/Feature

# Run specific test file
php artisan test tests/Feature/Reviews/ReviewCreationTest.php

# Run tests with specific filter
php artisan test --filter=test_users_can_submit_review_for_completed_booking
```

### Using Tests During Development

1. **Test-Driven Development**:
   - Write tests for new features before implementing them
   - Run `php artisan test --filter=YourNewTest` to see it fail
   - Implement the feature until the test passes

2. **Regression Testing**:
   - After making changes, run `php artisan test` to ensure nothing was broken
   - Focus on specific areas: `php artisan test --path=tests/Feature/TravelPackages`

3. **Debugging Failed Tests**:
   - Use `php artisan test --verbose` for detailed output
   - Add `$this->withoutExceptionHandling();` at the beginning of test methods to see full exception traces

4. **Database Testing**:
   - Tests use MySQL database to ensure compatibility with production environment
   - Tests automatically reset the database between test cases
   - Use factories to create test data: `User::factory()->withUserRole()->create()`
   
5. **Setting Up the Testing Environment**:
   ```bash
   # Create and set up the test database
   php setup_test_db.php
   ```
   - Ensure the `.env.testing` file has the correct database configuration
   - Make sure your MySQL server is running
   - Verify phpunit.xml has the correct DB_CONNECTION and DB_DATABASE settings

### Key Test Files

- **AdminBookingManagementTest**: Tests booking management by administrators
- **AdminTravelPackageManagementTest**: Tests travel package CRUD operations
- **AdminUserManagementTest**: Tests user management functionality
- **SecurityHelperTest**: Tests the security utility methods
- **ReviewCreationTest**: Tests the review submission process
- **TravelPackageListingTest**: Tests travel package listing and visibility

### Creating New Tests

```bash
# Create a new test file
php artisan make:test NewFeatureTest --feature

# Create a new unit test
php artisan make:test Models/NewModelTest --unit
```

Follow these best practices when creating tests:
- Name test methods descriptively (`test_users_can_view_their_own_reviews`)
- Test both successful and failure scenarios
- Use test data factories to create consistent test data
- Keep tests isolated (don't rely on data created by other tests)
- Add comments explaining complex assertions

## Screenshots

(Add screenshots of your application here)

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
