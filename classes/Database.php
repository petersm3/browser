<?php
// Database class to create a handle to the MySQL database using PDO
// Exceptions are turned on but there are is no exception catching via
// try/catch blocks in this application (specifically as there are no transactions)
//
// NOTE: Look at the bottom of this file to see all MySQL schema setup statements

class Database {
    function getConnection() {
        // Variables defined in /config/config.php
        $hostname = MYSQL_HOSTNAME;
        $database = MYSQL_DATABASE;
        $username = MYSQL_USERNAME;
        $password = MYSQL_PASSWORD;

        try {
            // Create handle to database
            $dbh = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);

            // Throw PDOExceptions to catch
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Buffered queries, MySQL specific
            $dbh->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

            return $dbh;

        } catch (PDOException $e) {
            error_log("Cannot connect to database : " . $e->getMessage());

            $request = new Lvc_Request();
            $request->setControllerName('error');
            $request->setActionName('view');
            $request->setActionParams(array('error' => '500'));
            // Get a new front controller without any routers,
            // and have it process our handmade request.
            $fc = new Lvc_FrontController();
            $fc->processRequest($request);

            exit;
        }
    }
}

/* MySQL Setup Commands (as MySQL root user)

  Modify MySQL configuration to enforce NOT NULL constraint (restart after change)
  grep -A1 '\[mysqld\]' /etc/mysql/my.cnf 
  [mysqld]
  sql_mode="STRICT_ALL_TABLES"

  create database browser;
  // MYSQL_USERNAME and MYSQL_PASSWORD derived from config/config.php
  CREATE USER 'MYSQL_USERNAME'@'localhost' IDENTIFIED BY 'MYSQL_PASSWORD';
  e.g.,
  CREATE USER 'browser_www'@'localhost' IDENTIFIED BY '**********';
  GRANT SELECT on `browser`.* TO `browser_www`@`localhost`;

  -- TABLE categories
  -- `priority` implies the priority the category is listed in Navigation, e.g.,
  -- Creator = 1, Photographer = 2, Style Period = 3, Work Type = 4, etc.
  -- Example INSERT:
  -- INSERT INTO categories (priority, category, subcategory) VALUES (1, 'Creator', 'Lawrence, Ellis Fuller');
  -- INSERT INTO categories (priority, category, subcategory) VALUES (3, 'Style Period', 'Art Deco');

  CREATE TABLE `categories` (
  `id` int(2) NOT NULL auto_increment,
  `priority` INT(2) NOT NULL,
  `category` VARCHAR(256) NOT NULL,
  `subcategory` VARCHAR(256) NOT NULL,
  `comment` VARCHAR(1024),
  PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

  -- INDEX `categories`
  -- mysql> show warnings;
  --  +---------+------+---------------------------------------------------------+
  --  | Level   | Code | Message                                                 |
  --  +---------+------+---------------------------------------------------------+
  --  | Warning | 1071 | Specified key was too long; max key length is 767 bytes |
  --  | Warning | 1071 | Specified key was too long; max key length is 767 bytes |
  --  +---------+------+---------------------------------------------------------+
  -- http://mechanics.flite.com/blog/2014/07/29/using-innodb-large-prefix-to-avoid-error-1071/
  CREATE INDEX categoryIndex ON categories(category);
  CREATE INDEX subcategoryIndex ON categories(subcategory);

  -- TABLE `properties`
  -- Properties of 'Image', 'Street Address', 'Photographer', and 'Date' of each accession
  -- NOTE: `id` will suffice as the unique identifier; better served by a unique accession
  --       e.g., Some type of digital object identifier (DOI)
  -- For images we're using randomly generated md5sum strings
  -- Example INSERT:
  -- INSERT INTO properties ('image', 'street_address', 'photographer', 'date') VALUES ('a14a23885b4730771459a530c79bed45', '123 NorthWest St.', null, '2013');

  CREATE TABLE `properties` (
  `id` int(7) NOT NULL auto_increment,
  `image` VARCHAR(32) NOT NULL,
  `street_address` VARCHAR(255) NOT NULL,
  `photographer` VARCHAR(255),
  `date` VARCHAR(255),
  PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

  -- INDEX `properties`
  -- Not needed as primary key (id) is automatically indexed and what queries are comparing against
  -- http://stackoverflow.com/questions/1071180/is-the-primary-key-automatically-indexed-in-mysql

  -- TABLE `filters`
  -- An accession (fk_properties_id) can belong to several subcategories
  -- Example INSERT:
  -- INSERT INTO filters (fk_properties_id, fk_categories_id) VALUES (8, 1);
  -- INSERT INTO filters (fk_properties_id, fk_categories_id) VALUES (8, 22);
  -- Example query to find accessions matching the user supplied filters:
  -- SELECT fk_properties_id, COUNT(fk_properties_id) as count_fk_properties_id FROM filters WHERE fk_categories_id IN (<several categories.id seperated by commas>) GROUP BY fk_properties_id HAVING count_fk_properties_id = <total number of categories.id supplied>;

  CREATE TABLE `filters` (
  `id` int(7) NOT NULL auto_increment,
  `fk_properties_id` int(7) NOT NULL,
  `fk_categories_id` INT(2) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (fk_properties_id) REFERENCES properties(id),
  FOREIGN KEY (fk_categories_id) REFERENCES categories(id)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

  -- INDEX `filters`
  CREATE INDEX fk_properties_idIndex ON filters(fk_properties_id);
  CREATE INDEX fk_categories_idIndex ON filters(fk_categories_id);

  -- TABLE `attributes`
  -- N attributes to 1 accession (fk_properties_id)
  -- Example INSERT:
  -- INSERT INTO attributes (fk_properties_id, name, value) VALUES (8, 'color', 'red');

  CREATE TABLE `attributes` (
  `id` int(9) NOT NULL auto_increment,
  `fk_properties_id` int(7) NOT NULL,
  `name` VARCHAR(256) NOT NULL,
  `value` VARCHAR(1024) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (fk_properties_id) REFERENCES properties(id)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

  -- INDEX `attributes`
  CREATE INDEX fk_properties_idIndex ON attributes(fk_properties_id);

 */

/* vim:set noexpandtab tabstop=4 sw=4: */
?>
