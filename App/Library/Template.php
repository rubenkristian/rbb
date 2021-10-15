<?php
namespace App\Library;

class Template{
    public $public;
    public $path = '';
    public $url;

    public function __construct(){
        $this->public = 'https://'.$_SERVER['HTTP_HOST'].$this->path.DS.'public/';
        
        $this->url = explode('?', $_SERVER['REQUEST_URI'], 2)[0];
    }
}