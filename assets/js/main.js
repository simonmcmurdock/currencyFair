//Connect to the node.js socket server
var socket = io("http://localhost:3000");

//This will be triggered when a message is received from the socket server
socket.on("message", function(data){
	
	//If a table_row element is received add it to the top of the table and remove the oldest
	//row from the bottom
	if(data.table_row){
		
		$(data.table_row).insertAfter('#messages_table_header');
		$('#recent_messages tr:last').remove();
		
	}
	
	//If map coordinates are received add them to the heat map
	if(data.map_cooridinates){
		
		window.pointArray.push(new google.maps.LatLng(data.map_cooridinates.lat, data.map_cooridinates.long));
						
	}	
	
});


