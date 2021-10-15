var request = null;
var pageactive = 0;
var table = null;

function deleteAjax(id){
    $.ajax({
        url: host+"user/submitsuspend",
        method: "POST",
        dataType: "JSON",
        data: {id:id},
        success: function(data){
            alert(data.msg);
            table.ajax.reload();
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
    
    table = $("#list-user").DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": host+"user/list",
            "type": "POST"
        },
        "columns":[
            {"data": "id"},
            {"data": "id_created"},
            {"data": "fullname"},
            {"data": "wa"},
            {"data":"tools", "orderable":false}
        ],
        dom: "<'row'<'col-sm-3'l><'col-sm-3'f><'col-sm-6'p>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
    });
    
    $(document).on('click', "#modalshow", function(e) {
        $('#smallModal').modal('show');
        var id = $(this).data('iddelete');
        $("#deletebtn").data("iddelete", id);
    });
    
    $(document).on('click',"#deletebtn", function(e){
        console.log('delete');
        var id = $(this).data('iddelete');
        
        $('#smallModal').modal('hide');
        e.preventDefault();
        deleteAjax(id);
    });
});