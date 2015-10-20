<?php
// Dummy controller for About page; takes no arguments
class AboutController extends AppController {
    public function actionIndex() {
                $this->navigation = new Navigation;
                $this->setVar('nav', $this->navigation->getMenus());
	// Nothing to implement
    }
}
?>
