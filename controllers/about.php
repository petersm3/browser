<?php
require_once(APP_PATH . 'classes/Navigation.php');

// Dummy controller for About page; takes no arguments
class AboutController extends AppController {
    public function actionIndex() {
        $this->navigation = new Navigation;
        // Set Navigation to display for about page by specifying second arg as 1
        $this->setVar('nav', $this->navigation->getMenus($this->get, 1));
    }
}
?>
