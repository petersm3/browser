<?php
// Create Faceted Navigation header
// Intercepts previously checked items and repopulates check boxes
class Navigation {
    public function getMenus($get, $post) {
        // Dynamically construct drop downs
        $categories = array("Creator", "Photographer", "Style Period", "Work Type", "Region", "Rights", "Format");
        
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
<form method="post" action="/">
<ul class="nav navbar-nav">
EOD;

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
            // Not handling 2 checks of the same category
            foreach ($post['filters'] as $key => $value) {
                if ("$value" == "$categoryUnderscore:$i") {
                    $checked='checked';
                }
            }
            $menus.='<li>&nbsp;<input type="checkbox" ' . $checked;
            $menus.=' name="filters[]" value="' . $categoryUnderscore.':'.$i;
            $menus.='" onchange="this.form.submit();"> ' . $i;
            $menus.='</li>';
            }
            $menus.='</ul></li>';
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
<ol class="breadcrumb">
EOD;
        $menus.="<b>Filters:</b> ";
        foreach ($post['filters'] as $key => $value) {
            $menus.='<li>' . $value . '</li> ';
        }
        $menus.='</ol></div>';
        return $menus;
    }
/* vim:set noexpandtab tabstop=4 sw=4: */
}
