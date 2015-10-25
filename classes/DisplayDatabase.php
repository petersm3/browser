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

    // Obtain a list of fk_properties_ids that match the filter criteria exactly
    public function getFilterMatches($categoriesIds, $limit = 0, $offset = 0) {
        // http://stackoverflow.com/questions/920353/can-i-bind-an-array-to-an-in-condition
        $qMarks = ' ? ';
        if(count($categoriesIds) > 1) {
            $qMarks = str_repeat('?,', count($categoriesIds) - 1) . '?';
        }
        $sql="SELECT fk_properties_id, COUNT(fk_properties_id) as count_fk_properties_id";
        $sql.=" FROM filters WHERE fk_categories_id ";
        $sql.="IN ($qMarks) GROUP BY fk_properties_id HAVING count_fk_properties_id = ";
        $sql.=count($categoriesIds);
        if($limit > 0) {
            $sql.=" LIMIT " . intval($limit);
        }
        if($offset > 0) {
            $sql.=" OFFSET " . intval($offset);
        } 
        $st = $this->dbh->prepare($sql);
        $st->execute($categoriesIds);
        return $st->fetchAll();
    }

    public function getProperties($propertiesId) {
        $sql="SELECT image, street_address, photographer, date ";
        $sql.=" FROM properties where id = ?";
        $st = $this->dbh->prepare($sql);
        $values = array($propertiesId);
        $st->execute($values);
        return $st->fetch();
    }
}
/* vim:set noexpandtab tabstop=4 sw=4: */
?>
