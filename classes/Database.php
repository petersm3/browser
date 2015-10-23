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

 // `priority` implies the priority the category is listed in Navigation, e.g.,
 // Creator = 1, Photographer = 2, Style Period = 3, Work Type = 4, etc.
 CREATE TABLE `category_filters` (
  `id` int(2) NOT NULL auto_increment,
  `priority` int(2) NOT NULL,
  `category` varchar(256) NOT NULL,
  `filter` varchar(256) NOT NULL,
  `comment` varchar(1024),
  PRIMARY KEY  (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

 Example insert
 INSERT INTO category_filters (priority, category, filter) VALUES (1, 'Creator', 'Lawrence, Ellis Fuller');
 INSERT INTO category_filters (priority, category, filter) VALUES (3, 'Style Period', 'Art Deco');
*/
 
/* vim:set noexpandtab tabstop=4 sw=4: */
?>