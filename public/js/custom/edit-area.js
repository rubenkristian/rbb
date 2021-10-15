$(document).ready(function(){
    function submitMember(id,location, lat, long, radius){
    	$.ajax({
    		url: host+"area/submitEditArea",
    		method: "POST",
    		dataType: "JSON",
    		data: {id:id, location: location, lat: lat, long: long, radius: radius},
    		success: function(data){
    			if(data.status == 0){
    				location.href = data.url;
    			}
    		},
    		error: function(data){
    
    		}
    	});
    }
    
    $("#area_edit").submit(function(e){
    	e.preventDefault();
    	var id          = $("#hidden_id_location").val();
    	var location    = $("#location").val();
    	var lat         = $("#lat").val();
    	var long        = $("#long").val();
    	var radius      = $("#rad").val();
    	submitMember(id,location, lat, long, radius);
    });
});