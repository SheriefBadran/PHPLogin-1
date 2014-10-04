<?php
require_once(ViewPath.DS.'RegisterView.php');
require_once(HelperPath.DS.'UserRepository.php');

	class RegisterController {

		private $registerView;
		private $userRepository;

		public function __construct () {

			$this->registerView = new RegisterView();
			$this->userRepository = new UserRepository();
		}

		public function runRegisterLogic () {

			$result = array();

			if ($this->registerView->userPressRegisterButton()) {
				
				$result = $this->registerView->validate();

				// If result render register form.
				if ($result === true) {

					$uniqueId = $this->userRepository->generateUniqueId();
					$username = $this->registerView->getUsername();
					$password = $this->registerView->getPassword();

					try {

						$newUser = $this->userRepository->makeUser($uniqueId, $username, $password);
					}
					catch (Exception $e) {

						$result = array();
						$this->registerView->setUsernameInputValue($username);
						$this->registerView->renderRegisterForm($result, true);
						exit;
					}

					$this->userRepository->createUser($newUser);
					
					$this->registerView->resetFormInputFields();
					return $this->registerView->getUsername();
				}
				else {

					$this->registerView->renderRegisterForm($result, false);
					$this->registerView->resetFormInputFields();
					return false;
				}

				$this->registerView->resetFormInputFields();
				return false;
				// exit;
			}

			$this->registerView->renderRegisterForm($result, false);
			$this->registerView->resetFormInputFields();
		}
	}

