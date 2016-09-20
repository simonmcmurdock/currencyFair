<?php 

namespace messagesModel;

use messagesModelInterface\messages_model_interface as messages_model_interface;

use PDO as PDO;

/**
 * messages_model, this is the message model it implements the interface messages_model_interface and handles all
 * interactions with the database.
 *
 * * @author Simon McMurdock
 * @param $dbc This should be an object of type \dbModule\db_module and should provide a connection to the database,
 * the connection will be opened when the model is created and closed when the model is destroyed
 * @param get_recent_messages($limit) This method will return an array containing the x most recent messages
 * @param get_map_data This method will return an array containing the x most recent map coordinates
 * @param add_message This method sanitises a message and inserts it into messages table
 *
 *
 */
class messages_model 
	implements messages_model_interface
{

	private $dbc;
	
	public function __construct(){
		
		$this->dbc = \dbModule\db_module::connect_db();		
		
		
	}
	
	public function get_recent_messages($limit=10){
		
		$sql= "SELECT messages.*, countries.name FROM messages, countries WHERE messages.originatingCountry = countries.country ORDER BY timePlaced DESC, id DESC LIMIT :limit";
		$stmt = $this->dbc->prepare($sql);
		$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
		$stmt->execute();
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		return 	$results;
		
		
	}
	
	public function get_map_data($limit=1000){
	
		$sql= "SELECT messages.originatingCountry, countries.latitude, countries.longitude FROM messages, countries WHERE messages.originatingCountry = countries.country ORDER BY timePlaced DESC, id DESC LIMIT :limit";
		$stmt = $this->dbc->prepare($sql);
		$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
		$stmt->execute();
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
		return 	$results;
	
	
	}
	
	public function add_message($requestData){
		
		$userId = filter_var($requestData->userId, FILTER_SANITIZE_NUMBER_INT);
		$currencyFrom = filter_var($requestData->currencyFrom, FILTER_SANITIZE_STRING);
		$currencyTo = filter_var($requestData->currencyTo, FILTER_SANITIZE_STRING);
		$amountSell = filter_var($requestData->amountSell, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
		$amountBuy = filter_var($requestData->amountBuy, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
		$rate = filter_var($requestData->rate, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
		$timePlaced = filter_var($requestData->timePlaced, FILTER_SANITIZE_STRING);$timePlaced = date('Y-m-d H:i:s', strtotime(filter_var($requestData->timePlaced, FILTER_SANITIZE_STRING)));
		$originatingCountry = filter_var($requestData->originatingCountry, FILTER_SANITIZE_STRING);
		
		$sql= "INSERT INTO messages (userId, currencyFrom, currencyTo, amountSell, amountBuy, rate, timePlaced, originatingCountry, added) VALUES (:userId, :currencyFrom, :currencyTo, :amountSell, :amountBuy, :rate, :timePlaced, :originatingCountry, NOW())";
		$stmt = $this->dbc->prepare($sql);
		$stmt->execute(array(
				'userId' => $userId,
				'currencyFrom' => $currencyFrom,
				'currencyTo' => $currencyTo,
				'amountSell' => $amountSell,
				'amountBuy' => $amountBuy,
				'rate' => $rate,
				'timePlaced' => $timePlaced,
				'originatingCountry' => $originatingCountry
		));
		
		
	}
	
	public function __destruct(){
		
		\dbModule\db_module::close_db($this->dbc);
		
	}

}

?>