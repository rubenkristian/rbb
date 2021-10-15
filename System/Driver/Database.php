<?php

namespace System\Driver;

class Database{
    public static $dbname;
    public static $host;
    public static $username;
    public static $password;
    public static $port;
    public static $charset;
    public static $collate;

    public function __construct(&$dbsetting){
        self::$dbname   = $dbsetting['dbname'];
        self::$host     = $dbsetting['hostname'];
        self::$username = $dbsetting['username'];
        self::$password = $dbsetting['password'];
        self::$port     = $dbsetting['port'];
        self::$charset  = $dbsetting['charset'];
        self::$collate  = $dbsetting['collate'];
    }
}