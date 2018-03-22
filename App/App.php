<?php

use Strategy\SetENV;
use Utils\DBFactory;

class App
{
    public function __construct()
    {
        $this->db = DBFactory::getMysqlConnexionWithPDO();;
    }

    public function run()
    {
        $env = new SetENV($this->db);
        $env->getRoute(strtok($_SERVER['REQUEST_URI'], '?'));
    }
}

// CREATE USER 'news'@'localhost' IDENTIFIED BY 'newspass';
// CREATE DATABASE news;
// USE news;
// CREATE TABLE `news` (
//   `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
//   `author` varchar(30) NOT NULL,
//   `title` varchar(100) NOT NULL,
//   `content` text NOT NULL,
//   `dateAdded` datetime NOT NULL,
//   `dateModif` datetime NOT NULL,
//   PRIMARY KEY (`id`)
// ) DEFAULT CHARSET=utf8;
// GRANT ALL PRIVILEGES on news.* to 'news'@'localhost' IDENTIFIED BY 'newspass';
// FLUSH PRIVILEGES;
// SHOW GRANTS FOR 'news'@'localhost';
