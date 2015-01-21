<?php

/**
 * 
 * Alex Johnson
 * 
 */

require 'vendor/autoload.php';

$app = new \Slim\Slim();

$app->get( '/api/' , 'controller' );

$app->run();

	function controller () {

		

	}

    function print_data($data) {

		echo json_encode($data);
    }

?>