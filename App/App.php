<?php
require 'AppComponent.php';

use Utils\DBFactory;

class App
{
    protected $db;

    public function __construct()
    {
        $this->db = DBFactory::getMysqlConnexionWithPDO();;
    }

    public function run()
    {
        $strategy = new AppComponent($this->db);
        $strategy->getRoute(strtok($_SERVER['REQUEST_URI'], '?'));
    }
}

// Be careful not to have important things is the news database before playing this !!!!!!

// CREATE USER 'news'@'localhost' IDENTIFIED BY 'newspass';

// DROP DATABASE if exists news;
// CREATE DATABASE news;
// USE news;
// CREATE TABLE `news` (
//   `id` bigint(20) unsigned NOT NULL auto_increment,
//   `author` varchar(30) NOT NULL,
//   `title` varchar(100) NOT NULL,
//   `content` text NOT NULL,
//   `dateAdded` datetime NOT NULL,
//   `dateModif` datetime NOT NULL,
//   PRIMARY KEY (`id`),
//   UNIQUE KEY `title` (`title`),
//   KEY (`author`, `title`)
// ) DEFAULT CHARSET=utf8;
// CREATE TABLE `users` (
//   `id` bigint(20) unsigned NOT NULL auto_increment,
//   `nickname` varchar(30) NOT NULL,
//   `email` varchar(70) default NULL,
//   `password` varchar(256) default NULL,
//   `accessLevel` tinyint(3) unsigned NOT NULL default '0',
//   `creationDate` datetime NOT NULL,
//   `lastAccess` datetime NOT NULL,
//   PRIMARY KEY (`id`),
//   UNIQUE KEY `email` (`email`),
//   UNIQUE KEY `nickname` (`nickname`),
//   KEY (`email`, `nickname`)
// ) DEFAULT CHARSET=utf8;
// INSERT INTO users SET nickname='defaultNickName', email='defaultEmail@email.com', password='pwd', accessLevel='1', creationDate=NOW(), lastAccess=NOW();

// GRANT ALL PRIVILEGES on news.* to 'news'@'localhost' IDENTIFIED BY 'newspass';
// FLUSH PRIVILEGES;
// SHOW GRANTS FOR 'news'@'localhost';
