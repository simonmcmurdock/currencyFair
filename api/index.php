<?php 
/**
 * API index
 *
 * This is the view for any api calls, I have built the API using a REST architecture. 
 * The api will return a JSON array containing a status and the http response code will be set
 * to either 200, 404, 400, 429, 403,or 405 depending on what has happened.
 * The .htacces file in this folder rewrites any requests to the api folder that don't 
 * have an extension to index.php?request= where request is set to the what comes after 
 * the api directory so /api/message gets rewritten to /api/index.php?request=message. 
 * I have built it this way so that if we wanted to expand my api and add more end points 
 * we would just need to add another if($request==) condition.
 * The end point that should be hit to process a message is api/message.
 * If the message is valid it will be stored in a mysql database and passed onto a node.js
 * socket server for processing and will be dynamically added to the front end.
 * I have implemented rate limiting by storing the number of hits in a memcached array.
 * I have done it this way to increase performance and reduce the load on the database.
 *
 *
 *
 * @author   Simon McMurdock

 */

require '../vendor/autoload.php';

/**
 * Class autoloader
 *
 * This spl_autoload_register call is used to auto load my classes.
 * Each controller will have _controller at the end of it's name so that the autoloader
 * knows to load it from the controller directory, similarly each model will have _model
 * at the end of it's name and modules will have _module at the end of their name.
 *
 * 
 */
spl_autoload_register(function ($class_name) {

	$class_name_array = explode('\\', $class_name);
	
	$class_name = array_pop($class_name_array);

	if (strpos($class_name, '_controller') !== false) {
		include_once "../controllers/". $class_name . '.php';
	}elseif (strpos($class_name, '_model') !== false) {
		include_once "../models/". $class_name . '.php';
	}elseif (strpos($class_name, '_module') !== false) {		
		include_once "../modules/". $class_name . '.php';		
	}

});


$request = filter_var($_REQUEST['request'], FILTER_SANITIZE_STRING);

if($request=='message'){	
	
	/*
	 * Below I am creating the model, rate limiter and response objects and creating
	 * a new message_api_controller with them. As the model, rate limiter and response classes 
	 * all use interfaces if we wanted to output the data in a different format, store it 
	 * somewhere else or change how the rate limiter works we could simply create new 
	 * classes using the same interfaces and pass them into the controller instead.
	 * 
	 */	
	$model = new messagesModel\messages_model();
	/*
	 * This rate limiter will limit each user to 10 messages per minute. 
	 */
	$rate_limiter = new MessagesRateLimiter\messages_rate_limiter_module(10, 60);
	$response_obj = new ResponseModuleJSON\JSON_response_module();
	
	$api = new messagesAPI\message_api_controller($model, $rate_limiter, $response_obj);
	$api->process_request();
	
	
}
else{
	
	/*
	 * The request is for a non existent end point so a 404 response is returned.
	 */
	ResponseModuleJSON\JSON_response_module::send_http_response(404, "Page not found");
	
}


?>