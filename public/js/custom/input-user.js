$(function(){
	$("#username").val("");
	$("#password").val("");
	$("#user_input").submit(function(e){
		var username = $("#username").val();
		var password = $("#password").val();
		var fullname = $("#fullname").val();
		var phonenum = $("#phonenumber").val();
		var level 	 = $("#level").val();
		e.preventDefault();
		$.ajax({
			url: host+"user/createUserSubmit",
			method: "POST",
			data: {username:username, password:password, fullname:fullname, phonenumber:phonenum, level:level},
			dataType: "JSON",
			success: function(data){
				if(data.status == 0){
					location.reload();
				}else{
					alert(data.msg);
				}
			},
			error: function(data){
				alert(data.message);
			}
		})
	});
});