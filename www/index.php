<?php

// nastaveni zakladni include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(dirname(__FILE__) . '/../library'),
    get_include_path(),
)));

// cesta do adresare aplikace
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// nastaveni prostredi aplikace
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

defined('PUBLIC_PATH')
	|| define('PUBLIC_PATH', realpath(dirname(__FILE__)));

/** Zend_Application */
require_once 'Zend/Application.php';  

// tvorba aplikace, uvodni inicializace a spusteni
$application = new Zend_Application(
    APPLICATION_ENV, 
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap();
$application->run();

?>