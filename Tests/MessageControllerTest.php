<?php


class MessageControllerTest extends \PHPUnit_Framework_TestCase
{
	
	protected function tearDown() {
		\Mockery::close();
	}
	
	
	public function test_recent_messages()
	
	{
		
		/*
		 * Create a mock of the messages_model class so our test doesn't affect the live db
		 */		
		$messages_model_mock = \Mockery::mock('messages_model');
		
		//Test that the get_recent_messages method gets called once
		$messages_model_mock->shouldReceive('get_recent_messages')->once();
		
		$controller = new \messageController\messages_controller($messages_model_mock);
		
		$recent_messages = $controller->recent_messages();
		
			
		
	}
	
	public function test_map_data()
	
	{
	
		/*
		 * Create a mock of the messages_model class so our test doesn't affect the live db
		 */
		$messages_model_mock = \Mockery::mock('messages_model');
	
		//Test that the get_map_data method gets called once
		$messages_model_mock->shouldReceive('get_map_data')->once();
	
		$controller = new \messageController\messages_controller($messages_model_mock);
	
		$map_data = $controller->map_data();
	
			
	
	}
	
	
	
	
    
}




