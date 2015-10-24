<?php
require_once(APP_PATH . 'classes/DisplayDatabase.php');

class Display {
    public function getResults($get, $dbh) {
        $this->displayDatabase = new DisplayDatabase($dbh);

        $results='';
        $results='<div class="container">';
        //$categoryIds=array(2, 5);
        $categoryIds=array(22);
        $filterMatches = $this->displayDatabase->getFilterMatches($categoryIds);
        foreach ($filterMatches as $filterMatch) {
            $results.='<div class="jumbotron">';
            $results.='Accession: ' . $filterMatch['accession'];
            $results.='</div>';
        }
        $results.='</div>'; // close container
        return $results; 
    }
/* vim:set noexpandtab tabstop=4 sw=4: */
}
