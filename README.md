google-bouffe-bo
================

A Symfony project created on October 3, 2016, 9:53 am.


http://localhost:8000
php app/console server:run

SF2 commands :
- Get list routes : php app/console router:debug
- Create bundle : php app/console generate:bundle

MYSQL Commands
- Connect to database : mysql -u root -p


Doctrine Commands
- Generate entities : php app/console doctrine:generate:entities
- Create entity : php app/console generate:doctrine:entity
- Create database : php app/console doctrine:database:create
- Update database : php app/console doctrine:schema:update --force --dump-sql