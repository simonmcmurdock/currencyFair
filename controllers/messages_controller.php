<?php

namespace messageController;

/**
 * messages_controller, this is the controller which handles the front end view (/index.php)
 *  
 * @author Simon McMurdock
 * @param $model this is the messages model which handles the interaction with the database
 * @param recent_messages Gets an array of the recent messages from the model and returns it to the view.
 * @param map_data Gets an array map coordinates from the model and returns it to the view.
 * 
 *
 */
class messages_controller
{
	
	private $model;
	
	public function __construct($model){
		
		$this->model = $model;
		
	}
	
	public function recent_messages($limit=10){
		
		
		return $this->model->get_recent_messages($limit);
		
		
	}
	
	public function map_data($limit=1000){
		
		return $this->model->get_map_data($limit);
		
	}
	
}