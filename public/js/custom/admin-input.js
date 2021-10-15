$(function(){
	$("#username").val("");
    $("#password").val("");
    $("#name").val("");
	$("#user_input").submit(function(e){
		var username = $("#username").val();
		var password = $("#password").val();
		var name = $("#name").val();
		e.preventDefault();
		$.ajax({
			url: host+"admin/adminInput",
			method: "POST",
			data: {username:username, password:password, name:name},
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