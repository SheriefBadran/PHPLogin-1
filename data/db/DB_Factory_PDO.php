<?php
require_once(HelperPath.DS.'db/DB_Base_Factory.php');

	class DB_Factory_PDO extends DB_Base_Factory implements iFactory {

	    public function __construct () {

	        parent::__construct();
	    }

	    public function createInstance () {

	    	if ($this->dbConnection == NULL) {

				$this->dbConnection = new \PDO($this->dbConnstring, $this->dbUsername, $this->dbPassword);
	    	}
			
			$this->dbConnection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			
			return $this->dbConnection;
	    }
	}