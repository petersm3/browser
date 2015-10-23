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
<form method="post" action="/filter">
<ul class="nav navbar-nav">
EOD;
        if(!$about) {
            // Obtain a listing of all top-level categories (unique)
            $categories = $this->navigationDatabase->getCategories();
            // Itterate through each pre-defined category in order and construct drop-down
            foreach ($categories as $category) {
                $categoryUnderscore = str_replace(' ', '_', $category['category']); // Avoid spaces in GET
                // $i integer needs to replaced with a table query for category in question
                $menus.='<li class="dropdown">';
                $menus.='<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">';
                $menus.=$category['category'];
                $menus.='<span class="caret"></span></a><ul class="dropdown-menu">';
                // Get each individual filter per the current category
                $category_filters = $this->navigationDatabase->getFilters($category['category']);
                foreach ($category_filters as $category_filter) {
                    $categoryFilterUnderscore = str_replace(' ', '_', $category_filter['filter']); // Avoid spaces
                    $categoryFilterEncode = urlencode($categoryFilterUnderscore);
                    $checked='';
                    if (isset($get['filter'])) {
                        // If the filter was previouly checked as shown by the current GET string
                        // then re-check it on the current display
                        foreach ($get['filter'] as $filter) {
                            $filterEncode = urlencode($filter);
                            if ("$filterEncode" == $categoryUnderscore . $colon . $categoryFilterEncode) {
                                $checked='checked';
                            }
                        }
                    }
                    $menus.='<li>&nbsp;<input type="checkbox" ' . $checked;
                    $menus.=' name="filters[]" id="' . $categoryUnderscore . $colon . $categoryFilterEncode;
                    $menus.='" value="' . $categoryUnderscore . $colon . $categoryFilterEncode;
                    $menus.='" onchange="this.form.submit();"> ';
                    // Enable text to be clickable along with checkbox
                    // Override bootstrap's default bold style of labels
                    $menus.='<label style="font-weight:normal !important;" for="';
                    $menus.=$categoryUnderscore . $colon . $categoryFilterEncode . '">';
                    $menus.=$category_filter['filter'] . '</label>';
                    $menus.='</li>';
                }
                $menus.='</ul></li>';
            }
        }

// TODO: Implement Submit button for WCAG compliance

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
        // Display currently applied filters
        if(!$about) {
            $menus.='<ol class="breadcrumb">';
            if(isset($get['filter'])) {
                foreach ($get['filter'] as $filter) {
                    $menus.='<li>' . str_replace('_', ' ', $filter) . '</li> ';
                }
            } else {
                $menus.= '<li>Filters: <i>none</i>';
            }
            $menus.='</ol>';
        }
        $menus.='</div>'; // Close container

        return $menus;
    }
/* vim:set noexpandtab tabstop=4 sw=4: */
}
