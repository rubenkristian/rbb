<?php

namespace System;
use System\Response;
use System\Controller as Ctrl;
class Core{
	protected $uri;
	protected $_c;
	protected $_m;
	protected $_p;
	protected $_default = "";
	protected $_404 = "";
	protected $loadClass;
	protected $req;

	protected $autoload;

	protected $dbsetting;

	public function __construct(&$req){
		$this->req =& $req;
		$this->uri =& $req->getUri();
	}

	public function bootstrap(&$path, &$autoload, &$dbsetting){
		$this->autoload =& $autoload;
		$this->dbsetting =& $dbsetting;
		$this->_default = $path['default'];
		$this->_404 = $path['404'];

		$this->_c = ucfirst(strtolower($this->uri->getMainPath()));
		$this->_m = strtolower($this->uri->getSecondPath());
		$this->_p = $this->uri->getParamsPaths();
		if(empty($this->_c)){
			$this->_c = $this->_default;
		}

		if(empty($this->_m)){
			$this->_m = 'index';
		}
		$path = APP.DS.'Controller'.DS.$this->_c.".php";
		if(!file_exists($path)){
			$this->_c = $this->_404;
		}
		$class = 'App\\Controller\\'.$this->_c;
		Ctrl::autoloadLib($this->autoload);
		Ctrl::setDBSetting($this->dbsetting);
		Ctrl::initRequest($this->req);
		Ctrl::initResponse(new Response(Ctrl::$classLoad));
		$this->loadClass = new $class();
		if(!method_exists($this->loadClass, $this->_m)){
			$this->_m = 'NotFound';
		}
		$this->LoadRender();
	}

	private function LoadRender(){
		call_user_func_array(array($this->loadClass, $this->_m), $this->_p);
	}
}