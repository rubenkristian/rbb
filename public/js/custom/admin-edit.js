$(function(){
	$("#username").val("");
    $("#name").val("");
    $("#id").val("");
	$("#user_input").submit(function(e){
		var username = $("#username").val();
        var name = $("#name").val();
        var id = $("#id").val();
		e.preventDefault();
		$.ajax({
			url: host+"admin/adminupdate",
			method: "POST",
			data: {id: id, username:username, name:name},
			dataType: "JSON",
			success: function(data){
                location.reload();
			},
			error: function({responseJSON}){
				alert(responseJSON.error.msg);
			}
		})
	});
});