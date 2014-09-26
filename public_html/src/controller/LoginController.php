<?php

require_once(HelperPath.DS.'UserRepository.php');
require_once(ModelPath.DS.'SessionModel.php');
require_once(ViewPath.DS.'LoginView.php');
require_once(ViewPath.DS.'MessageView.php');
require_once(ViewPath.DS.'CookieStorageView.php');

class LoginController {

    private $userRepository;
    private $sessionModel;
    private $view;
    private $messages;
    private $autoLogin;

    // Constructor, connects all the layers
    public function __construct() {
        $this->sessionModel = new SessionModel();
        $this->userRepository =  new UserRepository();
        $this->loginView = new LoginView($this->sessionModel);
        $this->messages = new MessageView();
        $this->autoLogin = new CookieStorageView();
    }

    public function runLoginLogic() {

        // TODO: Break this out to the SessionModel.
        if ($_SESSION["httpAgent"] !== $_SERVER["HTTP_USER_AGENT"]) {
            
            $this->autoLogin->autoLoginCookieRemove();
            $this->sessionModel->doLogout();
            unset($_SESSION["httpAgent"]);
            header('Location: index.php');
            exit;
        }

        if (!$this->sessionModel->getLoginStatus() && $this->loginView->registerUser()) {
            
            $registerController = new RegisterController();
            $result = $registerController->runRegisterLogic();

            if ($result) {
                
                $this->sessionModel->setUsernameInputValue($result);
                $this->messages->save("Registrering av ny användare lyckades.");
                header('Location: index.php');
            }

            exit;
        }

        // LOGIN/RELOAD WITH COOKIES ONLY
        if($this->sessionModel->getLoginStatus() == false && isset($_COOKIE[$this->autoLogin->getCookieUsername()]) && isset($_COOKIE[$this->autoLogin->getCookieToken()]))
        {
            $username = $this->autoLogin->getUsername();
            $uniqueId = $this->autoLogin->getUniqueId();
            $user = $this->userRepository->makeUser($username, $uniqueId);

            if ($user && $this->autoLogin->autoLoginCreationDate($this->userRepository, $uniqueId))
            {
                try
                {
                    // Checks the username and password in the model, to see that it exists.
                    $this->sessionModel->doLogin($username);
                    $this->messages->save("Inloggning lyckades via cookies");
                    header('Location: index.php');
                    exit;
                }
                catch (\Exception $e)
                {

                    $this->messages->save("Felaktig information i cookie");
                    $this->autoLogin->autoLoginCookieRemove();
                }
            }
            else
            {
                $this->messages->save("Felaktig information i cookie");
                $this->autoLogin->autoLoginCookieRemove();
            }
        }

        $username = $this->loginView->getPostedUsername();
        $password = $this->loginView->getPostedPassword();
        // If a user tries to login, the input is checked and validated.
        if($this->loginView->onClickLogin())
        {
            if ($username == "")
            {
                $this->messages->save("Användarnamn saknas");
            }
            elseif ($password == "")
            {
                $this->messages->save("Lösenord saknas");
            }
            else
            {
                try
                {
                    // Checks the username and password in the sessionModel, to see that it exists.
                    $uniqueId = $this->userRepository->generateUniqueId();
                    $user = new UserModel($uniqueId, 'Admin', hash('sha256', 'Password'));

                    // $this->userRepository->createUser($user);
                    $isAuthenticated = $this->authenticateUser();

                    // If the user wanted to be remembered a cookie with a hashed password is generated.
                    if ($this->loginView->rememberMe() && $isAuthenticated)
                    {
                        // $token = $this->sessionModel->retriveToken($username);
                        // $this->autoLogin->autoLoginCookie($username, $token);

                        $user = $this->userRepository->makeUser($username);

                        if ($user) {

                            $this->autoLogin->autoLoginCookie($username, $user->getUniqueId(), $this->userRepository);

                            $this->messages->save("Inloggning lyckades och vi kommer ihåg dig nästa gång");
                            header('Location: index.php');
                            exit;   
                        }
                    }

                    $this->messages->save("Inloggning lyckades");
                    header('Location: index.php');
                    exit;
                }
                catch (\Exception $e)
                {
                    $this->messages->save("Felaktigt användarnamn och/eller lösenord");
                }
            }
        }
        // If a user tries to logout, the session is returned to null.
        elseif ($this->loginView->onClickLogout())
        {
            $this->autoLogin->autoLoginCookieRemove();
            $this->sessionModel->doLogout();
            $this->messages->save("Du har nu loggat ut");
            header('Location: index.php');
            exit;
        }

        return $this->loginView->showPage();
    }

    protected function authenticateUser () {

        $username = $this->loginView->getPostedUsername();
        $password = $this->loginView->getPostedPassword();

        $isAuthenticated = $this->userRepository->authenticateUser($username, $password);

        if ($isAuthenticated) {
            
            // Retrieve the authenticated a user object
            // TODO: Create a user factory.


            $this->sessionModel->doLogin($username);
            return true;
        }
        else {

            throw new \Exception();
        }

    }
}