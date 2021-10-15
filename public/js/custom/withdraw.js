var request = null;
var pageactive = 0;
var table = null;

function deleteAjax(id){
    $.ajax({
        url: host+"user/delete",
        method: "POST",
        dataType: "JSON",
        data: {id:id},
        success: function(data){
            console.log(data);
            if(data.status === 0){
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
    
    table = $("#list-withdraw").DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": host+"withdraw/list",
            "type": "POST"
        },
        "columns":[
            {"data": "id"},
            {"data": "name"},
            {"data": "cash"},
            {"data": "date_verified"},
            {"data": "date_created"}
        ],
        dom: "<'row'<'col-sm-3'l><'col-sm-3'f><'col-sm-6'p>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
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