<script>
function trim(str) { return str.replace(/^\s\s*/, '').replace(/\s\s*$/, ''); }
function mobileValidate(y){
	var y = trim(y);
	if(y == ""){ alert("Enter mobile number"); return 0;
	}else if(isNaN(y)||y.indexOf(" ")!= -1){ alert("Mobile number should contain numeric values"); return 0;
	}else if(y.length != 10){ alert("Mobile number should be a 10 digit number"); return 0;
	}
	return 1;
}

function checkLogin(){
	var mobile=trim($('mobileNumber').value);
	var pwd=trim($('pwd').value);
	
	if(mobileValidate(mobile) == '0') return false;
		
	if(pwd==''){
		alert("Enter password");
		return false;
	}	
	
	document.salesman.action="/salesmen/login/";
	document.salesman.submit();
}

</script>

<form name="salesman" method="POST" onSubmit="return checkLogin();">
  <div style="width:100%;text-align:center;background:#ff0000;font-size:1.5em"><strong><span style="color:#ffffff">Pay</span><span style="color:#000000">1</span></strong></div>
  <h2>Welcome Salesman</strong></h2>
  <table border="0" cellspacing='0' cellpadding='2'>
    <?php if($err != ''){ ?>
    <tr>
      <td colspan="2" style="color:#ffffff;" bgcolor="#ff0000"><?php echo $err; ?></td>
    </tr>
    <tr>
      <td colspan="2"></br></td>
    </tr>
    <?php } ?>
    <tr>
      <td>Mobile No.</td>
      <td><input type="number" name="mobileNumber" id="mobileNumber" value=""/></td>
    </tr>
    <tr>
      <td></br>
        Password</td>
      <td></br>
        <input type="password" name="pwd" id="pwd" value=""/></td>
    </tr>
    <tr>
      <td></br>
        <input type="submit" value="Login" /></td>
    </tr>
  </table>
</form>
<script>
$('mobileNumber').focus();	
</script>
