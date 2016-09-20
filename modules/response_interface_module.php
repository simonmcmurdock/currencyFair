<?php

namespace ResponseModule;

/**
 * response_interface_module, this is an interface for response objects which send a response from my api to the client
 *
 * I have created this interface so that if we ever need to change the format of the api response we can simply create
 * a new class using this interface, as this interface ensures that any classes which use our response objects 
 * will have everything they need we will not have to make any changes to the rest of the code if we create a new response object
 *
 * @author Simon McMurdock
 * @param send_http_response This should set the appropriate http headers and output the response body
 *
 *
 */
interface response_interface_module{
	
	public static function send_http_response($code, $output);
	
}