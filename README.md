Orgchart
========
Orgchart is an company org chart built for PHP 5.5 and MySQL 5. It was built for a code challenge project.

Uses Doctrine DBAL for accessing the database, and Twig for its templates. The UI is based on Bootstrap 3 and FontAwesome.

Getting it running locally
--------------------------
1. `curl -sS https://getcomposer.org/installer | php`
2. `composer install`
3.  Set up environment variables
5.  Import data into database
4. `php -S localhost:8000 web/index.php`

Required Example Env variables
---------------------
```
export DB_NAME=database_name
export DB_HOST=localhost
export DB_USER=user
export DB_PASSWORD=password
export DEBUG=true
```

MySQL Database Schema
---------------
```
CREATE TABLE `employees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT NULL,
  `bossId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `bossId` (`bossId`)
)
```

Application Structure
---------------------
- web - includes view and entry point (index.php)
  - views (Contains the Twig templates, including the main layout file.)
- lib - standalone classes for orgchart/employee/pagination
- vendor - Contains the dependencies managed by composer.
            