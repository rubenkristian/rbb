$(document).ready(function(){
    function submitEditAccount(id, id_account, username, password){
        $.ajax({
    		url: host+"member/editAccountSubmit",
    		method: "POST",
    		dataType: "JSON",
    		data: {id: id, id_account: id_account, username: username, password: password},
    		success: function(data){
                console.log(data);
    			if(data.status == 0){
    				window.location = data.url;
    			}else{
                    alert(data.msg);
                }
    		},
    		error: function(data){
    
    		}
    	});
    }
    
    
    $("#account_edit").submit(function(e){
        e.preventDefault();
        var id = $("#member_id_hidden").val();
        var id_account = $("#account_id_hidden").val();
        var username = $("#username").val();
        var password = $("#password").val();
        submitEditAccount(id, id_account, username, password);
    });
});