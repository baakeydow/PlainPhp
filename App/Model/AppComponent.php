<?php
namespace Model;

use PDO;
use Lib\Session;
use Lib\HTTPRequest;
use Controller\MainController;

class AppComponent {

    protected $ctrl,
              $session,
              $req;

    public function __construct(PDO $db)
    {
        $this->session = new Session;
        $this->ctrl = new MainController($db, $this->session);
        $this->req = new HTTPRequest;
    }

    public function getRoute($URI)
    {
        if ($URI === '/') {
            $this->ctrl->indexView();
        } else if ($URI === '/admin') {
            $this->controlAccess();
        } else if ($URI === '/out') {
            $this->session->kick('loging out');
        } else {
            require 'App/Web/404.html';
        }
    }

    private function controlAccess()
    {
        if ($this->session->isAuthenticated() || $this->login($this->req)) {
            $this->ctrl->adminView();
        } else {
            require 'App/Web/login.php';
        }
    }

    private function login(HTTPRequest $request)
    {
        if (!$request->postExists('username')) {
            return false;
        }
        $login = $request->postData('username');
        $password = $request->postData('passwd');
        $user = $this->ctrl->getCredentials($login, $password);
        if ($user) {
            $this->session->setAuthenticated(true, $user);
            $this->ctrl->save('users', $user, 'login');
            return true;
        }
        $this->session->setFlash('Username or Password Invalid ! try again...');
        return false;
    }
}
