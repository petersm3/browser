<?php
require_once(APP_PATH . 'classes/DisplayDatabase.php');

class Display {
    public function getResults($get, $dbh) {
        $this->displayDatabase = new DisplayDatabase($dbh);

        $results='';
        if(isset($get['filter'])) {
            $results='<div class="container">';
            $categoryIds = array();
            // Lookup categories primary key `id` for each filer selected by user
            foreach ($get['filter'] as $getFilter) {
                // Filter is specified as category:subcategory
                $categorySubcategory = explode(":", $getFilter);
                $category = str_replace('_', ' ', $categorySubcategory[0]);
                $subcategory = urldecode(str_replace('_' ,' ', $categorySubcategory[1]));
                $categoryId = $this->displayDatabase->getCategoriesId($category, $subcategory);
                array_push($categoryIds, $categoryId['id']);
            }
            // Get all accessions that match filter criteria
            $filterMatches = $this->displayDatabase->getFilterMatches($categoryIds);
            if(count($filterMatches) == 0) {
                $results.='<div class="jumbotron">';
                $results.='No matches found satisfying an exact match to the above filter critera.';
                $results.='</div>'; 
            } else {
                foreach ($filterMatches as $filterMatch) {
                    $results.='<div class="jumbotron">';
                    $results.='Accession: ' . $filterMatch['accession'];
                    $results.='</div>';
                }
            }
            $results.='</div>'; // close container
        }
        return $results; 
    }
/* vim:set noexpandtab tabstop=4 sw=4: */
}
