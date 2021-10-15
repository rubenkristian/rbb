var process = false;

$(document).ready(function() {
    $("#submit-user-verified").on("click", function() {
        showPromptMessage();
    });
});

function showPromptMessage() {
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
        if(process) return false;
        
        process = true;
        
        $.ajax({
            url: host+"user/verified",
            method: "POST",
            dataType: "JSON",
            data: {id:id_user_not_verified, number: wa_number, username: admin_username, password: inputValue},
            success: function(data){
                if(data.status){
                    var win = window.open(data.link, '_blank');
                    swal({
                        title: "Success",
                        text: "Verified",
                        closeOnConfirm: false,
                        type: "info" 
                    }, function(val) {
                        window.history.back();
                    });
                    
                    win.focus();
                    process = false;
                }else{
                    swal("Alert", data.msg);
                    process = false;
                }
            },
            error: function(data){
                console.log(data);
                process = false;
            }
        });
    });
}