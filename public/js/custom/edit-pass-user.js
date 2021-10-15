$(function(){
	$("#change_password").submit(function(e){
		e.preventDefault();
        swal({
            title: "Confirm account verified",
            text: "Insert password to verified this account",
            type: "input",
            showCancelButton: true,
            closeOnConfirm: false,
            animation: "slide-from-top",
            inputPlaceholder: "Password"
        }, function (inputValue) {
            if (inputValue === false) return false;
            if (inputValue === "") {
                swal.showInputError("You need to write something!"); return false
            }
            var new_password    = $("#new_password").val();
            var new_re_password = $("#new_re_password").val();
            var id_user = $("#id_user").val();
            $("#new_password").val("");
            $("#new_re_password").val("");
    		$.ajax({
    			url: host+"user/submitchagepassword",
    			method: "POST",
    			data: {id: id_user, newpassword:new_password, newrepassword:new_re_password, username: admin_username, password: inputValue},
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
    		});
    	});
    });
});