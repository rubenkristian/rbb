
var request = null;
var pageactive = 0;
function deleteAjax(id){
    $.ajax({
        url: host+"user/delete",
        method: "POST",
        dataType: "JSON",
        data: {id:id},
        success: function(data){
            console.log(data);
            if(data.status == 0){
                location.reload();
            }else{
                
            }
        },
        error: function(data){
            console.log(data);
        }
    });
    return false;
}

$(document).ready(function() {
    $(document).on('click', '.mdl', function (e) {
        var name = $(this).data('name');
        var id = $(this).data('id');
        $("#message").html("Delete "+name+" ?");
        $("#deletebtn").attr('data-iddelete', id);
        $('#smallModal').modal('show');
    });
    $("#list-city").DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": host+"city/list",
            "type": "POST"
        },
        "columns":[
            {"data": "id"},
            {"data": "name"},
            {"data": "province_name"}
        ]
    });
});

$(document).on('click', '#')
$("#deletebtn").click(function(e){
    var id = $(this).data('iddelete');
    console.log(id);
    $('#smallModal').modal('hide');
    e.preventDefault();
    deleteAjax(id);
});