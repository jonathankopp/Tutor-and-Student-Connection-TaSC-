$(document).ready(function() {
	$.ajax({
		 	type: "GET",
		 	url: "Resources/connection.js",
		 	dataType: "json",
		 	success: function(responseData, status){
		  	var output = "<ul>";  
		 	$.each(responseData.Students, function(i, item) {
	   		output += '<h3>' + item.name + '<h3>';
	    	output += '<p> Course(s): ' + item.courses + '</p>';
	    	output += ' <p> Year: ' + item.year + '</p>';
	    	output += '<p>' + item.profile + '</p>';
	    	output += '<a href="' + item.addConnection + '"> Connect </a>';
	  	});
	  	output += "</ul>";
	  	$('.person').html(output);
		}, error: function(msg) {
	  				// there was a problem
	  	alert("There was a problem: " + msg.status + " " + msg.statusText);
		}
	});
});