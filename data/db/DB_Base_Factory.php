<?php
require_once(HelperPath.DS.'interfaces/iFactory.php');

	abstract class DB_Base_Factory {

		protected $dbUsername = 'root';
		protected $dbPassword = 'root';
		protected $dbConnstring = 'mysql:host=localhost;dbname=129463-loginmodule';
		protected $dbConnection;
		protected $dbTable;

	    protected function __construct () {

	        //
	    }
	 
	    // abstract public function createInstance ();
	}