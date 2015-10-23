<?php
require_once(APP_PATH . 'config/config.php');
require_once(APP_PATH . 'classes/Database.php');
require_once(APP_PATH . 'classes/NavigationDatabase.php');

// Create Faceted Navigation header
// Intercepts previously checked items and repopulates check boxes
class Navigation {
    public function getMenus($get, $about = 0) {

        $this->database = new Database;
        $this->dbh = $this->database->getConnection(); // Get database handle
        $this->navigationDatabase = new NavigationDatabase($this->dbh); // Navigation related queries

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
                $categoryUnderscore = str_replace(' ', '_', $category['category']); // Avoid spaces in GET
                $menus.='<li class="dropdown">';
                $menus.='<a href="#" class="dropdown-toggle" data-toggle="dropdown" ';
                $menus.=' role="button" aria-haspopup="true" aria-expanded="false">';
                $menus.=$category['category'];
                $menus.='<span class="caret"></span></a><ul class="dropdown-menu">';
                // Get each individual filter (sub-category) per the current category
                $categoryFilters = $this->navigationDatabase->getFilters($category['category']);
                foreach ($categoryFilters as $categoryFilter) {
                    $categoryFilterUnderscore = str_replace(' ', '_', $categoryFilter['filter']); // Avoid spaces
                    $categoryFilterEncode = urlencode($categoryFilterUnderscore); // encode &
                    $checked='';
                    if (isset($get['filter'])) {
                        // If the filter was previouly checked as shown by the current
                        // GET string then re-check it on the current display
                        foreach ($get['filter'] as $filter) {
                            $filterEncode = urlencode($filter); // Encode filter from GET to match below
                            if ("$filterEncode" == $categoryUnderscore . $colon . $categoryFilterEncode) {
                                $checked='checked';
                            }
                        }
                    }
                    // Previously checked item determined from GET string above
                    $menus.='<li>&nbsp;<input type="checkbox" ' . $checked;
                    // id necessary to match label tag below
                    $menus.=' name="filters[]" id="' . $categoryUnderscore . $colon . $categoryFilterEncode;
                    // value for filters[] is represented as category:filter
                    $menus.='" value="' . $categoryUnderscore . $colon . $categoryFilterEncode;
                    // Resubmit and update screen on every new check/un-check
                    $menus.='" onchange="this.form.submit();"> ';
                    // Enable text to be clickable along with checkbox
                    // Override bootstrap's default bold style of labels
                    $menus.='<label style="font-weight:normal !important;" for="';
                    $menus.=$categoryUnderscore . $colon . $categoryFilterEncode . '">';
                    $menus.=$categoryFilter['filter'] . '</label>';
                    $menus.='</li>';
                }
                $menus.='</ul></li>';
            }
        }

// Close drop down menus and list About menu to right
$menus.= <<<'EOD'
<li><button type="submit" class="btn btn-link">Submit</button></li>
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
        // Display currently applied filters
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
        }
        $menus.='</div>'; // Close container

        return $menus;
    }
/* vim:set noexpandtab tabstop=4 sw=4: */
}
