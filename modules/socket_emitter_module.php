<?php

namespace socketEmmiter;

use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version1X;

/**
 * socket_emitter_module, this is used to send messages to our socket server
 *
 * I have use elephant.io to communicate with my socket server
 *
 * @author Simon McMurdock
 */
class socket_emitter_module
{
	
		
	private $socket_server;
	private $client;
	
	public function __construct($socket_server){
		
		$this->socket_server = $socket_server;
		$this->client = new Client(new Version1X($this->socket_server));
		$this->client->initialize();
		
	}
	
	public function emit_message($data){
		
	
		$this->client->emit('broadcast', $data);
		
		
	}
	
	
	public function __destruct(){
	
		$this->client->close();
		
	}	
	
	
	
}