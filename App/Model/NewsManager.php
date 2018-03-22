<?php
namespace Model;

abstract class NewsManager {

    abstract public function getList($debut = -1, $limite = -1);

    abstract public function getOne($id);

    abstract protected function add(News $news);

    abstract protected function update(News $news);

    abstract public function delete($id);

    abstract public function count();

    public function save(News $news) {
        if ($news->isValid()) {
            $news->isNew() ? $this->add($news) : $this->update($news);
        } else {
            throw new RuntimeException('La news doit être valide pour être enregistrée');
        }
    }
}
