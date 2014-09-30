<?php

require_once('../data/pathConfig.php');
require_once(HelperPath.DS.'Strings.php');
require_once(HelperPath.DS.'HTMLView.php');
require_once(ControllerPath.DS.'LoginController.php');

session_start();

// Making the session contain info of what browser started the session, to prevent hijacking of session.
if(!isset($_SESSION[Strings::$httpSessionUserAgent]))
{
    $_SESSION[Strings::$httpSessionUserAgent] = $_SERVER[Strings::$httpServerUserAgent];
}

$mainView = new HTMLView();
$loginController = new LoginController();

$htmlBody = $loginController->runLoginLogic();

$mainView->echoHTML($htmlBody);