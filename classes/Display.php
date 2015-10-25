<?php
require_once(APP_PATH . 'classes/DisplayDatabase.php');

class Display {
    public function getResults($get, $dbh) {
        $this->displayDatabase = new DisplayDatabase($dbh);

        $offset=0;
        $limit=2;

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

            if(isset($get['offset'])) {
                $offset = intval($get['offset']);
            }

            // Get all accessions that match filter criteria
            $filterMatches = $this->displayDatabase->getFilterMatches($categoryIds, $limit, $offset);
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

            // Pagniation
            $filterMatchCount=count($this->displayDatabase->getFilterMatches($categoryIds));
            $totalPages=ceil($filterMatchCount/$limit);
            $results.='<div class="text-center">';
            $results.='<ul class="pagination">';
            // Construct URL of current page
            $urlFilter='';
            foreach ($get['filter'] as $getFilter) {
                $urlFilter.='filter[]=' .  $getFilter . '&amp;';
            }
            for ($page = 1; $page <= $totalPages; $page++) {
                $currentOffset = (($page-1)*$limit);
                if($page == 1) {
                    $currentOffset = 0;
                }
                if($currentOffset == $offset) {
                    $results.='<li class="active">';     
                } else {
                    $results.='<li>';
                }
                $results.='<a href="/?' . $urlFilter;
                $results.='offset=' . $currentOffset; 
                $results.='">' . $page;
                if($currentOffset == $offset) {
                    $results.=' <span class="sr-only">(current)</span>';
                } 
                $results.='</a></li>';
            }
            $results.='</ul>';
            $results.='</div>';

            $results.='</div>'; // close container
        }
        return $results; 
    }
/* vim:set noexpandtab tabstop=4 sw=4: */
}
