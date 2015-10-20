<?php
// Create Faceted Navigation header
// Intercepts previously checked items and repopulates check boxes
//         echo "cat1: " . $this->get['cat1'][1] . '<br/>';
//                 echo "cat2: " . $this->post['cat2'][0] . '<br/>';
class Navigation {
    public function getMenus($get, $post) {
        // Dynamically construct drop downs
        $categories = array("Creator", "Photographer", "Style Period", "Work Type", "Region", "Rights", "Format");
        $menus='';

$menus.='
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
<ul class="nav navbar-nav">';

foreach ($categories as $category) {
$menus.='
<li class="dropdown">
<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">';
$menus.=$category;
$menus.='<span class="caret"></span></a>
<ul class="dropdown-menu">';
$value_set="creator-x";
$checked='';
//echo '!'.$post[$category][0].'!<br/>';
foreach ($post as $key => $value) {
    //echo "|$key -> $value|<br/>";
    echo $value.'<br/>';
    if ("$value" == "$category-$value_set") {
        $checked='checked';
        //echo 'gotcha<br/>';
    }
}
$menus.='<li>&nbsp;<input type="checkbox" ' . $checked . ' name="' . $category . '" value="' . $category .'-'.$value_set .'"  onchange="this.form.submit();"> ' . $value_set .'</li>
</ul>
</li>';
}

// Close drop down menus and list About menu to right
$menus .= '
</ul>
</form>
<ul class="nav navbar-nav navbar-right">
<li><a href="/about">About</a></li>
</ul>
</div><!-- /.navbar-collapse -->
</div><!-- /.container-fluid -->
</nav>';

        return $menus;
    }
/* vim:set noexpandtab tabstop=4 sw=4: */
}
