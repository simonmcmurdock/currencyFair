<?php

namespace messagesAPI;

use API\api_controller as api_controller;

/**
 * message_api_controller, this is the controller which handles the post request to the /message endpoint. 
 * It extends the api_controller abstract class.
 *
 * @author Simon McMurdock
 * @param $required_post_fields this is an array of all the fields that need to be included in the request body, if one of them 
 * isn't included a 400 bad request response will be returned
 * @param $socket_server This is the address of my socket.io server
 * @param $rate_limiter_active This is used to enable/disable the rate limiter it should be set to either true or false
 * @param process_request This is the method which handles the request it first checks if the http method is POST if not 
 * a 405 response is returned, it then checks that the all the data was included in the body if not a 400 response is returned,
 * it then checks if a user has exceeded the rate limit if so a 429 response is returned, it then inserts the data into the database 
 * , if the database insert fails a 403 response is returned. Once the data is inserted into the database the data is passed onto 
 * the socket server and a 200 response is returned.
 *
 */
class message_api_controller 
	extends api_controller
{
	
	private $required_post_fields = array("userId", "currencyFrom", "currencyTo", "amountSell", "amountBuy", "rate", "timePlaced", "originatingCountry");
	private $socket_server = "http://localhost:3000";
	
	/*
	 * Setting this property to false disables the rate limiter
	 */
	private $rate_limiter_active = true;
		
	public function process_request(){
		
				
		if($this->request_method=="POST"){
			
			$requestData = json_decode($this->request_body);
			
			
			
			foreach ($this->required_post_fields as $field){
			
								
				if(!isset($requestData->$field)){
					
					/*
					 * One of the reuqired fields is missing so issue a 400 Bad Request repsonse.
					 */
					return $this->response_obj->send_http_response(400, "Bad request");
					
							
				}
			
			}
			
			if($this->rate_limiter_active==true){
				
				/*
				 * Check if this user has hit their rate limit
				 */				
				if(!$this->rate_limiter->is_below_rate_limit($requestData->userId)){
					
					/*
					 * The user has hit their rate limit isuse a 429 Rate limit reached! response
					 */					
					return $this->response_obj->send_http_response(429, "Rate limit reached!");
					
					
					
				}
				
				
			}
			
			/*
			* Send the data to the database
			*/			
			try {
				
				
				$this->model->add_message($requestData);
				
				
							
			} catch (Exception $e) {
				
				/*
				* The data could not be added to the database so issue a 403 something went wrong response so the client
				* knows the message was not processed
				*/	
				return $this->response_obj->send_http_response(403, "Somthing went wrong");
				
				
			}
			
			try {
						
				/*
				 * Pass the data to the socket emitter class so it can be sent onto the socket server
				 * */						
				$ws = new \socketEmmiter\socket_emitter_module($this->socket_server);
				$ws->emit_message(get_object_vars($requestData));
				unset($ws);
						
			}catch (Exception $e) {
						
						
						
			}
				
			/*
			 * If we reach this point the message has been processed successfully so issue a 200 response
			 */			
			return $this->response_obj->send_http_response(200, "Message proccessed");
		
			
			
		}else{
			/*
			 * The http method is not supported so issues a 405 Method not supported response 
			 */
			return $this->response_obj->send_http_response(405, "Method not supported");
			
			
		}
		
	}
	
	
	
}