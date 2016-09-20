<?php 

namespace dbModule;

/**
 * db_module, this module creates and closes a connection to the database
 *
 * @author Simon McMurdock
 */
class db_module
{
	
	public static function connect_db(){
		
		$dbc = new \PDO('mysql:host=localhost;dbname=currencyFair', 'root', '');
		
		return $dbc;		
		
	}
	

	public static function close_db(&$db){
	
			
		$db=null;
	
		
	}
	
}

?>