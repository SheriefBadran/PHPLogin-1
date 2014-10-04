<?php

	class Strings {

	    public static $usernameResponseMessage = "Användarnamn saknas.";
	    public static $usernamePasswordMessage = "Lösenord saknas.";
	    public static $loginSuccessMessage = "Inloggning lyckades.";
	    public static $logoutSuccessMessage = "Du har nu loggat ut.";
	    public static $cookieLoginSuccessMessage = "Inloggning lyckades via cookies.";
	    public static $rememberMeSuccessMessage = "Inloggning lyckades och vi kommer ihåg dig nästa gång.";
	    public static $registerSuccessMessage = "Registrering av ny användare lyckades.";
	    public static $cookieManipulationErrorMessage = "Felaktig information i cookie.";
	    public static $AuthenticationErrorMessage = "Felaktigt användarnamn och/eller lösenord.";
	    public static $hashType = 'sha256';
	    public static $headerRedirectString = "Location: index.php";
	    public static $emptyString = '';
	    public static $username = "username";
	    public static $password = "password";
	    public static $passwordPostIndex = "password";
	    public static $stayLoggedInPostIndex = "stayLoggedIn";
	    public static $loginButtonPostIndex = "loginButton";
	    public static $registerGetIndex = "registrera";
	    public static $logoutGetIndex = "logout";
	    public static $httpSessionUserAgent = "httpAgent";
	    public static $httpServerUserAgent = "HTTP_USER_AGENT";
	    public static $typeStringException = "Parameter must be of type string.";
	    public static $typeBoolException = "Second parameter must be of type boolean.";

	    // Specific to CookieStorageView.php
	    public static $cookieUsername = "username";
	    public static $cookieToken = "token";
	    public static $exptime = 20; // 60*60*24*30

	    // Specific for SessionModel.php
	    public static $message = "message";
	    public static $inputValues = "RegisterValues";
	    public static $sessionLocation = "LoggedIn";
    	public static $sessionUsername = "Username";

    	// Specific for RegisterView.php
		public static $usernameErrorMessage = "Användarnamnet har för få tecken. Minst 3 tecken.";
		public static $passwordErrorMessage = "Lösenordet har för få tecken. Minst 6 tecken.";
		public static $passwordsNotMatchingErrorMessage = "Lösenorden matchar inte.";
		public static $badCharsErrorMessage = "Användarnamnet innehåller ogiltiga tecken.";
		public static $userExistErrorMessage = "Användarnamnet är redan upptaget.";	
		public static $confirmpassword = 'confirmpassword';
		public static $postSubmitString = 'submit';
		public static $invalidChars = '<>""./';
		// public static $pregReplaceInvalidCharsPattern = '/\W/';
		public static $loginValuesSessionIndex = 'LoginValues';
	}