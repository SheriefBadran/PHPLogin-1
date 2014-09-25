<?php
require_once(HelperPath.DS.'interfaces/iAbstractFactory.php');
require_once(HelperPath.DS.'db/DB_Factory_PDO.php');


	class DB_Factory implements iAbstractFactory {

	    public function __construct() {

	    }

	    public static function getFactory() {

	    	$factory = new DB_Factory_PDO();
	        return $factory;
	    }
	 }