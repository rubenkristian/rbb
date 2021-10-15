function deleteAjax(id){
    $.ajax({
        url: host+"location/delete",
        method: "POST",
        dataType: "JSON",
        data: {id:id},
        success: function(data){
            location.reload();
        },
        error: function({responseJSON}){
            alert(responseJSON.error.msg);
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
    $("#list-location").DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": host+"location/locationlist",
            "type": "POST"
        },
        "columns":[
            {"data": "location_name"},
            {"data":"tools", "orderable":false}
        ]
    });
});
$("#deletebtn").click(function(e){
    var id = $(this).data('iddelete');
    $('#smallModal').modal('hide');
    e.preventDefault();
    deleteAjax(id);
});