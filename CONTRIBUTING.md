# P8_Openclassroom
Hey and thank you for your help.
Before edit or add files you need to read this documentation.

## Clone
1) Make a clone with `https://github.com/lionneclement/P8_Openclassroom.git` and `cd P8_Openclassroom`
2) Install composer with `composer install`
3) Create database with `php bin/console doctrine:database:create`
4) Create table with `php bin/console doctrine:schema:create`
5) Create data with `php bin/console doctrine:fixtures:load --group=app`

   By default a user was created with email=user@gmail.com and password=password and a admin with email=admin@gmail.com and password=password
   
6) Run server with `bin/console server:run` or `symfony server:start`and go to localhost with port 8000

## Pull request
1) Before create you new features you need to create your issue and your branch.
2) Explain in your issue what you will do, and add some labels.

## Code quality
1) We follow the PHP Standards Recommendations, you must follow these recommendations to contribute [here](https://www.php-fig.org/psr/)
2) We use Symfony 4.4 and Php 7.4.5, so follow the right documentation.
3) You need to create some test for you new features.

## Testing
1) We use phpUnit so read the documentation [here](https://phpunit.de/)
2) We have create fixture for testing, create data with `php bin/console doctrine:fixtures:load --group=testing`.
3) Don't forget to create a code coverage with `php bin/phpunit --coverage-html html`.

If you have any questions send me an mail to lionneclement@gmail.com.
