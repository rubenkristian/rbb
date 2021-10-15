<?php
/**
 * @package System
 * @author kristian ruben
 * Class Response
 * public status
 * public render
 * public json
 * public redirect
 * protected parser
 */
namespace System;

class Response{
	private $obj; // properties to hold array object
	
	public function __construct(&$obj){
		$this->obj =& $obj; // initialized array object
		// var_dump($obj['temp']);
	}

	public function status($code){
		http_response_code($code);
		return $this; 
	}

	public function render($view, $data = array()){
		$path = APP.DS.'View'.DS.$view.'.php';
		if(file_exists($path)){
			extract($data);

			ob_start();
			include($path);

			$strView = ob_get_contents();

			ob_end_clean();
			echo $this->parser($strView, $data);
		}
		unset($data);
		return $this;
	}

	protected function parser($template, $data, $minify=true){
		$matches = array();
		preg_match_all('/{{include+\s+[\w\/]+}}/', $template, $matches);
		extract($data);
		foreach ($matches[0] as $key => $value) {
			$include_file = str_replace(array("{{include ", "}}"), "", $value);
			
			$path = APP.DS."View".DS.$include_file.".php";
			ob_start();
			include($path);
			$view = ob_get_contents();
			ob_end_clean();
			$template = str_replace($value, $view, $template);
		}
		if($minify){
			$template = trim($template, ' ');
			$template = preg_replace('/\\t/', '', $template);
			$template = preg_replace('#(\\r\\n|\\r|\\n)#', '', $template);
			$template = preg_replace('~>\s+<~', '><', $template);
		}

        unset($data);
		return $template;
	}

	public function json($data=array()){
		echo json_encode($data);
		return $this;
	}

	public function redirect($path){
	    $url = strtr($_SERVER['SCRIPT_NAME'], array('public/index.php'=>''));
// 		$url = str_replace('public/index.php', '', $_SERVER['SCRIPT_NAME']);
		header('Location: '.$url.$path);
		return $this;
	}

	public function __get($name){
		return $this->obj[$name];
	}
}