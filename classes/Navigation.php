<?php
require_once(APP_PATH . 'classes/NavigationDatabase.php');
require_once(APP_PATH . 'classes/DisplayDatabase.php');

// Create Faceted Navigation header
// Intercepts previously checked items and repopulates check boxes
class Navigation {
    public function getMenus($get, $dbh = null, $about = 0) {

        $this->navigationDatabase = new NavigationDatabase($dbh);
        $this->displayDatabase = new DisplayDatabase($dbh);

        $colon='%3A'; // urlencode(':')
        $menus='';

$menus.= <<<'EOD'
<nav class="navbar navbar-default">
<div class="container-fluid">
<!-- Brand and toggle get grouped for better mobile display -->
<div class="navbar-header">
<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
<span class="sr-only">Toggle navigation</span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
<span class="icon-bar"></span>
</button>
<a class="navbar-brand" href="/">Browser <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span></a>
</div>
<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
<form method="post" action="/filter" class="navbar-form navbar-left">
<ul class="nav navbar-nav">
EOD;

        if(!$about) {
            // Obtain a listing of all (unique) top-level categories
            $categories = $this->navigationDatabase->getCategories();
            // Itterate through each pre-defined category in order and construct drop-down
            foreach ($categories as $category) {
                $categoryRaw = $category['category'];
                $categoryUnderscore = str_replace(' ', '_', $categoryRaw); // Avoid spaces in GET
                $menus.='<li class="dropdown">';
                $menus.='<a href="#" class="dropdown-toggle" data-toggle="dropdown" ';
                $menus.=' role="button" aria-haspopup="true" aria-expanded="false">';
                $menus.=$category['category'];
                $menus.='<span class="caret"></span></a><ul class="dropdown-menu">';
                // Get each individual subcategories per the current category
                $subCategories = $this->navigationDatabase->getSubCategories($categoryRaw);
                foreach ($subCategories as $subCategory) {
                    $subCategoryRaw = $subCategory['subcategory'];
                    $subCategoryUnderscore = str_replace(' ', '_', $subCategoryRaw); // Avoid spaces
                    $subCategoryEncode = urlencode($subCategoryUnderscore); // encode &
                    $checked='';
                    if (isset($get['filter'])) {
                        // If the filter was previouly checked as shown by the current
                        // GET string then re-check it on the current display
                        foreach ($get['filter'] as $getFilter) {
                            $getFilterEncode = urlencode($getFilter); // Encode filter from GET to match below
                            if ("$getFilterEncode" == $categoryUnderscore . $colon . $subCategoryEncode) {
                                $checked='checked';
                            }
                        }
                    }
                    // Previously checked item determined from GET string above
                    $menus.='<li>&nbsp;<input type="checkbox" ' . $checked;
                    // id necessary to match label tag below
                    $menus.=' name="filters[]" id="' . $categoryUnderscore . $colon . $subCategoryEncode;
                    // value for filters[] is represented as category:filter
                    $menus.='" value="' . $categoryUnderscore . $colon . $subCategoryEncode;
                    // Resubmit and update screen on every new check/un-check
                    $menus.='" onchange="this.form.submit();"> ';
                    // Enable text to be clickable along with checkbox
                    // Override bootstrap's default bold style of labels
                    $menus.='<label style="font-weight:normal !important;" for="';
                    $menus.=$categoryUnderscore . $colon . $subCategoryEncode . '">';
                    $menus.=$subCategoryRaw;

                    // Given current search filters applied project what adding this additional
                    // filter would yield in total results.
                    $categoryIds = array();
                    if(isset($get['filter'])) {
                        foreach ($get['filter'] as $getFilter) {
                            // Filter is specified as category:subcategory
                            $categorySubcategory = explode(":", $getFilter);
                            $category = $categorySubcategory[0];
                            $subcategory = $categorySubcategory[1];
                            $categoryId = $this->displayDatabase->getCategoriesId($category, $subcategory);
                            array_push($categoryIds, $categoryId['id']);
                        }
                    }
                    // Add curent unselected filter to array to generate possible set of return accessions
                    $categoryId = $this->displayDatabase->getCategoriesId($categoryRaw, $subCategoryRaw);
                    array_push($categoryIds, $categoryId['id']);
                    $filterMatches = $this->displayDatabase->getFilterMatches($categoryIds);
                    $menus.= '&nbsp;&nbsp;<span class="badge">' . count($filterMatches);
                    $menus.='</span><label></li>';
                }
                $menus.='</ul></li>';
            }
            $menus.='<li><button type="submit" class="btn btn-link">Submit</button></li>';
        }

// Close drop down menus and list About menu to right
$menus.= <<<'EOD'
</ul>
</form>
<ul class="nav navbar-nav navbar-right">
<li><a href="/about">About</a></li>
</ul>
</div><!-- /.navbar-collapse -->
</div><!-- /.container-fluid -->
</nav>
<div class="container">
EOD;
        // Display currently applied filters on main page (not About page)
        if(!$about) {
            $menus.='<ol class="breadcrumb">';
            if(isset($get['filter'])) {
                foreach ($get['filter'] as $filter) {
                    $menus.='<li>' . str_replace('_', ' ', $filter) . '</li> ';
                }
            } else {
                $menus.= '<li>Filters: <i>none</i></li>';
            }
            $menus.='</ol>';

            // If no filters show a default message
            if(!isset($get['filter'])) {
                $menus.='<div class="jumbotron">';
                $menus.='Select filters from the dropdown categories above to being you search.';
                $menus.='</div>';
            }
        }
        $menus.='</div>'; // Close container

        return $menus;
    }
/* vim:set noexpandtab tabstop=4 sw=4: */
}
