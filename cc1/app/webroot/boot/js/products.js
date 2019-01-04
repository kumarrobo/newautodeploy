$(document).ready(function(){ 
    $('#circles_yes').multipleSelect({ 
        selectAll: false, 
        width: 380, 
        multipleWidth: 170, 
        multiple: true, 
        placeholder: "Select circles"
    });
    $('#circles_no').multipleSelect({ 
        selectAll: false, 
        width: 380, 
        multipleWidth: 170, 
        multiple: true, 
        placeholder: "Select circles"
    });

    $('#circles_yes').change(function(){
    	var values = $('#circles_yes').multipleSelect('getSelects');
    	console.log(values);
	    $("#circles_no > option").each(function() {
	        if(values.indexOf(this.value) == -1){
	        	$(this).attr('disabled', false);
	        }    
	        else
	        	$(this).attr('disabled', true);
        	console.log(values.indexOf(this.value));
	    });  
	    $('#circles_no').multipleSelect('refresh');
    });

	$('#circles_no').change(function(){
		var values = $('#circles_no').multipleSelect('getSelects');
	    $("#circles_yes > option").each(function() {
	        if(values.indexOf(this.value) == -1){
				$(this).attr('disabled', false);
	        }    
	        else
	        	$(this).attr('disabled', true);
	    });  
	    $('#circles_yes').multipleSelect('refresh');
	});
});

//to check if entered amount is valid or not
function isValidAmount()
    {//debugger;
        var invalid=$('#invalid').val();
        if(invalid=="")
            {
            return true;
            }
        var regEx=/^[0-9]+(,[0-9]+)*$/;
        var result =regEx.test(invalid);

       console.log(result[1]);
       if(result==false)
            {
            alert("Please enter valid number");
            return false;
            }

       return true;
    }

function save()
    {
        if(!isValidAmount())
        {
           //alert("Invalid Amount");
           return false;
        }
        var pid=$('#id').val();
        var url="/products/editFormEntry/"+pid;
        var circles_yes = $('#circles_yes').multipleSelect("getSelects").join(',');
        var circles_no = $('#circles_no').multipleSelect("getSelects").join(',');
        var data = $('#edit_form').serialize() + "&cy=" + circles_yes + "&cn=" + circles_no; 

       if(circles_yes!="" && circles_no!="")
       {
           alert("Only active circles OR inactive circles can be filled at a time");
           return false;
       }

        $('#submitform').attr('disabled', true);
        $.ajax({ type:"POST",
                 url:url,
                 data:data,
                 dataType:"json",
                 success:function(data)
                 {

                  if(data.status == 'done')
                    {
                        console.log(data);
                        $('#submitform').attr('disabled', false);
                        alert('Data has been saved successfully');
                       // window.location.href="/products/edit?product_id="+pid;
                    } 

                 }
             });
             return false;

    }
    
    function apm_validation() {
        
            var operator    = $('#operator').val();
            var vendor      = $('#vendor').val();
            var min_amount  = ($('#min_amount').val() > 0) ? $('#min_amount').val() : '';
            var max_amount  = ($('#max_amount').val() > 0) ? $('#max_amount').val() : '';
            var list_amount = $('#list_amount').val();
            var activation  = $('#radio .active input').attr('value');

            $('.error-msg').hide();

            var key = 0;
            if(operator == '') {
                $('#operator_err').show();
                key = 1;
            }
            if(vendor == '') {
                $('#vendor_err').show();
                key = 1;
            }
            if(list_amount == '' && (max_amount == '' || min_amount == '')) {
                $('#amount_err').show();
                key = 1;
            }
            if(list_amount != '' && (min_amount != '' || max_amount != '')) {
                $('#both_amount_err').show();
                key = 1;
            }

            if(key == 0){
                $('#activation').val(activation);
                $('#amount_priority_map').submit();
            }
    }
    
    function lvm_validation() {
        
            var vendor      = $('#vendor').val();
            var operator    = $('#operator').val();
            var distributed = $('#distributed').val();
            var activation  = $('#radio .active input').attr('value');

            $('.error-msg').hide();

            var key = 0;
            if(vendor == '') {
                $('#vendor_err').show();
                key = 1;
            }
            if(operator == '') {
                $('#operator_err').show();
                key = 1;
            }
            if(distributed == '') {
                $('#distributed_err').show();
                key = 1;
            }

            if(key == 0){
                $('#activation').val(activation);
                $('#vendor_operator_map').submit();
            }
    }
    
    