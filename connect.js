$(document).ready(function() {
	$.ajax({
		 	type: "GET",
		 	url: "connections.json",
		 	dataType: "json",
		 	success: function(responseData, status){
		  	var output = "<ul>";  
		 	$.each(responseData.items, function(i, item) {
	   		output += '<h2>' + item.name + '<h2>';
	    	output += '<p> Course(s): ' + item.courses + '</p>';
	    	output += ' <p> Year: ' + item.year + '</p>';
	    	output += '<p>' item.description + '</p>';
	    	output += '<a href="' + item.link + '"> Connect </a>';
	  	});
	  	output += "</ul>";
	  	$('.person').html(output);
		}, error: function(msg) {
	  				// there was a problem
	  	alert("There was a problem: " + msg.status + " " + msg.statusText);
		}
	});
}