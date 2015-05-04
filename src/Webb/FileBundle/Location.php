<?php

namespace Webb\FileBundle;

class Location {

    private $dir;
    private $web_root;

    public function __construct($root_dir, $web_dir, $upload_dir){
        $this->web_root = realpath($root_dir . '/../' . $web_dir);
        $this->dir = $upload_dir;
    }

    public function getWebRoot(){
        return $this->web_root;
    }

    public function getDir($subdir = null){
        return isset($subdir) ? $this->dir . '/'. $subdir : $this->dir;
    }

}