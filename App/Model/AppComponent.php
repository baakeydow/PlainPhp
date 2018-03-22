<?php
namespace Model;

use Lib\Session;
use Lib\HTTPRequest;

abstract class AppComponent {

    public function __construct($db)
    {
        $this->db = $db;
        $this->session = new Session;
        $this->req = new HTTPRequest;
    }

}
