<?php

namespace messagesModelInterface;

/**
 * messages_model_interface, this is an interface for the messages_model
 *
 * I have created this interface so that if we ever need to change how the data is stored we can simply create 
 * a new model using this interface, as this interface ensures that the model will have everything the controller
 * needs we will not have to make any changes to the rest of the code if we create a new model
 *
 * @author Simon McMurdock
 * @param get_recent_messages This method should return an array of recent messages
 * @param get_map_data This method should return an array of map coordinates
 * @param map_data The method should add the passed in message to the data store
 *
 *
 */
interface messages_model_interface
{
	
	public function get_recent_messages($limit);

	public function get_map_data($limit);
	
	public function add_message($requestData);
	
}