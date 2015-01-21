<?php

/**
 * 
 * Alex Johnson
 * 
 */

require 'vendor/autoload.php';

$app = new \Slim\Slim();

$app->get( '/api' , 'controller' );

$app->post( '/tip', 'tip');

$app->run();

	function controller () {
        return "hello world";
	}

    function tip (){
        return 'tipped!';
    }

    function print_data($data) {

		echo json_encode($data);
    }

?>