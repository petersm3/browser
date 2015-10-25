<?php

class Database {
    function getConnection() {
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

  -- TABLE `filters`
  -- An accession (fk_properties_id) can belong to several subcategories
  -- Example INSERT:
  -- INSERT INTO filters (fk_properties_id, fk_category_id) VALUES (8, 1);
  -- INSERT INTO filters (fk_properties_id, fk_category_id) VALUES (8, 22);
  -- Example query to find accessions matching the user supplied filters:
  -- SELECT fk_properties_id, COUNT(fk_properties_id) as count_fk_properties_id FROM filters WHERE fk_categories_id IN (<several categories.id seperated by commas>) GROUP BY fk_properties_id HAVING count_fk_properties_id = <total number of categories.id supplied>;

  CREATE TABLE `filters` (
  `id` int(6) NOT NULL auto_increment,
  `fk_properties_id` int(7) NOT NULL,
  `fk_categories_id` INT(2) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (fk_properties_id) REFERENCES properties(id),
  FOREIGN KEY (fk_categories_id) REFERENCES categories(id)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

  -- TABLE `properties`
  -- Properties of 'Image', 'Street Address', 'Photographer', and 'Date' of each accession
  -- Example INSERT:
  -- INSERT
 */
 
/* vim:set noexpandtab tabstop=4 sw=4: */
?>
