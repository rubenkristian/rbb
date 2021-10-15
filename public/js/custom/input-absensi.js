
$(document).ready(function() {
	// Selector input yang akan menampilkan autocomplete.
	$( "#location" ).autocomplete({
		serviceUrl: host+"api/location_dest",   // Kode php untuk prosesing data.
		dataType: "JSON",           // Tipe data JSON.
		onSelect: function (suggestion) {
			$( "#location" ).val("" + suggestion.location);
			$("#loc_val").val(suggestion.val);
		}
	});
});

$("#input_absensi").submit(function(e){
    e.preventDefault();
    var nik = $("#nik").val();
    var status = $("#stat").val();
    var keterangan = $("#ket").val();
    var lokasi = $("#loc_val").val();
    var shift = $("#shift").val();
    submitAbsensiDays(nik, status, keterangan, lokasi, shift);
});

function submitAbsensiDays(nik, status, keterangan, lokasi, shift){
    $.ajax({
		url: host+"api/absensi_member",
		method: "POST",
		dataType: "JSON",
		data: {nik: nik, ket: keterangan, stat: status, loc: lokasi, shift: shift},
		success: function(data){
			if(data.status == 0){
                alert(data.msg);
				location.reload();
			}else{
                alert(data.msg);
            }
		},
		error: function(data){
            alert(data);
		}
	});
}