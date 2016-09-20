<?php 

namespace  RateLimiter;

/**
 * rate_limiter_interface_module, this is an interface for rate limit objects which limit the number of requests
 * users can send to the api
 *
 * I have created this interface so that if we ever need to change how the rate limiter works we can simply create
 * a new class using this interface, as this interface ensures that any classes which use our rate limiter objects
 * will have everything they need we will not have to make any changes to the rest of the code if we create a new
 * rate limiter object
 *
 * @author Simon McMurdock
 * @param is_below_rate_limit This method should check if a user is below their rate limit and true/false
 *        should be returned.
 * @param add_to_limiter_array This method should add an entry to the add_to_limiter_array
 * @param This should clear any entries from the limiter_array which are older than the $limit_seconds parameter
 *
 *
 */
interface rate_limiter_interface_module
{
	
	public function __construct($limit, $limit_sconds);
	
	public function is_below_rate_limit($user_indetifier);
	
	public function add_to_limiter_array($user_indetifier);
	
	public function clear_expired_entries();
	
	
	
}

?>