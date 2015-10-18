<?php
$this->setLayoutVar('pageTitle', 'Image and cultural properties browser');
?>
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
<form method="post" action="/">
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Creator<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li>&nbsp;<input type="checkbox" aria-label="Lawrence,_Ellis_Fuller" onchange="this.form.submit();">&nbsp;Lawrence, Ellis Fuller</li>
            <li>&nbsp;<input type="checkbox" aria-label="Holford,_William" onchange="this.form.submit();">&nbsp;Holford, William</li>
            <li>&nbsp;<input type="checkbox" aria-label="Doyle,_Albert_E." onchange="this.form.submit();">&nbsp;Doyle, Albert E.</li>
          </ul>
        </li>
      </ul>
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expand
ed="false">Photographer<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li>&nbsp;<input type="checkbox" aria-label="Ross,_Marion_Dean" onchange="this.form.submit();">&nbsp;Ross, Marion Dean</li>
            <li>&nbsp;<input type="checkbox" aria-label="Shellenbarger,_Michael" onchange="this.form.submit();">&nbsp;Shellenbarger, Michael</li>
            <li>&nbsp;<input type="checkbox" aria-label="Teague,_Edward,_1952-" onchange="this.form.submit();">&nbsp;Teague, Edward, 1952-</li>
          </ul>
        </li>
      </ul>
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expand
ed="false">Style Period<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
          </ul>
        </li>
      </ul>
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expand
ed="false">Work Type<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
          </ul>
        </li>
      </ul>
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expand
ed="false">Region<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
          </ul>
        </li>
      </ul>
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expand
ed="false">Rights<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
          </ul>
        </li>
      </ul>
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expand
ed="false">Format<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">Action</a></li>
            <li><a href="#">Another action</a></li>
            <li><a href="#">Something else here</a></li>
          </ul>
        </li>
      </ul>
     <ul class="nav navbar-nav">
          <button type="submit" class="btn btn-default navbar-btn">Submit</button>
     </ul>
</form>
  <ul class="nav navbar-nav navbar-right">
        <li><a href="#">About</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
