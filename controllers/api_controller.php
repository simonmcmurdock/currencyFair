<?php 

namespace API;

/**
 * api_controller class, this is an abstract controller which interfaces through the 
 * abstract method process_request
 *
 * I have created this abstract class so that if we wanted to extend my api and add
 * additional endpoints they can be added by simply creating additional classes that 
 * extend this class. I built this as an abstract class rather than an interface as
 * it means that any generic code which would be needed by all controllers that use
 * the api could be added here to prevent duplication.
 * 
 * @author Simon McMurdock
 * @param $model This should be a model which provides database access
 * @param \RateLimiter\rate_limiter_interface_module this is a RateLimiter object which will be used for rate limiting
 * @param \ResponseModule\response_interface_module this is a response object which will be used to send a response
 * @param $data This is used if data needs to be passed in instead of coming from php://input
 * @param $request_method This is used if the request method needs to be passed in instead of coming from $_SERVER['REQUEST_METHOD']
 * 
 */
abstract class api_controller
{

	protected $request_method;
	protected $request_body;
	protected $model;
	protected $rate_limiter;
	protected $response_obj;
	
	
	public function __construct($model, \RateLimiter\rate_limiter_interface_module $rate_limiter, \ResponseModule\response_interface_module $response_obj, $data=false, $request_method=false){
		
		$this->response_obj = $response_obj;
		
		$this->request_method = $request_method ? $request_method : $_SERVER['REQUEST_METHOD'];
				
		$this->request_body = $data ? $data : file_get_contents('php://input');
	
		$this->model = $model;
		
		$this->rate_limiter = $rate_limiter;
				
	}
	
	abstract public function process_request();


}

?>