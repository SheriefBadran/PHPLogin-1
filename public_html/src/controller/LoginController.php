<?php

require_once(HelperPath.DS.'UserRepository.php');
require_once(ModelPath.DS.'SessionModel.php');
require_once(ViewPath.DS.'LoginView.php');
require_once(ViewPath.DS.'CookieStorageView.php');

class LoginController {

    private $userRepository;
    private $sessionModel;
    private $view;
    private $autoLogin;

    // Constructor, connects all the layers
    public function __construct() {
        $this->sessionModel = new SessionModel();
        $this->userRepository =  new UserRepository();
        $this->loginView = new LoginView($this->sessionModel);
        $this->autoLogin = new CookieStorageView();
    }

    public function runLoginLogic() {

        // TODO: Break this out to the SessionModel.
        if ($this->sessionModel->sessionStolen()) {
            
            $this->autoLogin->autoLoginCookieRemove();
            $this->sessionModel->throwOutUser($this->autoLogin, false);
            exit;
        }

        if (!$this->sessionModel->isLoggedIn() && $this->loginView->registerUser()) {
            
            $registerController = new RegisterController();
            $result = $registerController->runRegisterLogic();

            if ($result) {
                
                $this->sessionModel->setUsernameInputValue($result);
                $this->loginView->redirectToRegisterSuccess();
            }

            exit;
        }

        // LOGIN/RELOAD WITH COOKIES ONLY
        if(!$this->sessionModel->isLoggedIn() && $this->autoLogin->autoLoginApproved())
        {

            $username = $this->autoLogin->getUsername();
            $uniqueId = $this->autoLogin->getUniqueId();
            $user = $this->userRepository->getUser($username, $uniqueId);

            if ($user && $this->autoLogin->autoLoginCreationDate($this->userRepository, $uniqueId))
            {

                $this->loginView->redirectToMemberArea($this->autoLogin);
                exit;
            }
            else
            {

                $this->loginView->throwOutUser($this->autoLogin, true);
            }
        }

        $username = $this->loginView->getPostedUsername();
        $password = $this->loginView->getPostedPassword();
        // If a user tries to login, the input is checked and validated.
        if($this->loginView->onClickLogin() && $this->loginView->validates())
        {                
            try
            {

                $user = $this->authenticateUser() ? $this->userRepository->getUser($username) : false;

                if ($user && $this->loginView->rememberMe()) {

                    $this->autoLogin->autoLoginCookie($username, $user->getUniqueId(), $this->userRepository);
                    $this->loginView->redirectToMemberArea($this->autoLogin, true);
                }
                else {

                    $this->loginView->redirectToMemberArea($this->autoLogin, false);
                }

                exit;
            }
            catch (\Exception $e)
            {

                $this->loginView->saveAuthErrorMsg();
            }
        }
        // If a user tries to logout, the session is returned to null.
        elseif ($this->loginView->onClickLogout())
        {

            $this->autoLogin->autoLoginCookieRemove();
            $this->loginView->doLogout();
            exit;
        }

        return $this->loginView->showPage();
    }

    protected function authenticateUser () {

        $username = $this->loginView->getPostedUsername();
        $password = $this->loginView->getPostedPassword();

        $isAuthenticated = $this->userRepository->authenticateUser($username, $password);

        if ($isAuthenticated) {
            
            $this->sessionModel->doLogin($username);
            return true;
        }
        else {

            throw new \Exception();
        }

    }
}