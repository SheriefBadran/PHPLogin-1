<?php
require_once(ViewPath.DS.'RegisterView.php');
require_once(ModelPath.DS.'SessionModel.php');

	class RegisterController {

		private $registerView;
		private $sessionModel;

		public function __construct () {

			$this->registerView = new RegisterView();
			$this->sessionModel = new SessionModel();

		}

		public function runRegisterLogic () {

			$errors = array();

			if ($this->registerView->userPressRegisterButton()) {
				
				$errors = $this->registerView->validate();

				// if errors render register form
				$this->registerView->renderRegisterForm($errors);

				// else render success view.


				$this->sessionModel->resetUsernameInputValue();
				$this->registerView->resetFormInputFields();
				exit;
			}

			$this->registerView->renderRegisterForm($errors);
		}
	}

