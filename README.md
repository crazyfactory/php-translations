# php-translations 
##Classes for the TranslatorApp
- ```TranslationManagerBase```: Handles all translations stored in the database. retrieves their values
- ```TranslationCacheBase```: Retrieves translation by id or key, can preload scopes. scopes are retrieved from a generated php-file.
- ```TranslationValidator```: Verify value before insert or update in database.
## Running tests
This packages use Codeception testing framework (http://codeception.com/). To run tests:
- get dependencies ready:
    ```composer install```
- run ALL tests:
    ```php /vendor/codeception/codeception/codecept run```
