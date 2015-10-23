<?php

class NavigationDatabase {

    function __construct($dbh) {
        $this->dbh = $dbh;
    }

    protected function beforeAction() {
        parent::beforeAction(); // chain to parent
    }

    // Obtain all categories sorted by priority
    public function getCategories() {
        // categories have a `priority` order assigned in the schema for nav display
        $sql = "SELECT DISTINCT category FROM category_filters ORDER BY priority";
        $st = $this->dbh->prepare($sql);
        $st->execute();
        return $st->fetchAll();
    }

    // Obtain all filters (sub-categories) per single category; unsorted
    public function getFilters($category) {
        $sql = "SELECT filter FROM category_filters WHERE category = ?";
        $st = $this->dbh->prepare($sql);
        $values = array($category);
        $st->execute($values);
        return $st->fetchAll();
    }
}
