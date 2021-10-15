$(document).ready(function(){
    function submitMember(id,nik, nama, position, address, emergency_call_1, emergency_call_2, relation, ncc, ncc_date, contract_join_1, contract_finish_1, contract_join_2, contract_finish_2, training, terminal, status, reward){
    	$.ajax({
    		url: host+"member/editMemberSubmit",
    		method: "POST",
    		dataType: "JSON",
    		data: {id:id, nik: nik, nama: nama, position: position, address: address, emergency_call_1: emergency_call_1, emergency_call_2: emergency_call_2, relation: relation, ncc: ncc, ncc_date: ncc_date, contract_join_1: contract_join_1, contract_finish_1: contract_finish_1, contract_join_2: contract_join_2, contract_finish_2: contract_finish_2, training: training, terminal: terminal, status: status, reward: reward},
    		success: function(data){
    			if(data.status === 0){
    				location.href = data.url;
    			}
    		},
    		error: function(data){
    
    		}
    	});
    }
    
    $("#member_edit").submit(function(e){
    	e.preventDefault();
    	var id = $("#member_id_hidden").val();
    	var nik = $("#nik").val();
    	var nama = $("#nama").val();
    	var position = $("#position").val();
    	var address = $("#address").val();
    	var emergency_call_1 = $("#emergency_call_1").val();
    	var emergency_call_2 = $("#emergency_call_2").val();
    	var training = $("#training").val();
    	var relation = $("#relation").val();
    	var ncc = $("#ncc").val();
    	var ncc_date = $("#ncc_date").val();
    	var contract_join_1 = $("#contract_join_1").val();
    	var contract_finish_1 = $("#contract_finish_1").val();
    	var contract_join_2 = $("#contract_join_2").val();
    	var contract_finish_2 = $("#contract_finish_2").val();
    	var terminal = $("#loc_val").val();
    	var status = $("#status").val();
    	var reward = $("#reward").val();
    	submitMember(id,nik, nama, position, address, emergency_call_1, emergency_call_2, relation, ncc, ncc_date, contract_join_1, contract_finish_1, contract_join_2, contract_finish_2, training, terminal, status, reward);
    });

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