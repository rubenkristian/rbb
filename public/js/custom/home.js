function getNotification() {
    $.ajax({
        url: host+"admin/notif",
        method: "GET",
        dataType: "JSON",
        data: {},
        success: function(data){
            console.log(data);
            $("#person").html(data.people);
            $("#withdraw").html(data.withdraw);
            $("#expired").html(data.expired);
        },
        error: function({responseJSON}){
            
        }
    });
    return false;
}

$(document).ready(function() {
    getNotification();

    $("#box_person").on("click", function() {
        window.location = host+"user/notverifieds";
    });

    $("#box_withdraw").on("click", function() {
        window.location = host+"withdraw/request";
    });
    
    $("#box_expired").on("click", function() {
       window.location = host+"user/notverifiedexpired";
    });
});