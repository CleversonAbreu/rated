
# rated 

Application created as an activity for the possibility of a vacancy as a laravel developer.
  
## How to install

- git clone ...
- cd rated
- sudo docker-compose up

- open new terminal
- cd rated
- docker-compose exec app sh
- composer install 
- cp .env.example .env
- php artisan key:generate
- php artisan migrate
- php artisan db:seed


## How to use tests

- open project in vscode
- open file ProductTest.php and edit with valid email 
(inserted in the database) in token method line 16
- open new terminal
- cd rated
- ./vendor/bin/phpunit --filter ProductTest

- open file UserTest.php and edit with valid email 
(inserted in the database) in token method line 18
- ./vendor/bin/phpunit --filter UsertTest

