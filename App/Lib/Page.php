<?php
namespace Lib;

use RuntimeException;

class Page
{
    protected $file;
    protected $vars = [];

    public function __construct($file)
    {
        if (!is_string($file) || empty($file))
        {
            throw new RuntimeException('view not valid');
        }

        $this->file = __DIR__.'/../../Public/pages/' . $file . '.php';
    }

    public function send()
    {
        exit($this->display());
    }

    public function addVar($var, $value)
    {
        if (!is_string($var) || is_numeric($var) || empty($var))
        {
            throw new RuntimeException('rtfm');
        }

        $this->vars[$var] = $value;
    }

    public function display()
    {
        if (!file_exists($this->file))
        {
            throw new RuntimeException('file does not exist');
        }

        extract($this->vars);

        require $this->file;
    }
}
