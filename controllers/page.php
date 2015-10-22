<?php
require_once(APP_PATH . 'config/config.php');
require_once(APP_PATH . 'classes/Database.php');
require_once(APP_PATH . 'classes/Navigation.php');

class PageController extends AppController
{
	private $database;
	private $dbh;

	protected function beforeAction() {
       
	//	parent::beforeAction(); // chain to parent
		$this->database = new Database;
	    $this->dbh = $this->database->getConnection();
    }

	public function actionView($pageName = 'home')
	{
        $this->navigation = new Navigation;
        // Need to past LightVC get and post for navigation to parse
        $this->setVar('nav', $this->navigation->getMenus($this->get));

		if (strpos($pageName, '../') !== false)
		{
			throw new Lvc_Exception('File Not Found: ' . $sourceFile);
		}

		$this->loadView('page/' . rtrim($pageName, '/'));
	}
}

?>
