<?php

class NavigationDatabase {

    function __construct($dbh) {
        $this->dbh = $dbh;
    }

    protected function beforeAction() {
        parent::beforeAction(); // chain to parent
    }

    public function getCategoryFilters() {
        $sql = "SELECT * FROM category_filters";
        $st = $this->dbh->prepare($sql);
        $st->execute();
        return $st->fetchAll();
    }
}
