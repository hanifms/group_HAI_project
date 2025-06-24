# How to Run Tests

This project uses Laravel and has two main folders: `original` and `enhanced`. All collaborative work and testing should be done in the `enhanced` folder.

## Steps to Run Tests

1. **Set Up the Test Environment**

    Navigate to the `enhanced` folder and run:

    ```bash
    php setup_test_db.php
    ```

    This will set up the test database environment.

2. **Run the Tests**

    Still inside the `enhanced` folder, run:

    ```bash
    php artisan test
    ```

    This will execute all automated tests.

3. **Before Pushing Changes**

    Only push to the `main` branch when **all tests pass** to ensure code quality and stability.
    Before push to main, send screenshots of the successful tests in the whatsapp group

    # Do Not Delete Any Test Files, They Are There So Our Project Will Not Break

> **Note:** Always work and run tests inside the `enhanced` folder, not the `original` folder.