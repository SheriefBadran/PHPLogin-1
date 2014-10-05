<?php
	
	// DEFINE CORE PATHS (absolute).
	
	// Define a short for directory separator.
	defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

	// Define a project root path.
	defined('ProjectRootPath') ? null : define('ProjectRootPath', DS.'Applications'.DS.'MAMP'.DS.'htdocs'.DS.'www'.DS.'git'.DS.'PHPLogin-1');

	// Define helper path.
	defined('HelperPath') ? null : define('HelperPath', ProjectRootPath.DS.'data');

	// Define MVC path.
	defined('ModelPath') ? null : define('ModelPath', ProjectRootPath.DS.'public_html/src/model');
	defined('ViewPath') ? null : define('ViewPath', ProjectRootPath.DS.'public_html/src/view');
	defined('ControllerPath') ? null : define('ControllerPath', ProjectRootPath.DS.'public_html/src/controller');

	// REQUIRE NEEDED FILES BELOW.

	// REQUIRE HELPERS

	// Database factory for db-type instances.
	require_once(HelperPath.DS.'config.php');
	require_once(HelperPath.DS.'Strings.php');
	require_once(HelperPath.DS.'interfaces/iFactory.php');
	require_once(HelperPath.DS.'db/DB_Base_Factory.php');
	require_once(HelperPath.DS.'db/DB_Factory_PDO.php');

	require_once(HelperPath.DS.'interfaces/iAbstractFactory.php');
	require_once(HelperPath.DS.'db/DB_Factory.php');


	require_once(HelperPath.DS.'DatabaseAccessModel.php');
	require_once(HelperPath.DS.'UserRepository.php');

	// REQUIRE MODELS
	require_once(ModelPath.DS.'UserModel.php');
	require_once(ModelPath.DS.'SessionModel.php');

	// REQUIRE VIEWS
	require_once(ViewPath.DS.'LoginView.php');
	require_once(ViewPath.DS.'CookieStorageView.php');
	require_once(ViewPath.DS.'RegisterView.php');

	// REQUIRE CONTROLLERS
	require_once(ControllerPath.DS.'RegisterController.php');
	require_once(ControllerPath.DS.'LoginController.php');

