<?php

require_once(HelperPath.DS.'UserRepository.php');

class CookieStorageView {
    private $cookieUsername = "username";
    private $cookieToken = "token";
    private $cookieCreationDate = "creationDate";
    private $cookieDatesFile = "CookieDates.txt";

    public function getCookieUsername() {
        return $this->cookieUsername;
    }

    public function getCookieToken() {
        return $this->cookieToken;
    }

    // public function getCookieCreationDate() {
    //     return $this->cookieCreationDate;
    // }

    // Creating the cookies for automatic login.
    public function autoLoginCookie($username, $token, $userRepository) {
        // $time = time()+60*60*24*30;
        $time = time()+20;

        setcookie('username', $username, $time);
        setcookie('token', $token, $time);
        // setcookie('creationDate', $time, $time);

        // Saves the creation date of the cookies, to avoid manipulation.
        $userRepository->saveCookieExpTime($token, $time);
        // $fp = fopen($this->cookieDatesFile, 'a');
        // fwrite($fp, $username . $time . PHP_EOL);
    }

    // Removing cookies for automatic login.
    public function autoLoginCookieRemove()
    {
        setcookie("username", "", 1);
        setcookie("token", "", 1);
        // setcookie("creationDate", "", 1);
    }

    // Function to check the creation date of the autoLogin cookie
    public function autoLoginCreationDate($userRepository) {

        $username = $_COOKIE[$this->getCookieUsername()];
        $user = $userRepository->makeUser($username);
        $cookieExpTime = (int)($userRepository->getCookieExpTime($user->getUniqueId()));

        return ($cookieExpTime < time()) ? false : true;
        // $cookieCreationDate = $_COOKIE[$this->getCookieCreationDate()];

        // $cookieDates = @file($this->cookieDatesFile);
        // if ($cookieDates === FALSE) {
        //     return false;
        // }

        // foreach ($cookieDates as $creationDate) {
        //     $creationDate = trim($creationDate);
        //     if ($creationDate === $username . $cookieCreationDate) {
        //         return true;
        //     }
        // }
        // return false;
    }
}