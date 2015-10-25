<?php
// Navigation helper functions to construct top-level dropdown faceted navigation

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
        $sql = "SELECT DISTINCT category FROM categories ORDER BY priority";
        $st = $this->dbh->prepare($sql);
        $st->execute();
        return $st->fetchAll();
    }

    // Obtain all filters (sub-categories) per single category; unsorted
    public function getSubCategories($category) {
        $sql = "SELECT subcategory FROM categories WHERE category = ?";
        $st = $this->dbh->prepare($sql);
        $values = array($category);
        $st->execute($values);
        return $st->fetchAll();
    }
}
/* vim:set noexpandtab tabstop=4 sw=4: */
?>
