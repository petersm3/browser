<?php
require_once(APP_PATH . 'config/config.php');
require_once(APP_PATH . 'classes/Database.php');
require_once(APP_PATH . 'classes/NavigationDatabase.php');

// Create Faceted Navigation header
// Intercepts previously checked items and repopulates check boxes
class Navigation {
    public function getMenus($get, $about = 0) {

                $this->database = new Database;
                $this->dbh = $this->database->getConnection();
                $this->navigationDatabase = new NavigationDatabase($this->dbh);

                //print_r($this->$dbh->getCategoryFilters());
        // Dynamically construct drop downs
        $categories = array("Creator", "Photographer", "Style Period", "Work Type", "Region", "Rights", "Format");

$categoryFilters = $this->navigationDatabase->getCategoryFilters();
foreach($categoryFilters as $categoryFilter) {
    echo $categoryFilter['category'] . ' -> '. $categoryFilter['filter'] . '<br/>';
}

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
            // Itterate through each pre-defined category in order and construct drop-down
            foreach ($categories as $category) {
                $categoryUnderscore = str_replace(' ', '_', $category); // Avoid spaces in GET
                // $i integer needs to replaced with a table query for category in question
                $menus.='<li class="dropdown">';
                $menus.='<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">';
                $menus.=$category;
                $menus.='<span class="caret"></span></a><ul class="dropdown-menu">';
                for ($i = 1; $i <= 10; $i++) {
                $checked='';
                if (isset($get['filter'])) {
                    foreach ($get['filter'] as $filter) {
                        if ("$filter" == "$categoryUnderscore:$i") {
                            $checked='checked';
                        }
                    }
                }
                $menus.='<li>&nbsp;<input type="checkbox" ' . $checked;
                $menus.=' name="filters[]" value="' . $categoryUnderscore.':'.$i;
                $menus.='" onchange="this.form.submit();"> ' . $i;
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
            $menus.="<b>Filters:</b> ";
            if(isset($get['filter'])) {
                foreach ($get['filter'] as $filter) {
                    $menus.='<li>' . str_replace('_', ' ', $filter) . '</li> ';
                }
            } else {
                $menus.='<i>none</i>'; // No filters applied; home screen
            }
            $menus.='</ol>';
        }
        $menus.='</div>'; // Close container

        return $menus;
    }
/* vim:set noexpandtab tabstop=4 sw=4: */
}
