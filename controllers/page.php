<?php
require_once(APP_PATH . 'classes/Navigation.php');

class PageController extends AppController
{
	private $database;
	private $dbh;

	protected function beforeAction() {
	    // parent::beforeAction(); // chain to parent
    }

	public function actionView($pageName = 'home')
	{
        $this->navigation = new Navigation;
        // Need to past LightVC get and post for navigation to parse
        $this->setVar('navigation', $this->navigation->getMenus($this->get));
        $this->setVar('results', '');

		if (strpos($pageName, '../') !== false)
		{
			throw new Lvc_Exception('File Not Found: ' . $sourceFile);
		}

		$this->loadView('page/' . rtrim($pageName, '/'));
	}
}

?>
