<?php

require_once("src/view/MessageView.php");

class LoginView {
    private $sessionModel;
    private $messages;

    public function __construct(SessionModel $sessionModel)
    {
        $this->sessionModel = $sessionModel;
        $this->messages = new MessageView();
    }

    public function getPostedUsername () {

        if (isset($_POST['username'])) {
            
            return $_POST["username"];
        }
    }

    public function getPostedPassword () {

        if (isset($_POST['password'])) {
            
            return $_POST['password'] === '' ? '' : hash("sha256" ,$_POST['password']);
        }
    }

    public function rememberMe () {

        return isset($_POST["stayLoggedIn"]);
    }

    public function registerUser() {

        return isset($_GET['registrera']);
    }

    // Checks if the user has pressed the login button.
    public function onClickLogin() {
        if(isset($_POST["loginButton"]))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    // Checks if the user has pressed the logout button.
    public function onClickLogout() {
        if(isset($_GET['logout']))
        {
          return true;
        }
        else
        {
            return false;
        }
    }

    public function sessionCheck() {
        if ($_SESSION["httpAgent"] != $_SERVER["HTTP_USER_AGENT"])
        {
            return false;
        }

        return true;
    }

    // Renders the page according to the user being logged in or not.
    public function showPage() {

        if($this->sessionModel->getLoginStatus() === false || $this->sessionCheck() === false)
        {
            $username = isset($_POST["username"]) ? $_POST["username"] : "";

            $registeredUser = $this->sessionModel->getUsernameInputValue();

            if ($registeredUser != '') {
                
                $username = $registeredUser;
            }

            return "
            <a href='?registrera'>Registrera en användare</a>
            <h3>Ej inloggad</h3>
            <form action='?login' method='post' name='loginForm'>
                <fieldset>
                    <legend>Login - Skriv in användarnamn och lösenord</legend><p>"
                    . $this->messages->load() . "</p>
                    <label><strong>Användarnamn: </strong></label>
                    <input type='text' name='username' value='$username' />
                    <label><strong>Lösenord: </strong></label>
                    <input type='password' name='password' value='' />
                    <label><strong>Håll mig inloggad: </strong></label>
                    <input type='checkbox' name='stayLoggedIn' />
                    <input type='submit' value='Logga in' name='loginButton' />
                 </fieldset>
            </form>";
        }
        else
        {
            return "<h1>Välkommen!</h1>
                    <h3>" . $this->sessionModel->retriveUsername() . " är inloggad</h3>
                    <p>" . $this->messages->load() . "</p>
                    <a href='?logout'>Logga ut</a>";
        }
    }
}