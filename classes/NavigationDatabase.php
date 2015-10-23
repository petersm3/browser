<?php

class NavigationDatabase {

    function __construct($dbh) {
        $this->dbh = $dbh;
    }

    protected function beforeAction() {
        parent::beforeAction(); // chain to parent
    }

    public function getCategories() {
        // categories have a `priority` order assigned in the schema for nav display
        $sql = "SELECT DISTINCT category FROM category_filters ORDER BY priority";
        $st = $this->dbh->prepare($sql);
        $st->execute();
        return $st->fetchAll();
    }

    public function getFilters($category) {
        $sql = "SELECT filters FROM category_filters WHERE category = ?";
        $st = $this->dbh->prepare($sql);
        $values = array($category);
        $st->execute();
        return $st->fetchAll();
    }
}
