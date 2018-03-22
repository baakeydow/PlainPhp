<?php
namespace Model;

use Lib\User;
use Lib\HTTPRequest;

abstract class AppComponent {

    public function __construct($db)
    {
        $this->db = $db;
        $this->user = new User;
        $this->HTTPRequest = new HTTPRequest;
    }

}
