var express = require("express");
var http = require("http");
var mysql = require("mysql");

//Creates the socket.io server and tells it to listen for requests on port 3000
var app = express();
var server = http.createServer(app).listen(3000);
var io = require("socket.io")(server);

//Connects to the database, the database connection is used to load the list of countries coordinates from the
//countries table as they're needed for the heatmap.
var con = mysql.createConnection({
	host: "localhost",
	user: "root",
	password: "",
	database: "currencyFair"
});

con.connect(function(err){
	if(err){
	 console.log('Error connecting to Db\n');
	 return;
	}
	console.log('Db Connection established\n');
});

//Select all the countries and create an array of the required data
var countries = [];
con.query('SELECT * FROM countries',function(err,rows){
	  if(err) throw err;

	  console.log('Countries data received from Db\n');
	 
	 
	  var arrayLength = rows.length;
	  for (var i = 0; i < arrayLength; i++) {
		  countries[rows[i].country] = { lat: rows[i].latitude, long: rows[i].longitude, name: rows[i].name}
	     
	  }
	 
	  
});

//Close the database connection as it's no longer needed
con.end(function(err) {

});

function format_table_row(data){
	
	var countryName = countries[data.originatingCountry].name;
	countryName = countryName.replace(/"/g, "");
	var table_row = `
	<tr>
		<td>${data.currencyFrom}</td>
		<td>${data.currencyTo}</td>
		<td>${data.amountSell}</td>
		<td>${data.amountBuy}</td>
		<td>${data.rate}</td>
		<td>${countryName}</td>
		<td>${data.timePlaced}</td>
	</tr>	
	`;
	
	return table_row;
	
}

//Listen for socket connections
io.on("connection", function(socket){
	
	//Listen for broadcast messages
	socket.on("broadcast", function(message){
		
		//When we receive a broadcast message process the received data and emit the processed data to all socket connections
		var proccessed_tb_row = format_table_row(message);
		
		var proccessed_data = {
				
				table_row: proccessed_tb_row,
				map_cooridinates: {
					lat: countries[message.originatingCountry].lat, 					
					long: countries[message.originatingCountry].long
				}
				
		}				
		
		socket.broadcast.emit("message", proccessed_data);
		
	});
	
	
});

console.log("Starting socket.io app");