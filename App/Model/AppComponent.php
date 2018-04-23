<?php
namespace Model;

use PDO;
use Lib\Page;
use Lib\Session;
use Lib\HTTPRequest;
use Model\News\NewsManager;
use Model\News\News;
use Model\Users\User;
use Controllers\IndexController;
use Controllers\AdminController;

class AppComponent {

    protected $db,
              $newsManager,
              $session,
              $req,
              $page;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->newsManager = new NewsManager($db);
        $this->session = new Session;
        $this->req = new HTTPRequest;
        $this->page = new Page('index');
    }

    public function getRoute($URI)
    {
        if ($URI === '/') {
            $Ctrl = new IndexController($this->db);
            $Ctrl->index();
        } else if ($URI === '/admin') {
            $Ctrl = new AdminController($this->db);
            $this->controlAccess($Ctrl);
        } else if ($URI === '/out') {
            $this->session->kick('loging out');
        } else {
            require 'App/Web/404.html';
        }
    }

    private function controlAccess($Ctrl)
    {
        if ($this->session->isAuthenticated() || $this->login($this->req)) {
            $Ctrl->index();
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
        $Ctrl = new AdminController($this->db);
        $user = $Ctrl->getCredentials($login, $password);
        if ($user) {
            $this->session->setAuthenticated(true, $user);
            $Ctrl->saveAddedUser($user, 'login');
            return true;
        }
        $this->session->setFlash('Username or Password Invalid ! try again...');
        return false;
    }
}
