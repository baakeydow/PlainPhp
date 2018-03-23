<?php
namespace Model;

use PDO;
use Lib\Session;
use Lib\HTTPRequest;
use Model\News\NewsManager;

abstract class AppComponent {

    protected $db,
              $newsManager,
              $session,
              $req;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->newsManager = new NewsManager($db);
        $this->session = new Session;
        $this->req = new HTTPRequest;
    }

}
