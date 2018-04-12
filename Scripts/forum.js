$(document).ready(function() {
	window.alert("here");
	$.ajax({
		 	type: "GET",
		 	url: "forum.json",
		 	dataType: "json",
		 	success: function(responseData, status){
		  	var output = "<ul>";  
		 	$.each(responseData.items, function(i, item) {
	   		output += '<li class="discussion">' + item.Subject + '</li>';
	    	output += '<li class="internalDisc">' + item.Messege + '</li>';
	    	output += '<li class="author">' "Posted by "+item+" "+item.date+'</li>';
	  	});
	  	output += "</ul>";
	  	$('.discussion').append(output);

		}, error: function(msg) {
	  				// there was a problem
	  	alert("There was a problem: " + msg.status + " " + msg.statusText);
		}
	});
}