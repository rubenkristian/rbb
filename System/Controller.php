<?php
/**
 * Controller.php
 * 
 * Controller route request
 * 
 * @category Controller
 * @package System 
 * @author kristian ruben
 */
namespace System;

class Controller{
	public static $classLoad = []; // array for hold class object

	static function initRequest($req){ // hold class request
		self::$classLoad["req"] =& $req;
	}

	static function initResponse($res){ // hold class response
		self::$classLoad["res"] =& $res;
	}

	public function load($name, $class){ // manual load class object
		self::$classLoad[$name] =& $class;
	}

	public function loadLib($name, $class){
		$class = 'App\\Library\\'.$class;
		self::$classLoad[$name] = new $class;
	}
	
	public function loadLibClass($name, $class) {
		self::$classLoad[$name] = new $class;
	}

	static function autoloadLib(&$autoload){ // autoloader for Library used
		foreach($autoload as $name => $classLib){
			$class = 'App\\Library\\'.$classLib;
			self::$classLoad[$name] = new $class();
		}
	}

	static function setDBSetting($dbsetting){ // database configuration
		self::$classLoad['dbsetting'] = $dbsetting;
	}

	public function loadModel($name, $class){ // load model and create object properties with name to hold class object
		$this->$name = $class;
		$this->$name->setDB($this->dbsetting);
	}

	public function NotFound(){
		$this->res->status(404)->render("page404", array("title"=>"404 NOT FOUND"));
	}

	public function __get($name){ // magic properties
		return self::$classLoad[$name]; // return object in array by name
	}
}