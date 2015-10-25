<?php
$this->setLayoutVar('pageTitle', 'Display ' . $accession);
?>
<a href="#content" class="sr-only">Skip navigation</a>
<?php echo $navigation ?>
<div class="container">
<selection id="content">
<?php echo $result ?>
</selection>
</div>
