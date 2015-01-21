<?php
require 'vendor/autoload.php';

$app = new \Slim\Slim();

$default_services = array(
    'SSH'=>'22',
    'SMTP'=>'25',
    'HTTP'=>'80',
    'MySQL'=>'3306'
);

$app->get( '/api/status/' , 'controller' );

$app->run();

/**
 * 
 * PHP Status Checker with REST API
 * Alex Johnson
 * 
 */

	function controller () {

		global $default_services;

	    $report = server_report($default_services);

	    print_service_status($report);
	}

    function server_report($services) {
        $report = array();
        foreach ($services as $service=>$port) {
            $report[$service.":".$port] = check_port($port);
        }
        return $report;
    }

    function check_port($port) {
        $conn = @fsockopen("127.0.0.1", $port, $errno, $errstr, 2);
        if ($conn) {
            fclose($conn);
            return true;
        } else return false;
    }

    function print_service_status($services) {
		echo json_encode($services);
    }

?>