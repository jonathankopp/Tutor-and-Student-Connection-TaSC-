$(document).ready(function() {

	$.ajax({
		 	type: "GET",
		 	url: "Resources/forumJS.js",
		 	dataType: "json",
		 	success: function(responseData, status){ 
		 	output="";
		 	$.each(responseData.information, function(i, information) {
		 	output += "<ul>";
	   		output += '<li id="discussion">' + information.Subject + '</li>';
	    	output += '<li class="internalDisc">' + information.Messege + '</li>';
	    	output += '<li class="author">' + "Posted by "+information.Name+" "+information.date+'</li>';
	    	output += "</ul>";
	  	});
	  	$('#discussion').append(output);
		}, error: function(msg) {
	  				// there was a problem
	  	alert("There was a problem: " + msg.status + " " + msg.statusText);
		}
	});
});