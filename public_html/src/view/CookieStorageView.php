<?php

require_once(HelperPath.DS.'UserRepository.php');

class CookieStorageView {

    private static $cookieUsername = "username";
    private static $cookieToken = "token";
    private static $emptyString = "";
    private static $exptime = 20; // 60*60*24*30


    public function cookieUsernameIsSet () {

        return isset($_COOKIE[Strings::$cookieUsername]);
    }

    public function cookieTokenIsSet () {

        return isset($_COOKIE[Strings::$cookieToken]);
    }

    public function autoLoginApproved () {

        return isset($_COOKIE[Strings::$cookieToken]) && isset($_COOKIE[Strings::$cookieUsername]);
    }

    public function getUsername () {

        return $_COOKIE[Strings::$cookieUsername];
    }

    public function getUniqueId () {

        return $_COOKIE[Strings::$cookieToken];
    }

    // Creating the cookies for automatic login.
    public function autoLoginCookie($username, $token, UserRepository $userRepository) {

        $time = time()+Strings::$exptime;

        setcookie(Strings::$cookieUsername, $username, $time);
        setcookie(Strings::$cookieToken, $token, $time);

        // Saves the creation date of the cookies, to avoid manipulation.
        $userRepository->saveCookieExpTime($token, $time);
    }

    // Removing cookies for automatic login.
    public function autoLoginCookieRemove()
    {
        setcookie(Strings::$cookieUsername, Strings::$emptyString, 1);
        setcookie(Strings::$cookieToken, Strings::$emptyString, 1);
    }

    // Function to check the creation date of the autoLogin cookie
    public function autoLoginCreationDate(UserRepository $userRepository, $uniqueId) {


        $username = $this->getUsername();

        try {

            $cookieExpTime = $userRepository->getCookieExpTime($uniqueId);
        }
        catch(Exception $e) {

            return false;
        }

        return ($cookieExpTime < time()) ? false : true;
    }
}