<?php



use messagesAPI\message_api_controller as message_api_controller;
use MessagesRateLimiter\messages_rate_limiter_module as messages_rate_limiter_module;



class MessageApiControllerTest extends \PHPUnit_Framework_TestCase
{
	
	protected function tearDown() {
		\Mockery::close();
	}
	
	
	public function test_valid_message()
	
	{
		
		/*
		 * Create a mock of the messages_model class so our test doesn't affect the live db
		 */		
		$messages_model_mock = \Mockery::mock('messages_model');
		$messages_model_mock->shouldReceive('add_message')->once();
		
		$JSON_response_module_mock = \Mockery::mock('JSON_response_module');
		$JSON_response_module_mock->shouldReceive('send_http_response')->with(200, "Message proccessed");
		
		$data = '{"userId": "134256", "currencyFrom": "EUR", "currencyTo": "GBP", "amountSell": 1000, "amountBuy": 747.10, "rate": 0.7471, "timePlaced" : "24-JAN-15 10:27:44", "originatingCountry" : "FR"}';
		
		$rateLimiter = new messages_rate_limiter_module(10, 60);
		
		$message_api_controller = new message_api_controller($messages_model_mock, $rateLimiter, $JSON_response_module_mock, $data, 'POST');
				
		$message_api_controller->process_request();
		
		
		
		
	}
	
	public function test_invalid_method_type()
	
	{
	
		/*
		 * Create a mock of the messages_model class so our test doesn't affect the live db
		 */	
		$messages_model_mock = \Mockery::mock('messages_model');
		$messages_model_mock->shouldNotReceive('add_message');
	
		$JSON_response_module_mock = \Mockery::mock('JSON_response_module');
		$JSON_response_module_mock->shouldReceive('send_http_response')->with(405, "Method not supported");
	
		$data = '{"userId": "134256", "currencyFrom": "EUR", "currencyTo": "GBP", "amountSell": 1000, "amountBuy": 747.10, "rate": 0.7471, "timePlaced" : "24-JAN-15 10:27:44", "originatingCountry" : "FR"}';
	
		$rateLimiter = new messages_rate_limiter_module(10, 60);
	
		$message_api_controller = new message_api_controller($messages_model_mock, $rateLimiter, $JSON_response_module_mock, $data, 'GET');
	
		$message_api_controller->process_request();
	
	
	
	
	}
	
	public function test_invalid_post_data()
	
	{
		
		/*
		 * Create a mock of the messages_model class so our test doesn't affect the live db
		 */		
		$messages_model_mock = \Mockery::mock('messages_model');
		$messages_model_mock->shouldNotReceive('add_message');
		
		$JSON_response_module_mock = \Mockery::mock('JSON_response_module');
		$JSON_response_module_mock->shouldReceive('send_http_response')->with(400, "Bad request");
		
		$data = '{"currencyFrom": "EUR", "currencyTo": "GBP", "amountSell": 1000, "amountBuy": 747.10, "rate": 0.7471, "timePlaced" : "24-JAN-15 10:27:44", "originatingCountry" : "FR"}';
		
		$rateLimiter = new messages_rate_limiter_module(10, 60);
		
		$message_api_controller = new message_api_controller($messages_model_mock, $rateLimiter, $JSON_response_module_mock, $data, 'POST');
				
		$message_api_controller->process_request();
		
		
		
		
	}
	
	
    
}

class messages_model 
{
	
	public function add_message() {}
	
	public function get_recent_messages() {}
	
	public function get_map_data() {}
	
}

class JSON_response_module implements \ResponseModule\response_interface_module 
{
	
	public static function send_http_response($code, $output){
		
	
	}
	
}

