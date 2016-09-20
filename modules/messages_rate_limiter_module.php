<?php

namespace MessagesRateLimiter;

use RateLimiter\rate_limiter_interface_module as rate_limiter_interface_module;

use Memcached;

/**
 * messages_rate_limiter_module, this module handles rate limiting and is used by the message_api_controller.
 * It implements the interface rate_limiter_interface_module.
 * 
 * @author Simon McMurdock
 * @param $limit integer This is the number of api calls a user may make during the $limit_seconds period.
 * @param $limit_seconds integer This is the number of seconds that the $limit will be enforced against.
 * @param $rate_limiter_array array This is a multidimensional array which contains a timestamp for each hit grouped by user
 *        The array is stored in Memory Using Memcached. 
 *        I have use Memcached to store the hit count so that we don't have to query the database to see if the user 
 *        has hit their rate limit, getting it from memory is quicker and will allow the api to handle more
 *        simultaneous requests.
 * @param $memcached \Memcached This is the Memcached instance which is set up when a messages_rate_limiter_module object is created.
 * @param is_below_rate_limit This method is used to check that a user has not exceeded their rate limit, it calls the 
 * 		  clear_expired_entries method then gets a count of the user's number of hits, if it's less than $limit
 * 		  it returns true and calls the add_to_limiter_array method otherwise false is returned.
 * @param add_to_limiter_array This method adds a hit for a user to the $add_to_limiter_array and stores the array in Memory
 * @param clear_expired_entries This method clears any hits that are older than the $limit_seconds parameter in the $add_to_limiter_array and stores the array
 * 		  in memory.
 *  
 * 
 */
class messages_rate_limiter_module 
	implements rate_limiter_interface_module
{
	
	private $limit;
	
	private $limit_seconds;
	
	private $rate_limiter_array;
	
	private $memcached;
	
	public function __construct($limit=100, $limit_sconds=60){
		
		$this->limit = $limit;
		$this->limit_seconds = $limit_sconds;

		
		
		$this->memcached = new Memcached();
		$this->memcached->addServer ( '127.0.0.1', '11211' );
		
		$this->rate_limiter_array = $this->memcached->get("messages_rate_limiter_array");
		
		if($this->rate_limiter_array === false){
			
			$rate_limiter_array=array();
			
			$this->memcached->set("messages_rate_limiter_array", $this->rate_limiter_array);
			
		}		
		
		
		
	}
	
	public function is_below_rate_limit($user_indetifier){
		
		/*
		 * Clear any entries that are older than $limit_seconds before counting
		 */		
		$this->clear_expired_entries();
		
		if(count($this->rate_limiter_array[$user_indetifier])<$this->limit){
			
			
			$this->add_to_limiter_array($user_indetifier);
			
						
			return true;
			
		}else{
			
			
			
			return false;
			
		}
		
		
		
		
	}
	
	
	public function add_to_limiter_array($user_indetifier){
		
				
		if($this->rate_limiter_array[$user_indetifier]){
			
			array_push ($this->rate_limiter_array[$user_indetifier], time());
			
		}else{
			
			$this->rate_limiter_array[$user_indetifier] = array(time());
			
			
		}
		
		$this->memcached->set("messages_rate_limiter_array", $this->rate_limiter_array);	
		
	}
	
	
	
	public function clear_expired_entries(){
		
		$expired_time = time() - $this->limit_seconds;
		
		foreach ($this->rate_limiter_array as $key => $userArray){
			
			foreach ($userArray as $timeKey => $timeCode){
				
				
				if($timeCode<$expired_time){
					
					unset($this->rate_limiter_array[$key][$timeKey]);
						
					
				}
				
				
			}
			
			
		}
		
		$this->memcached->set("messages_rate_limiter_array", $this->rate_limiter_array);
		
		
	} 
	
	
	
}