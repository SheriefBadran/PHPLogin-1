<?php

class SessionModel {

    // Checks the credentials, if correct the LoggedIn session is set to true.
    public function doLogin($username) {

        $_SESSION[Strings::$sessionLocation] = true;
        $_SESSION[Strings::$sessionUsername] = $username;
    }

    // When a user wants to logout, the session is returned to be null.
    public function doLogout($message = '') {

        $_SESSION[Strings::$sessionLocation] = null;
        $this->save($message);
        header(Strings::$headerRedirectString);
        exit;
    }

    // Function for checking if a user is currently logged in or not.
    public function isLoggedIn() {
        if(!(isset($_SESSION[Strings::$sessionLocation])) || $_SESSION[Strings::$sessionLocation] === false)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function retriveUsername() {

        return $_SESSION[Strings::$sessionUsername];
    }

    public function setUsernameInputValue ($value) {

        $_SESSION[Strings::$inputValues][Strings::$username] = $value;
    }

    public function resetUsernameInputValue () {

        $_SESSION[Strings::$inputValues][Strings::$username] = Strings::$emptyString;
    }

    public function getUsernameInputValue () {

        $userNameInputValue = isset($_SESSION[Strings::$inputValues][Strings::$username]) ?
                                    $_SESSION[Strings::$inputValues][Strings::$username] : Strings::$emptyString;

        $this->resetUsernameInputValue();

        return $userNameInputValue;
    }

    public function sessionStolen () {

        return $_SESSION[Strings::$httpSessionUserAgent] !== $_SERVER[Strings::$httpServerUserAgent];
    }

    public function throwOutUser () {

        $_SESSION[Strings::$sessionLocation] = null;
        unset($_SESSION[Strings::$httpSessionUserAgent]);
        header(Strings::$headerRedirectString);
        exit;
    }

    // These functions were originally located in a MessageView class that now is deleted.
    // Function for saving a message to the session.
    public function save($string) {
        $_SESSION[Strings::$message] = $string;
    }

    // Function for retrieving the message, present it, and delete it.
    public function load() {
        if(isset($_SESSION[Strings::$message]))
        {
            $ret = $_SESSION[Strings::$message];

        }
        else
        {
            $ret = Strings::$emptyString;
        }

        $_SESSION[Strings::$message] = Strings::$emptyString;

        return $ret;
    }
}