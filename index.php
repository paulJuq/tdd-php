<?php 

use Twitter\Http\Response; 
use Twitter\Controller\HelloController;


require_once __DIR__ . '/vendor/autoload.php'; 

$controller = new HelloController; 

$response = $controller->hello(); 

$response->send();