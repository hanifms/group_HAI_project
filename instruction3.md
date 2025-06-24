# How to Run Tests

This project uses Laravel and has two main folders: `original` and `enhanced`. All collaborative work and testing should be done in the `enhanced` folder.

## Steps to Run Tests

1. **Set Up the Test Environment**

    Navigate to the `enhanced` folder and run:

    ```bash
    php setup_test_db.php
    ```

    This will set up the test database environment.
    Check if the database 'mytravelv2_testing' has been created in phpmyadmin dashboard 

2. **Generate App Key for Testing Environment**

    Generate a secure application key for your testing environment:

    ```bash
    php artisan key:generate --env=testing
    ```

    This ensures your application encryption is properly configured for tests.

3. **Run the Tests**

    Still inside the `enhanced` folder, run:

    ```bash
    php artisan test
    ```

    This will execute all automated tests.

4. **Before Pushing Changes**

    Only push to the `main` branch when **all tests pass** to ensure code quality and stability.
    Before push to main, send screenshots of the successful tests in the whatsapp group

    # Do Not Delete Any Test Files, They Are There So Our Project Will Not Break

   *make sure before pushing your tests results looks like this

   <img width="263" alt="all good" src="https://github.com/user-attachments/assets/6d46b3ba-4d87-41a8-aab7-d71ed2280bd3" />

   *and not this

   <img width="266" alt="all bad" src="https://github.com/user-attachments/assets/a2a1a859-df70-4ecc-be2c-7f56c12ed97c" />

> **Note:** Always work and run tests inside the `enhanced` folder, not the `original` folder.
