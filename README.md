## App Installation

Project has been created a per requirements and the example CSV sent was added to the public directory. For ease of use the app uses an SQLite database and provides quick console commands to quicky create an SQLite database to use. Steps are as follows

- Clone Repository
- run "cp .env.example .env"
- run "composer install"
- run "php artisan key:generate"
- run "./vendor/bin/phpunit" to run the created unit tests
- run "php artisan sqlite:setup" // This creates an SQLite database for use on the app with the appropriate permissions so you dont have to manually do it
- run "php artisan migrate" to migrate users table
- Finally run "php artisan import:csv" to import csv. It asks for the "absolute path" to the file needed to upload but provides the default of the file initially provided currently on the public directory
- Use the view data option asked to have a list of the extracted data display on the cli
- use your favorite database client to connect to the MySQlite database and crosscheck the data populated.

Please reach out for further questions.
