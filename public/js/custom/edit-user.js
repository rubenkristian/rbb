var province_list = [];
var city_list = [];
var bank_list = [];

function getCity(id) {
    $.ajax({
        url: host+"city/get?idprovince="+id,
        method: "GET",
        dataType: "JSON",
        data: {},
        success: function(data){
            var selected_city = $("#city_id").val();
            if(data.status){
                city_list = data.data.cities;
                var arrayOptions = [];
                city_list.forEach(function(value, index) {
                    if(value.id === selected_city) {
                        arrayOptions.push("<option value='"+value.id+"' selected>"+value.name+"</option>");
                    } else {
                        arrayOptions.push("<option value='"+value.id+"'>"+value.name+"</option>");
                    }
                });
                
                $("#city").html(arrayOptions.join());
                $("#city").selectpicker('refresh');
            }else{
                alert(data.msg);
            }
        },
        error: function(data){
            console.log(data);
        }
    });
    return false;
}

function getProvince() {
    $.ajax({
        url: host+"province/get",
        method: "GET",
        dataType: "JSON",
        data: {},
        success: function(data){
            var selected_province = $("#province_id").val();
            if(data.status){
                province_list = data.data.provinces;
                var arrayOptions = [];
                province_list.forEach(function(value, index) {
                    if(value.id === selected_province) {
                        arrayOptions.push("<option value='"+value.id+"' selected>"+value.name+"</option>");
                    } else {
                        arrayOptions.push("<option value='"+value.id+"'>"+value.name+"</option>");
                    }
                });
                
                $("#province").html(arrayOptions.join());
                $("#province").selectpicker('refresh');
            }else{
                alert(data.msg);
            }
        },
        error: function(data){
            console.log(data);
        }
    });
    return false;
}

function getBank() {
    $.ajax({
        url: host+"bank/get",
        method: "GET",
        dataType: "JSON",
        data: {},
        success: function(data){
            var selected_bank = $("#bank_id").val();
            if(data.status){
                bank_list = data.data.bank;
                var arrayOptions = [];
                bank_list.forEach(function(value, index) {
                    if(value.id === selected_bank) {
                        arrayOptions.push("<option value='"+value.id+"' selected>"+value.name+"</option>");
                    } else {
                        arrayOptions.push("<option value='"+value.id+"'>"+value.name+"</option>");
                    }
                });
                
                $("#bank").html(arrayOptions.join());
                $("#bank").selectpicker('refresh');
            }else{
                alert(data.msg);
            }
        },
        error: function(data){
            console.log(data);
        }
    });
    return false;
}

$(document).ready(function() {
    var selected_province = $("#province_id").val();
    getProvince();
    getCity(selected_province);
    getBank();
    
    $("#province").on('change', function() {
        getCity(this.value);
    });
    
	$("#edit_user").submit(function(e){
		e.preventDefault();
        swal({
            title: "Confirm account verified",
            text: "Insert password to verified this account",
            type: "input",
            showCancelButton: true,
            closeOnConfirm: false,
            animation: "slide-from-top",
            inputPlaceholder: "Password"
        }, function (inputValue) {
            if (inputValue === false) return false;
            if (inputValue === "") {
                swal.showInputError("You need to write something!"); 
                return false;
            }
    		var id 		        = $("#userid").val();
    		var email           = $("#email").val();
    		var wa              = $("#wa").val();
    		var name            = $("#name").val();
    		var occupation      = $("#occupation").val();
    		var company         = $("#company").val();
    		var province 	    = $("#province").val();
    		var city     	    = $("#city").val();
    		var bank     	    = $("#bank").val();
    		var accountname	    = $("#bank_name_account").val();
    		var accountnumber   = $("#bank_number_account").val();
    		$.ajax({
    			url: host+"user/submitedit",
    			method: "POST",
    			data: {id: id, wa:wa, name:name, email: email, occupation:occupation, company:company, province:province, city: city, bank: bank, accountname: accountname, accountnumber: accountnumber, username: admin_username, password: inputValue},
    			dataType: "JSON",
    			success: function(data){
    				if(data.status){
    				    alert(data.msg);
    				    location.reload();
    				}else{
    					alert(data.msg);
    				}
    			},
    			error: function(data){
    				alert(data.message);
    			}
    		})
    	});
	});
});