<?php

namespace System;

class Routes{
	static $_route_list = array();

	public function __construct(){

	}

	static function addRoute($path, $callback){
		array_push(self::$_route_list, array('path'=>$path, 'action'=>$callback));
	}

	public function routeResponse($uri_request){
		$path_split = explode('/', $uri_request);
		
	}
}