<?php
namespace System;
use System\Driver\PDODriver;

class Database{
    private $db;
    public function __construct(&$settings){
        $pdoDrive = new PDODriver($settings);
        $this->db = &$pdoDrive;
    }

    public function getDB(){
        return $this->db;
    }
}