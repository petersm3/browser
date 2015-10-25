#!/usr/bin/php
<?php

require_once('../config/config.php');
require_once('../classes/Database.php');

$database = new Database;
$dbh = $database->getConnection(); // Get database handle

if(!isset($argv[1])) {
    echo 'Usage: ' . $argv[0] . ' <number of accessions to randomly generate>' . "\n";
    echo ' e.g., ' . $argv[0] . ' 10000' . "\n";
    echo "\n";
    echo "This script will populate the following tables with sample data:\n";
    echo "`properties`, `categories`, `filters`, and `attributes`\n\n";
    exit();
};

?>
