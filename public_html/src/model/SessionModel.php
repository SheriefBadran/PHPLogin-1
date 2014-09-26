<?php


class SessionModel {
    private $sessionLocation = "LoggedIn";
    private $sessionUsername = "Username";

    public function __construct() {
        // NOTE - implement stuff.
    }

    // Checks the credentials, if correct the LoggedIn session is set to true.
    public function doLogin($username) {

        $_SESSION[$this->sessionLocation] = true;
        $_SESSION[$this->sessionUsername] = $username;
        //$_SESSION["httpAgent"] = $_SERVER["HTTP_USER_AGENT"];
    }

    // When a user wants to logout, the session is returned to be null.
    public function doLogout() {
        $_SESSION[$this->sessionLocation] = null;
    }

    // Function for checking if a user is currently logged in or not.
    public function getLoginStatus() {
        if(!(isset($_SESSION[$this->sessionLocation])) || $_SESSION[$this->sessionLocation] === false)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    // Function for retrieving the username of the user currently logged in.
    public function retriveUsername() {
        return $_SESSION[$this->sessionUsername];
    }

    // Do note that I'm just using fake data here. When working with real data this will be of much more use.
    // Then a username can be sent in, the token located in the DB and returned.
    public function retriveToken($username) {
        //if($username == "Admin" && $this->getLoginStatus() == true)
        //{
            return "fsdfsf2uy39fy392f923oif23";
        //}
    }

    // sb222rf
    public function setUsernameInputValue ($value) {

        $_SESSION['RegisterValues']['username'] = $value;
    }

    public function resetUsernameInputValue () {

        $_SESSION['RegisterValues']['username'] = '';
    }

    public function getUsernameInputValue () {

        $userNameInputValue = isset($_SESSION['RegisterValues']['username']) ? $_SESSION['RegisterValues']['username'] : '';
        $this->resetUsernameInputValue();

        return $userNameInputValue;
    }
}