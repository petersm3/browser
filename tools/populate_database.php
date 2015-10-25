#!/usr/bin/php
<?php
require_once('../classes/Database.php');
// browser_www user only has SELECT permissions in ../config/config.php
// Specify a user with INSERT credentials in credentials.php
require_once('./credentials.php'):

/* Not using PDO transactions with exception handling in this script.
 */

$database = new Database;
$dbh = $database->getConnection(); // Get database handle

if(!isset($argv[1])) {
    echo 'Usage: ' . $argv[0] . ' <number of accessions to randomly generate>' . "\n";
    echo ' e.g., ' . $argv[0] . ' 10000' . "\n";
    echo "\n";
    echo "This script will populate the following tables with sample data:\n";
    echo "`categories`, `properties`, `filters`, and `attributes`\n\n";
    exit();
};

// Populate `categories`
// INSERT INTO categories (priority, category, subcategory) VALUES (1, 'Creator', 'Lawrence, Ellis Fuller');
$sql= "INSERT INTO categories (priority, category, subcategory)";
$sql.= " VALUES (?, ?, ?)";
$values = array(1, 'Creator', 'Lawrence, Ellis Fuller');
$st=$dbh->prepare($sql);
$st->execute($values);


?>
