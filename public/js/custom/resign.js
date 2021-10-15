$(document).ready(function(){
    $("#resign_input").submit(function(e){
        e.preventDefault();
        var nik = $("#nik").val();
        $.ajax({
            url: host+"member/resignMember",
            method: "POST",
            dataType: "JSON",
            data: {nik: nik},
            success: function(data){
                if(data.status){
                    alert("Done");
                    location.reload();
                }else{
                    alert("Failed nik not found");
                }
            },
            error: function(data){
                alert(data)
            }
        })
    });
});