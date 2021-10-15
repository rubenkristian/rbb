function submitAbsensi(terminal, shift, sick, iz, abs, ini){
    $.ajax({
        url: host+"abs/submitAbsensi",
        method: "POST",
        dataType: "JSON",
        data: {terminal: terminal, shift: shift, sakit: sick, izin: iz, absen: abs, ini: ini},
        success: function(data){
            if(data.status == 0){
                location.reload();
            }
        },
        error: function(data){

        }
    })
}

$("#absen_input").submit(function(e){
    e.preventDefault();
    submitAbsensi(
                    $("#terminal").val(), 
                    $("#shift").val(), 
                    $("#sick").val(), 
                    $("#izin").val(), 
                    $("#abs").val(), 
                    $("#in").val()
                );
    
});

function clear(){
    $("#terminal").val("");
    $("#terminal").selectpicker("render");
    $("#shift").val("");
    $("#shift").selectpicker("render");
    $("#sick").val("");
    $("#izin").val(""); 
    $("#abs").val(""); 
    $("#in").val("");
}