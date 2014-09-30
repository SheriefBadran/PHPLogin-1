<?php

require_once(HelperPath.DS.'UserRepository.php');
require_once(ModelPath.DS.'SessionModel.php');

	class RegisterView {

		private $mainView;
		private $sessionModel;
		private $userRepository;


		private static $usernameErrorMessage = "Användarnamnet har för få tecken. Minst 3 tecken.";
		private static $passwordErrorMessage = "Lösenordet har för få tecken. Minst 6 tecken.";
		private static $passwordsNotMatchingErrorMessage = "Lösenorden matchar inte.";
		private static $badCharsErrorMessage = "Användarnamnet innehåller ogiltiga tecken.";
		private static $userExistErrorMessage = "Användarnamnet är redan upptaget.";
		private static $username = 'username';
		private static $password = 'password';
		private static $confirmpassword = 'confirmpassword';
		private static $hashType = 'sha256';
		private static $postSubmitString = 'submit';
		private static $invalidChars = '<>""./';
		private static $loginValuesSessionIndex = 'LoginValues';


		private static $emptyString = '';

		function __construct () {

			$this->mainView = new HTMLView();
			$this->sessionModel = new SessionModel();
			$this->userRepository = new UserRepository();
		}

		public function getRegisterFormHTML ($usernameErrMsg = '', $passwordErrMsg = '') {

			// IF cookie with errors is set render a sertain view.
			$usernameResponse = Strings::$emptyString;
			$passwordResponse = Strings::$emptyString;

			// TODO: Breake this out to a function that takes $xErrMsg and $xResponse as params.
			if ($usernameErrMsg != Strings::$emptyString) {
					
				$usernameResponse = '<p>' . $usernameErrMsg . '</p>';
			}

			if ($passwordErrMsg != Strings::$emptyString) {

				$passwordResponse = '<p>' . $passwordErrMsg . '</p>';
			}

			$usernameInputValue = $this->sessionModel->getUsernameInputValue();

			$registerHTML = 
			'<a href="?login">Tillbaka</a>' .
			'<h2>Ej Inloggad, Registrerar användare</h2>' .

			'<form id="login" enctype="multipart/form-data" method="post" action="?registrera">' .
				'<fieldset>' .
					'<legend>Registrera ny användare - Skriv in användarnamn och lösenord</legend>' .
					$usernameResponse .
					$passwordResponse .
					'<label for="username">Användarnamn : </label>' .
					'<input type="text" name="username" value="' . $usernameInputValue . '" maxlength="30" id="username" /> ' .

					'<label for="password">Lösenord : </label>' .
					'<input type="password" name="password" maxlength="30" id="password" /> ' .

					'<label for="password">Repetera Lösenord : </label>' .
					'<input type="password" name="confirmpassword" maxlength="30" id="confirmpassword" /> ' .

					'<input type="submit" name="submit" id="submit" value="Registrera" />
				</fieldset>
			</form>';

			// $this->sessionModel->resetUsernameInputValue();

			return $registerHTML;			
		}

		private function userExist ($username) {

			return $this->userRepository->userExist($username);
		}

		public function renderRegisterForm (array $errorMessages) {

			$numberOfErrors = count($errorMessages);

			if ($numberOfErrors === 0) {
				
				$registerHTML = $this->getRegisterFormHTML();
			}
			else if ($numberOfErrors === 1) {

				if (array_key_exists(Strings::$username, $errorMessages)) {
					
					$registerHTML = $this->getRegisterFormHTML($errorMessages[Strings::$username], Strings::$emptyString);
				}
				else {

					$registerHTML = $this->getRegisterFormHTML(Strings::$emptyString, $errorMessages[Strings::$password]);	
				}
			}
			else {

				$registerHTML = $this->getRegisterFormHTML($errorMessages[Strings::$username], $errorMessages[Strings::$password]);
			}

			echo $this->mainView->echoHTML($registerHTML);
		}

		public function renderLogoutView ($isDefaultLogout = true) {

			$isDefaultLogout ? $this->RenderLoginForm(Strings::$logOutSuccessMessage)
							 : $this->RenderLoginForm(Strings::$corruptCookieLogoutMessage);
		}

		public function getUsername () {

			// Is called from LoginController
			if (isset($_POST[Strings::$username])) {
				
				return $_POST[Strings::$username];
			}
		}

		public function getPassword () {

			// Is called from LoginController
			if (isset($_POST[Strings::$password])) {
				
				return ($_POST[Strings::$password] == null || strlen($_POST[Strings::$password]) < 6) ? Strings::$emptyString :
						hash(Strings::$hashType, $_POST[Strings::$password]);
			}
		}

		protected function getConfirmedPassword () {

			if (isset($_POST[Strings::$confirmpassword])) {
				
				return ($_POST[Strings::$confirmpassword] == null || strlen($_POST[Strings::$confirmpassword]) < 6) ? Strings::$emptyString :
						hash(Strings::$hashType, $_POST[Strings::$confirmpassword]);
			}
		}

		public function userPressRegisterButton () {

			return isset($_POST[Strings::$postSubmitString]);
		}

		public function validate () {

			$errorMessages = array();

			$badCharsExist = false;
			$cleanUsername = null;

			$username = $this->getUsername();
			$password = $this->getPassword();
			$confirmedPassword = $this->getConfirmedPassword();

			if ($this->userExist($username)) {
				
				$errorMessages[Strings::$username] = Strings::$userExistErrorMessage;
				$this->sessionModel->setUsernameInputValue($username);
			}

			if ($username == null || strlen($username) < 3) {

				$errorMessages[Strings::$username] = Strings::$usernameErrorMessage;
				$this->sessionModel->setUsernameInputValue($username);
			} 
			
			else if (strpbrk($username, Strings::$invalidChars) !== false) {

				$errorMessages[Strings::$username] = Strings::$badCharsErrorMessage;
				$cleanUsername = strip_tags($username);
				$this->sessionModel->setUsernameInputValue($cleanUsername);
			}

			// If password is less than 6 chars, it is converted to empty string.
			if ($password === '') {

				// TODO: Break this code out in a separate function
				$errorMessages[Strings::$password] = Strings::$passwordErrorMessage;

				if (strpbrk($username, Strings::$invalidChars) !== false) {
					
					$cleanUsername = strip_tags($username);
					$this->sessionModel->setUsernameInputValue($cleanUsername);
				}
				else {

					$this->sessionModel->setUsernameInputValue($username);
				}
			}

			else if ($confirmedPassword !== $password) {

				// TODO: Break this code out in a separate function
				$errorMessages[Strings::$password] = Strings::$passwordsNotMatchingErrorMessage;

				if (strpbrk($username, Strings::$invalidChars) !== false) {
					
					$cleanUsername = strip_tags($username);
					$this->sessionModel->setUsernameInputValue($cleanUsername);
				}
				else {

					$this->sessionModel->setUsernameInputValue($username);
				}
				// return Strings::$passwordsNotMatchingErrorMessage;
			}


			return (count($errorMessages) === 0) ? true : $errorMessages;
		}

		public function resetFormInputFields () {

			$this->sessionModel->resetUsernameInputValue();
		}

		public function getLoginErrorMessage () {

			$errorMessage;
			$_SESSION[Strings::$loginValuesSessionIndex][Strings::$username] = $this->GetUsername();

			return Strings::$loginErrorMessage;
		}
	}