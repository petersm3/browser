# Summary
Image and cultural properties browser
# Setup
## MySQL
* View comments at bottom of: https://github.com/petersm3/browser/blob/master/classes/Database.php
* CREATE DATABASE
  * browser
* CREATE USER and GRANTS
  * browser_www
* CREATE TABLES
  * categories
  * properties
  * filters
  * attributes
* CREATE INDEXES
### Populate database
* Copy https://github.com/petersm3/browser/blob/master/tools/credentials.php-template to credentials.php
  * Configure values
* Run https://github.com/petersm3/browser/blob/master/tools/populate_database.php
  * e.g., 10000 accessions

## Apache

### VHOST

## Application

### Content delivery network (CDN)
* Simulate CDN by deploying "Dynamic Dummy Image Generator" (http://dummyimage.com/) to VHOST
  * Do not need index.php deployed; code.php is referenced by htaccess
* Line 110 of code.php may need to have the explicit path to the font (on Ubuntu)
* `$font = "/data/www/cdn/mplus-1c-medium.ttf";`
  * Ubuntu 

### Configuration
* Copy https://github.com/petersm3/browser/blob/master/config/config.php-template to config.php
  * Configure values
