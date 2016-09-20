<?php
/**
 * index.php
 * 
 * This is the view for the front end, it uses messages_model and messages_controller object to 
 * retrieve an array of recent messages and an array of map coordinates. It uses these arrays to 
 * display a table containing any the 10 most recent messages and to display a heatmap showing
 * where the most recent 1,000 messages have originated from. Both the table and the heatmap 
 * update in real time as they use socket.io to listen for incoming messages from my socket server.
 * The heatmap was created using google maps API.
 * 
 * @author   Simon McMurdock

 */

/**
 * Class autoloader
 *
 * This spl_autoload_register call is used to auto load my classes.
 * Each controller will have _controller at the end of it's name so that the autoloader
 * knows to load it from the controller directory, similarly each model will have _model
 * at the end of it's name and modules will have _module at the end of their name.
 *
 *
 */
spl_autoload_register(function ($class_name) {

	$class_name_array = explode('\\', $class_name);
	
	$class_name = array_pop($class_name_array);
	
	if (strpos($class_name, '_controller') !== false) {
		include_once "controllers/". $class_name . '.php';
	}elseif (strpos($class_name, '_model') !== false) {
		include_once "models/". $class_name . '.php';
	}elseif (strpos($class_name, '_module') !== false) {
		include_once "modules/". $class_name . '.php';		
	}

});

$model = new \messagesModel\messages_model();

$controller = new messageController\messages_controller($model);

$recent_messages = $controller->recent_messages();

$map_data = $controller->map_data();

?>
<!DOCTYPE html>
<html>
  <head>
	<link rel="stylesheet" type="text/css" href="assets/css/styles.css"> 
  </head>

  
	<body>
		<h1>Currency Fair</h1>
		<h3>This page will update automatically through the use of websockets</h3>
		<h2>Recent Transactions</h2>
		<table id="recent_messages">
			<tr id="messages_table_header">
				<th>From Currency</th>
				<th>To Currency</th>				
				<th>Sell Amount</th>
				<th>Buy Amount</th>
				<th>Rate</th>
				<th>Originating Country</th>
				<th>Transaction Date</th>
			</tr>
			<?php 
			if(count($recent_messages)){ 
			
				foreach ($recent_messages as $message){
					
					$c_name = str_replace('"', "", $message['name']);
					
					echo "
						<tr>
							<td>{$message['currencyFrom']}</td>
							<td>{$message['currencyTo']}</td>
							<td>{$message['amountSell']}</td>
							<td>{$message['amountBuy']}</td>
							<td>{$message['rate']}</td>
							<td>{$c_name}</td>
							<td>{$message['timePlaced']}</td>
						</tr>
					";
					
				}
				
				
			}
			?>
		</table>
		<h2>Dynamic heat map</h2>
		<div id="map"></div>
    
		
		
		<script>


      var map, heatmap;

      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          zoom: 1,
          center: {lat: 0, lng: 0},
          mapTypeId: 'satellite'
        });

    
	        mapPoints = [
	          <?php 
	          $mapPoints = count($map_data);
	          foreach ($map_data as $key => $data_point){
	          	
	          	$mapPoint = "new google.maps.LatLng({$data_point['latitude']}, {$data_point['longitude']})";
	          	$curPos = $key + 1;
	          	if($curPos<$mapPoints)
	          		$mapPoint .= ',';
	          	
	          	echo $mapPoint;
	          	
	          }
	          
	          ?>
	         
	        ];
        

        window.pointArray = new google.maps.MVCArray(mapPoints);

        heatmap = new google.maps.visualization.HeatmapLayer({
          data: window.pointArray,
          map: map
        });

                
      }   
      
    </script>
    
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDfnoG-WpEAf6VSrEZlbC9Iozigopm-7Es&libraries=visualization&callback=initMap">
    </script>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="assets/js/socket.io-client.min.js"></script>
		<script src="assets/js/main.js"></script>
		
    
		
	</body>
</html>