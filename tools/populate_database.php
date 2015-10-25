#!/usr/bin/php
<?php
// This script assumes you only run it ONCE!
// If you need to run it again then drop and recreate all databases from scratch
// delete from <table name> is not sufficient as the auto increment primary keys
// will be out of order.

require_once('../classes/Database.php');
// browser_www user only has SELECT permissions in ../config/config.php
// Specify a user with INSERT credentials in credentials.php
require_once('./credentials.php');

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
// e.g., INSERT INTO categories (priority, category, subcategory) VALUES (1, 'Creator', 'Lawrence, Ellis Fuller');
// Generate 4 categories and populate each with 10 subcategories
$categories = array('Creator', 'Style Period', 'Work Type', 'Region');
$priority=1;
foreach ($categories as $category) {
    $sql= "INSERT INTO categories (priority, category, subcategory)";
    $sql.= " VALUES (?, ?, ?)";
    $st=$dbh->prepare($sql);
    for ($increment = 1; $increment <= 10; $increment++) {
        // Get first letter of category followed by an integer
        $subcategory=substr($category, 0, 1) . $increment;
        $values = array($priority, "$category", "$subcategory");
        $st->execute($values);
    }
    $priority++;
}

// Popualte `properties` (table that contains accessions)
// Generate number based upon what was specified as a command line argument
$year  = range(1900, 2015, 1); // Random year
$direction = array('north', 'north east', 'east', 'south east', 'south', 'south west', 'west', 'north west');
$prefix = array('Mr.', 'Ms.', 'Mrs.', 'Miss', 'Dr.');
$sql="INSERT INTO properties (image, street_address, photographer, date)";  
$sql.=" VALUES (?, ?, ?, ?)";  
$st=$dbh->prepare($sql);
for ($accession = 1; $accession <= intval($argv[1]); $accession++) {
    // uniqid does not need to be truely unique for this example
    // Assigned DOI would be more appropriate in production
    $values = array(md5(uniqid()), rand(1,1000) . ' ' . $direction[array_rand($direction)] , $prefix[array_rand($prefix)] . ' ' . chr(64+rand(0,26)), $year[array_rand($year)]);
    $st->execute($values);
}

// Populate `filters` (that ties each accession to a subcategory in the navigation)
$sql="INSERT INTO filters (fk_properties_id, fk_categories_id)";
$sql.=" VALUES(?, ?)";
$st=$dbh->prepare($sql);
for ($accession = 1; $accession <= intval($argv[1]); $accession++) {
    // Create up to 5 filters per accession
    // There are 4 categories * 10 subcategories = 40
    for($filter = 1; $filter <= rand(1,5); $filter++) {
        $values = array($accession, rand(1,40));
        $st->execute($values); 
    }
}

// Populate `filters` (that ties each accession to a subcategory in the navigation)
$colors = array('red', 'orange', 'yellow', 'green', 'blue', 'indigo', 'violet', 'Burnt Sienna');
$clouds = array('Clear', 'Scattered/Partly Cloudy', 'Broken/Mostly Cloudy', 'Overcast', 'Obscured');

$sql="INSERT INTO attributes (fk_properties_id, name, value)";
$sql.=" VALUES(?, ?, ?)";
$st=$dbh->prepare($sql);
for ($accession = 1; $accession <= intval($argv[1]); $accession++) {
    $values = array($accession, 'Color', $colors[array_rand($colors)]);
    $st->execute($values);
    $values = array($accession, 'Clouds', $clouds[array_rand($clouds)]);
    $st->execute($values);
    $values = array($accession, 'Humidity (%)', rand(1,100));
    $st->execute($values);
}
/* vim:set noexpandtab tabstop=4 sw=4: */
?>
