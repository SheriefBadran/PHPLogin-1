<?php

class LoginView {

    private $sessionModel;

    public function __construct(SessionModel $sessionModel) {
        $this->sessionModel = $sessionModel;
    }

    public function validates () {

        $validates = true;

        if ($this->getPostedUsername() == Strings::$emptyString) {

            $this->sessionModel->save(Strings::$usernameResponseMessage);
            $validates = false;
        }
        else if ($this->getPostedPassword() == Strings::$emptyString) {

            $this->sessionModel->save(Strings::$usernamePasswordMessage);
            $validates = false;
        }

        return $validates;
    }

    public function redirectToMemberArea (CookieStorageView $autoLogin, $isAutoLogin = false) {

        $successMessage = $isAutoLogin ? Strings::$rememberMeSuccessMessage :
                                        Strings::$loginSuccessMessage;

        if (!$this->sessionModel->isLoggedIn() && $autoLogin->autoLoginApproved()) {

            $successMessage = Strings::$cookieLoginSuccessMessage;
            $this->sessionModel->doLogin($autoLogin->getUsername());
        }

        $this->sessionModel->save($successMessage);
        header(Strings::$headerRedirectString);
        exit;
    }

    public function redirectToRegisterSuccess () {

        $this->sessionModel->save(Strings::$registerSuccessMessage);
        header(Strings::$headerRedirectString);
    }

    public function throwOutUser (CookieStorageView $autoLogin, $messageExist = true) {

        if (!is_bool($messageExist)) {

            throw new \Exception(Strings::$typeStringException);
        }

        if ($messageExist) {
            
            $this->sessionModel->save(Strings::$cookieManipulationErrorMessage);
        }

        $autoLogin->autoLoginCookieRemove();
    }

    public function saveAuthErrorMsg () {

        $this->saveMessage(Strings::$AuthenticationErrorMessage);
    }

    public function saveMessage ($message) {

        if (!is_string($message)) {
            
            throw new \Exception(Strings::$typeStringException);
        }

        $this->sessionModel->save($message);
    }

    public function doLogout () {

        $this->sessionModel->doLogout(Strings::$logoutSuccessMessage);
    }

    public function getPostedUsername () {

        if (isset($_POST[Strings::$username])) {
            
            return $_POST[Strings::$username];
        }
    }

    public function getPostedPassword () {

        if (isset($_POST[Strings::$passwordPostIndex])) {
            
            return $_POST[Strings::$passwordPostIndex] === Strings::$emptyString ? Strings::$emptyString : 
                                            hash(Strings::$hashType ,$_POST[Strings::$passwordPostIndex]);
        }
    }

    public function rememberMe () {

        return isset($_POST[Strings::$stayLoggedInPostIndex]);
    }

    public function registerUser() {

        return isset($_GET[Strings::$registerGetIndex]);
    }

    // Checks if the user has pressed the login button.
    public function onClickLogin() {
        if(isset($_POST[Strings::$loginButtonPostIndex]))
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
        if(isset($_GET[Strings::$logoutGetIndex]))
        {
          return true;
        }
        else
        {
            return false;
        }
    }

    public function sessionCheck() {
        if ($_SESSION[Strings::$httpSessionUserAgent] != $_SERVER[Strings::$httpServerUserAgent])
        {
            return false;
        }

        return true;
    }

    // Renders the page according to the user being logged in or not.
    public function showPage() {

        if($this->sessionModel->isLoggedIn() === false || $this->sessionCheck() === false)
        {
            $username = isset($_POST[Strings::$username]) ? $_POST[Strings::$username] : Strings::$emptyString;

            $registeredUser = $this->sessionModel->getUsernameInputValue();

            if ($registeredUser != Strings::$emptyString) {
                
                $username = $registeredUser;
            }

            return "
            <a href='?registrera'>Registrera en användare</a>
            <h3>Ej inloggad</h3>
            <form action='?login' method='post' name='loginForm'>
                <fieldset>
                    <legend>Login - Skriv in användarnamn och lösenord</legend><p>"
                    . $this->sessionModel->load() . "</p>
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
                    <p>" . $this->sessionModel->load() . "</p>
                    <a href='?logout'>Logga ut</a>";
        }
    }
}