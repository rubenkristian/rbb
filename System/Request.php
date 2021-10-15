<?php

namespace System;
use System\URI;

class Request{

	private $uri;

	private $guestAddr;

	private $guestPort;

	private $keepAlive;

	private $userAgent;

	private $gate;

	private $protocol;

	private $host;

	private $settings;

	public $session;

	public $urlmain = '';

	public function __construct(&$settings){
		$this->settings =& $settings;

		$this->_render_();
	}

	private function _render_(){
		$this->_uri_render();

	}

	private function _uri_render(){
		$this->uri = new URI($this->settings);
	}

	public function getUri(){
		return $this->uri;
	}

	public function getHeader($name){
		$name = 'HTTP_'.strtoupper(preg_replace("/[!@#$%^&*-:;\/\\]]/", '_', $name));
		$header = array_key_exists($name, $_SERVER) ? $_SERVER[$name] : null;
		return $header;
	}

	public function getMethod(){
		if(isset($_SERVER["REQUEST_METHOD"])){
			return $_SERVER["REQUEST_METHOD"];
		}
		return "GET";
	}

	public function getClientIP() {
		$ipaddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_X_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if(isset($_SERVER['REMOTE_ADDR']))
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}

	public function Post($key){
		if(isset($_POST[$key])){
			return $_POST[$key];
		}
		return null;
	}

	public function Get($key){
		if(isset($_GET[$key])){
			return $_GET[$key];
		}
		return null;
	}

	public function setSession(&$session){
		$this->session =& $session;
	}

	public function getmainurl(){
		$url = explode('?', $_SERVER['REQUEST_URI'], 2)[0];
		// echo $url;
		$this->urlmain = str_replace($this->uri->get_str_uri(), '', $url);
	}
}