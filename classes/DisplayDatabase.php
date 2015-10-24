<?php

class DisplayDatabase {

    function __construct($dbh) {
        $this->dbh = $dbh;
    }

    protected function beforeAction() {
        parent::beforeAction(); // chain to parent
    }

    // Lookup function to get primary key for each filter specified
    public function getCategoriesId($category, $subcategory) {
        $sql="SELECT id FROM categories where category = ? and subcategory = ?";
        $st = $this->dbh->prepare($sql);
        $values = array($category, $subcategory);
        $st->execute($values);
        return $st->fetch();
    }

    // Obtain a list of accessions that match the filter criteria exactly
    public function getFilterMatches($categoriesIds) {
        // http://stackoverflow.com/questions/920353/can-i-bind-an-array-to-an-in-condition
        $qMarks = str_repeat('?,', count($categoriesIds) - 1) . '?';
        $sql= "SELECT accession, COUNT(accession) as count_accession FROM filters WHERE fk_categories_id ";
        $sql.="IN ($qMarks) GROUP BY accession HAVING count_accession = ";
        $sql.=count($categoriesIds);
        $st = $this->dbh->prepare($sql);
        $st->execute($categoriesIds);
        return $st->fetchAll();
    }
}
