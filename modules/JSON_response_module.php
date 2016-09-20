<?php

namespace ResponseModuleJSON;

use \ResponseModule\response_interface_module as response_interface_module;

/**
 * JSON_response_module, this is a module for sending JSON responses and is used by the message_api_controlles.
 * It implements the interface response_interface_module
 *
 * @author Simon McMurdock
 * @param send_http_response sets the required http headers and outputs a json array containing a response status
 *
 *
 */
class JSON_response_module implements response_interface_module{
	
	public static function send_http_response($code, $status){
		
		header('Content-Type: application/json');
		http_response_code($code);
		echo(json_encode(array("status"=>$status)));
		exit();
		
		
	}	
	
}