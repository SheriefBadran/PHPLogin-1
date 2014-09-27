<?php

require_once('../data/pathConfig.php');
require_once(HelperPath.DS.'HTMLView.php');
require_once(ControllerPath.DS.'LoginController.php');


session_start();

// Making the session contain info of what browser started the session, to prevent hijacking of session.
if(!isset($_SESSION["httpAgent"]))
{
    $_SESSION["httpAgent"] = $_SERVER["HTTP_USER_AGENT"];
}

// if (!isset($_SESSION['LoginValues'])) {
	
// 	$_SESSION['RegisterValues']['username'] = '';
// }

$mainView = new HTMLView();
$loginController = new LoginController();

$htmlBody = $loginController->runLoginLogic();

$mainView->echoHTML($htmlBody);