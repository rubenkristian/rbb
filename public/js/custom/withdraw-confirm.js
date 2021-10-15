$(document).ready(function() {
    $("#submit-user-verified").on("click", function() {
        showPromptMessage();
    });
});

function showPromptMessage() {
    swal({
        title: "Confirm withdraw",
        text: "Insert password to confirm this withdraw",
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
        
        $.ajax({
            url: host+"withdraw/confirm",
            method: "POST",
            dataType: "JSON",
            data: {id:id_withdraw, number: wa_number, idmember: id_member, username: admin_username, password: inputValue},
            success: function(data){
                console.log(data);
                if(data.status){
                    var win = window.open(data.link, '_blank');
                    swal({
                        title: "Success",
                        text: "Withdraw confirm",
                        closeOnConfirm: false,
                        type: "info" 
                    }, function(val) {
                        window.history.back();
                    })
                    win.focus();
                }else{
                    swal("Alert", data.msg);
                }
            },
            error: function(data){
                console.log(data);
            }
        });
    });
}