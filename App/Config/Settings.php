<?php

$path = array(
	'base' => '', /*url to take*/
	'index_page' => 'index.php', /*first file is execute. if u use mod_rewrite remove make it blank.*/
	'url_suffix' => '',
	'charset' => 'UTF-8',
	'composer_autoload' => FALSE,
	'permitted_uri_chars' => 'a-z 0-9~%.:_\\-'
);


/* optional setting you can change it*/
$database = array(
	'dbdriver'=>'pdo',
	'hostname' => '',
	'username' => '',
	'password' => '',
	'dbname' => '',
	'charset' => 'utf8',
	'collate' => 'utf8_general_ci',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'port'=>3306
);

$route = array(
	'default' => 'Admin',
	'404' => 'Page404',
);

$autoload = array(
	'temp' => 'Template',
	'jwt' => 'Jwt',
	'whatsapp' => 'Whatsapp',
	'email' => 'Email'
);

$session = array(
	'timeout'=>60*60*60
);