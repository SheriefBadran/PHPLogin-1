<?php

require_once(HelperPath.DS.'db/DB_Factory.php');

	class DatabaseAccessModel {

		// protected $dbUsername = 'root';
		// protected $dbPassword = 'root';
		// protected $dbConnstring = 'mysql:host=localhost;dbname=129463-loginmodule';
		// protected $dbConnection;
		// protected $dbTable;
		
		// protected function connection() {
		// 	if ($this->dbConnection == NULL)
		// 		$this->dbConnection = new \PDO($this->dbConnstring, $this->dbUsername, $this->dbPassword);
			
		// 	$this->dbConnection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			
		// 	return $this->dbConnection;
	 // 	}

		protected $dbFactory;

		public function __construct () {

			$dbAbstactFactory = new DB_Factory();
			$this->dbFactory = $dbAbstactFactory::getFactory();
		}


	}