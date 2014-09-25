<?php

require_once(ModelPath.DS.'SessionModel.php');
	class RegisterView {

		private $mainView;
		private $sessionModel;


		private static $usernameErrorMessage = "Användarnamnet har för få tecken. Minst 3 tecken.";
		private static $passwordErrorMessage = "Lösenordet har för få tecken. Minst 6 tecken.";
		private static $passwordsNotMatchingErrorMessage = "Lösenorden matchar inte.";
		private static $badCharsErrorMessage = "Användarnamnet innehåller ogiltiga tecken.";

		function __construct () {

			$this->mainView = new HTMLView();
			$this->sessionModel = new SessionModel();
		}

		public function getRegisterFormHTML ($usernameErrMsg = '', $passwordErrMsg = '') {

			// IF cookie with errors is set render a sertain view.
			$usernameResponse = '';
			$passwordResponse = '';

			// TODO: Breake this out to a function that takes $xErrMsg and $xResponse as params.
			if ($usernameErrMsg != '') {
					
				$usernameResponse = '<p>' . $usernameErrMsg . '</p>';
			}

			if ($passwordErrMsg != '') {

				$passwordResponse = '<p>' . $passwordErrMsg . '</p>';
			}

			$registerHTML = 
			'<a href="?login">Tillbaka</a>' .
			'<h2>Ej Inloggad, Registrerar användare</h2>' .

			'<form id="login" enctype="multipart/form-data" method="post" action="?registrera">' .
				'<fieldset>' .
					'<legend>Registrera ny användare - Skriv in användarnamn och lösenord</legend>' .
					$usernameResponse .
					$passwordResponse .
					'<label for="username">Användarnamn : </label>' .
					'<input type="text" name="username" value="' . $_SESSION['RegisterValues']['username'] . '" maxlength="30" id="username" /> ' .

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

		public function renderRegisterForm (array $errorMessages) {

			$numberOfErrors = count($errorMessages);

			if ($numberOfErrors === 0) {
				
				$registerHTML = $this->getRegisterFormHTML();
			}
			else if ($numberOfErrors === 1) {

				if (array_key_exists('username', $errorMessages)) {
					
					$registerHTML = $this->getRegisterFormHTML($errorMessages['username'], '');
				}
				else {

					$registerHTML = $this->getRegisterFormHTML('', $errorMessages['password']);	
				}
			}
			else {

				$registerHTML = $this->getRegisterFormHTML($errorMessages['username'], $errorMessages['password']);
			}

			echo $this->mainView->echoHTML($registerHTML);
		}

		public function renderLogoutView ($isDefaultLogout = true) {

			$isDefaultLogout ? $this->RenderLoginForm(self::$logOutSuccessMessage)
							 : $this->RenderLoginForm(self::$corruptCookieLogoutMessage);
		}

		public function getUsername () {

			// Is called from LoginController
			if (isset($_POST['username'])) {
				
				return $_POST['username'];
			}
		}

		public function getPassword () {

			// Is called from LoginController
			if (isset($_POST['password'])) {
				
				return $_POST['password'];
			}
		}

		public function getConfirmedPassword () {

			if (isset($_POST['confirmpassword'])) {
				
				return $_POST['confirmpassword'];
			}
		}

		public function userPressRegisterButton () {

			return isset($_POST['submit']);
		}

		public function validate () {

			$errorMessages = array();

			$badCharsExist = false;
			$cleanUsername = null;

			$username = $this->getUsername();
			$password = $this->getPassword();
			$confirmedPassword = $this->getConfirmedPassword();

			// strip_tags
			// var_dump(trim($username));
			// var_dump(htmlspecialchars($username));
			// var_dump(strip_tags($username));
			// var_dump(strip_tags($username));
			// var_dump(strpbrk($username, '<>"".') !== false);
			// die();

			if ($username == null || strlen($username) < 3) {

				$errorMessages['username'] = self::$usernameErrorMessage;
				$this->sessionModel->setUsernameInputValue($username);
			} 
			
			else if (strpbrk($username, '<>""./') !== false) {

				$errorMessages['username'] = self::$badCharsErrorMessage;
				$cleanedUsername = strip_tags($username);
				// $this->sessionModel->setUsernameInputValue($cleanedUsername);
			}

			if ($password == null || strlen($password) < 6) {

				$errorMessages['password'] = self::$passwordErrorMessage;
				$this->sessionModel->setUsernameInputValue($username);
			}

			else if ($confirmedPassword !== $password) {

				$errorMessages['password'] = self::$passwordsNotMatchingErrorMessage;
				$this->sessionModel->setUsernameInputValue($username);
				// return self::$passwordsNotMatchingErrorMessage;
			}

			if (count($errorMessages) > 0) {
				
				// $this->sessionModel->resetUsernameInputValue();
			}

			return (count($errorMessages) === 0) ? true : $errorMessages;
		}

		public function resetFormInputFields () {

			$this->sessionModel->resetUsernameInputValue();
		}

		public function getLoginErrorMessage () {

			$errorMessage;
			$_SESSION['LoginValues']['username'] = $this->GetUsername();

			return self::$loginErrorMessage;
		}
	}