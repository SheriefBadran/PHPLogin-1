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

					// die('The user '. $this->registerView->getUsername() . ' is registrated!');
					$this->registerView->resetFormInputFields();
					return $this->registerView->getUsername();
				}
				else {

					$this->registerView->renderRegisterForm($result);
					$this->registerView->resetFormInputFields();
					return false;
				}

				// else render success view.



				$this->registerView->resetFormInputFields();
				return false;
				// exit;
			}

			$this->registerView->renderRegisterForm($result);
			$this->registerView->resetFormInputFields();
		}
	}

