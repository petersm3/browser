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
                    $results.='<div class="row">';
                    $results.='<div class="col-sm-5">';
                    $results.='<img src="http://cdn.communicore.net/320x200/000/fff&amp;text=8644aef5764b2fb3fc9cd6955962a01b" alt="adfadfa" />';
                    $results.='</div>';
                    $results.='<div class="col-sm-2"></div>';
                    $results.='<div class="col-sm-5">';
                    $results.='Accession: ' . $filterMatch['fk_properties_id'];
                    $results.='</div></div>';
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
