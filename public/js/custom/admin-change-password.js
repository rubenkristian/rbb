$(function(){
	$("#old_password").val("");
    $("#new_password").val("");
    $("#new_re_password").val("");
	$("#change_password").submit(function(e){
		e.preventDefault();
    	var old_password    = $("#old_password").val();
        var new_password    = $("#new_password").val();
        var new_re_password = $("#new_re_password").val();
		$.ajax({
			url: host+"admin/submitchagepassword",
			method: "POST",
			data: {id: admin_id, oldpassword:old_password, newpassword:new_password, newrepassword:new_re_password},
			dataType: "JSON",
			success: function(data){
			    if(data.status) {
                    location.reload();
			    } else {
			        alert(data.msg);
			    }
			},
			error: function({responseJSON}){
				alert(responseJSON.error.msg);
			}
		})
	});
});