<?php
namespace Model\News;

interface NewsManagerInterface {

    public function getNewsList($debut = -1, $limite = -1);

    public function getNewsById($id);

    public function delNews($id);

    public function saveNews(News $news);

    public function addNews(News $news);

    public function updateNews(News $news);

    public function countNews();
}
