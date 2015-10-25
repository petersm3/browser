# Summary
Image and cultural properties browser
# Setup
## MySQL
* View comments at bottom of: https://github.com/petersm3/browser/blob/master/classes/Database.php
* Modify `my.cnf` to enforce `sql_mode="STRICT_ALL_TABLES"` for primary/foriegn key InnoDB constraints.
* CREATE DATABASE
  * `browser`
* CREATE USER and GRANTS
  * `browser_www`
* CREATE TABLES
  * `categories`
  * `properties`
  * `filters`
  * `attributes`
* CREATE INDEX
  * `categories`
  * `filters`
  * `attributes`

### Populate database
* Copy https://github.com/petersm3/browser/blob/master/tools/credentials.php-template to `credentials.php`
  * Configure values; database user must have a GRANT to perform INSERT.
* Run https://github.com/petersm3/browser/blob/master/tools/populate_database.php
  * e.g., `10000` accessions
  * Only run this script once. If you need to re-run then first drop the tables and recreate.
    * This is necessary as the script assumes a certain order/offset from the auto increment primary keys.

## Apache

### VHOST

## Application

### Content delivery network (CDN)
* Simulate CDN by deploying "Dynamic Dummy Image Generator" (http://dummyimage.com/) to VHOST.
  * The index.php is not required; code.php is required and referenced by .htaccess
  * GD required, e.g., On Ubuntu: `apt-get install php5-gd`
* Line 110 of code.php may need to have the explicit path to the font (on Ubuntu):
* `$font = "/data/www/cdn/mplus-1c-medium.ttf";`

### Configuration
* Copy https://github.com/petersm3/browser/blob/master/config/config.php-template to `config.php`
  * Configure values
  * User should be `browser_www` with only a GRANT to SELECT.
