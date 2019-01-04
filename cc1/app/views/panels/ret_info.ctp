<?php if(!isset($pageType) || $pageType != 'csv'){?>
<link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css">
<script>    
function displayTags()
{
if($('tags').style.display == 'none')
	{
		$('tags').show();
	}
	else
	{
		$('tags').hide();
	}

}
function showRequests()
{
        if($('retailerTopUpRequest').style.display == 'none'){
		$('retailerTopUpRequest').show();
	}
	else {
		$('retailerTopUpRequest').hide();
	}

}

function submitManualRequest(retMobile)
{
	var sel='';
	var operator='';
	var subId='';
	var chkSub=$('manualRequestSubId').value;

	var sel1 = document.getElementsByName('manualRequestRadio');
	var str = '';

	if (sel1[0].checked === true)
	{
		sel=$('mobileDDD').value;
	}
	else if (sel1[1].checked === true)
	{
	    sel=$('dthDDD').value;
	    subId=chkSub;
	}else if (sel1[2].checked === true) {
             sel=$('entDDD').value;
	    //subId=chkSub;
        }

        if(sel == 'mobile' || sel == 'DTH' || sel == 'Entertainment')
	{
            alert("Please select an  operator.");
            return;
	}
	//alert(sel);

	/*if($('manualRequestAmount').value== '' && ( sel1[2].checked === true && sel == 35 ))
	{
            alert("Please enter Amount.");
            return;
	}*/

        if($('manualRequestAmount').value== ''){
            if( sel1[2].checked === true ){
                if( sel == "Ditto Tv" ){
                    alert("Please enter Amount.");
                    return;
                }
            }else{
                alert("Please enter Amount.");
                return;
            }
        }




	if($('manualRequestMobNo').value== '')
	{
            alert("Please enter mobile number.");
            return;
	}

	var url='/panels/manualRequest';
	var pars  = "retMobile="+retMobile+"&Amount="+$('manualRequestAmount').value+"&Operator="+sel+"&subId="+subId+"&UserMobile="+$('manualRequestMobNo').value;

	var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
				onSuccess:function(transport)
				{
					var html = transport.responseText;
					//alert(html);
					$('manualRequestAmount').value="";
					$('manualRequestMobNo').value="";
					$('manualRequestSubId').value="";
					$('mobileDDD').value="";
					$('dthDDD').value="";
				}
			});
}


function searchRetTransByDate(mob,flag)
  {
	var from=$('from').value;
	var to=$('to').value;

	var url = '/panels/tranDate/'+mob+'/'+flag;
	var pars   = "retMobile="+mob+"&from="+from+"&to="+to;
	var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
				onSuccess:function(transport)
				{
					var html = transport.responseText;
					 $('reverseTable').innerHTML=html;
				}
			});
  }


function addComment(userId,retId)
{
    var reason=$('commentAreaForRetailer').value;
	//var index=reason.indexOf('#');
	var index = -1;
	var temp = reason.substring(1);
	if(temp=="")
	{
	alert("Tag name cannot be blank.");
	return;
	}


	if(index==0)
		{
			var url1='/panels/tagTransaction/2';
			var pars1="tagName="+encodeURIComponent(reason)+"&tagFor="+userId;

			var myAjax= new Ajax.Request(url1,{method: 'post',parameters:pars1,
			onSuccess:function(transport)
			   {
			   var html=transport.responseText;
		//		$("tags1").innerHTML += temp;
				$('commentAreaForRetailer').value= "";
			   }
			 });
		}

else
{

	var url = '/panels/addComment';
	var pars   = "retId="+retId+"&text="+encodeURIComponent($('commentAreaForRetailer').value);
	var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
				onSuccess:function(transport)
				{
					//var html = transport.responseText;
					//var text1=html.split("==") + "<br/>";
					//Element.insert('asdf',{top:text1});
					$('commentAreaForRetailer').value = "";
					window.location.reload( true );

				}
			});
}
}



function getRetMobNo()
  {
  alert($('retId').value);
  	if($('retId').value != ''){
	var url = '/panels/getRetMobNo';
    var pars   = "retId="+$('retId').value;
	var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
				onSuccess:function(transport)
				{
					$('mobno').value = transport.responseText;
					setAction();
				}
			});
	}else{
		setAction();
	}

  }


  function changeMobNumber(mobileNo)
{

    var url = '/users/sendOtp/';
    var pars = {'mobileNo':mobileNo};
    var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
				onSuccess:function(transport)
				{
                   var res = JSON.parse(transport.responseText);
                   if(res.result=="success"){
                       $("old_mob").value = res.number;
                       $("checkdata1").show();
                       $("checkdata2").show();
                       $("checkdata3").show();
                       $("checkdata4").show();
                   }
				}
			});

}


   function submit(){



       var otp = $('otp').value;
       var newMob = $("new_mob").value;
       var oldMob = $("old_mob").value;
       var url = '/panels/addNewNumber/';
       var pars = {'otp':otp,'newNumber':newMob,'oldNumber':oldMob};
        if(otp == ''){
        alert("Please Enter OTP");
        return false;
        }
        else if(newMob==''){
        alert("Please Enter New Mobile Number");
        return false;
        }
        else if(oldMob==''){
        alert("Please Enter Old Mobile Number");
        return false;
        }
        else if (mobileValidate(newMob)==false)
        {
        alert("Please enter a valid mobile number.");
        return;
        }

        else if(newMob==oldMob){
           alert ("Old number and new number provided are same. Please give a new number.");
	       return false;
        }

        var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
				onSuccess:function(transport)
				{
                    var html = transport.responseText;
						//alert(html);
						var res = transport.responseText;
						var arr = res.split('^^^');

						if(arr[0] == 1){
							//$('test').innerHTML="Your number "+oldNumber+" has been changed to "+newNumber+". "+arr[1];
							alert(arr[1]);
							window.location.href = "/panels/retInfo/"+newMob;
						}else{
							alert(arr[1]+'Try again.');
						window.location.href = "/panels/retInfo/"+oldMob;
						}

				}
			});

  }

  function changeDisplay()
{
				if($('ret').style.display == 'none'){
		$('ret').style.display = 'block';
		$('numberUpdation').style.display = 'none';
	}else{
		$('ret').style.display = 'none';
		$('numberUpdation').style.display = 'block';
	}
}

function display2()
{
if($('ret').style.display == 'none'){
		$('ret').style.display = 'block';
		$('numberUpdation').style.display = 'none';
	}else{
		$('ret').style.display = 'none';
		$('numberUpdation').style.display = 'block';
		$('test').style.display='none';
	}
}


function addNewNumber(oldNumber)
{

	var oldNumber=oldNumber;
	var newNumber=$('change_mobile').value;
	if(newNumber=='')
	{
	alert("New Number field is blank. Please enter a new number");
	return;
	}
	if (mobileValidate(newNumber)==false)
	{
	alert("Please enter a valid mobile number.");
	return;
	}

	if(newNumber==oldNumber)
	{
	alert ("Old number and new number provided are same. Please give a new number.");
	return;
 	}

	var confirmNumber=prompt("Please confirm your new number");

	if(confirmNumber==null)
	{
	$('newNumber').value ='';
	return;
	}
	if(newNumber<confirmNumber||newNumber>confirmNumber)
	{
	alert ('Mismatch between New Number and Confirmed number. Enter again.');
	return;
 	}
 	var url = '/panels/addNewNumber/1';
	var pars   = "oldNumber="+oldNumber+"&newNumber="+newNumber;
	var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
					onSuccess:function(transport)
					{
						var html = transport.responseText;
						//alert(html);
						var res = transport.responseText;
						var arr = res.split('^^^');

						if(arr[0] == 1){
							//$('test').innerHTML="Your number "+oldNumber+" has been changed to "+newNumber+". "+arr[1];
							alert(arr[1]);
							window.location.href = "/panels/retInfo/"+newNumber;
						}else{
							alert(arr[1]+'Try again.');
						window.location.href = "/panels/retInfo/"+oldNumber;
						}


					}
				});
}


function display()
{
if($('ret').style.display == 'none'){
		$('ret').style.display = 'block';
		$('numberUpdation').style.display = 'none';
	}else{
		$('numberUpdation').style.display = 'block';
		$('test').style.display='none';
	}
}

function display1()
{
	var sel = document.getElementsByName('manualRequestRadio');
	var str = '';
	for (var i=0; i<sel.length; i++)
	 {
		if (sel[i].checked == true)
		{
			str = sel[i].value;
		}
	 }

	if(str == 'Mobile')
	{
		$('mobileDD').show();
		$('dthDD').hide();
                $('entDD').hide();
		$('subIdRow').style.display = 'none';
                $('amtRow').style.display = '';
	}
	else if(str == 'DTH')
	{
		$('mobileDD').hide();
		$('dthDD').show();
                $('entDD').hide();
		$('subIdRow').style.display = '';
                $('amtRow').style.display = '';
	}
        else if(str == 'ENT')
	{
		$('mobileDD').hide();
		$('dthDD').hide();
                $('entDD').show();
		$('subIdRow').style.display = 'none';
                if($('entDDD').value != "Ditto Tv"){
                    $('amtRow').style.display = 'none';
                }else{
                    $('amtRow').style.display = '';
                }

	}

}

function setAction()
   {
   	if($('rMobNo').value == '')
   	$('rMobNo').value = 'temp';
   	retId = -1;
	if($('retId'))
		retId = $('retId');

	document.retInfo.action="/panels/retInfo/"+$('rMobNo').value+"/"+retId+"/"+$('from').value+"/"+$('to').value;
	document.retInfo.submit();
   }
   function selectEntertainment(){


        if($('entDDD').value != "Ditto Tv"){
            $('amtRow').style.display = 'none';
        }else{
            $('amtRow').style.display = '';
        }
   }

   function getInformation(){
		var retMobile = $('rMobNo').value.strip();
		var uMobile = $('mobno').value.strip();
		var uSubId = $('subid').value.strip();
		var transactionId = $('pay1Tran').value.strip();
		var pay1TransId = $('vendTran').value.strip();
		var rShop = $('rShop').value.strip();
		var params1= '';
		var params2='';
		var url='';

		if(retMobile != '' || rShop != '')
		{
			if(retMobile != '' && rShop != '')
			{
			alert("Please enter either Retailer Mobile OR Retailer Shop Name. Not both.");
			return;
			}

			if(retMobile != '')
			{
				params1=retMobile;
				url="/panels/retInfo/"+params1+"/"+params2+"/"+$('from').value+"/"+$('to').value;

			}
			else
			{
				//alert(rShop);
				params1=rShop;
				url="/panels/search/"+$('from').value+"/"+$('to').value+"/"+rShop;

			}
		}
	    else
	     if(uMobile != '' || uSubId != '' )
		{
			//alert("In user info");
			if(uMobile != '')
			{

				params1=uMobile;
				url="/panels/userInfo/"+params1;
			}
			else
			{
				params1=uSubId;
				url="/panels/userInfo/"+params1+"/subid";
			}
		}
		else
		 if(transactionId != '' || pay1TransId != '')
		{
			if(transactionId != '' )
			{
			//alert(transactionId);
				params1=transactionId;
				url="/panels/transaction/"+params1;
			}
			else
			{
				params1=pay1TransId;
				url="/panels/transaction/"+params1+"/1";
			}
		}

		document.searchInfo.action=url;
		document.searchInfo.submit();
	}

</script>
<?php

$mapping = array(
			'mobRecharge' =>array(
				'1'=>array(
					'operator'=>'Aircel',
					'opr_code'=>'AC',
					'flexi'=>array('id' => '1','oss'=>'AIC','pp'=>'85','payt'=>'7','cbz'=>'RC','rdu'=>'ARCL','uva'=>'AIRC','anand'=>'aircel','apna'=>'AIR','magic'=>'RC','gem'=>'AS','gitech'=>'HACL'),
					'voucher'=>array('id' => '','oss'=>'AIC','pp'=>'85')
	),
				'2'=>array(
					'operator'=>'Airtel',
					'opr_code'=>'AT',
					'flexi'=>array('id' => '2','oss'=>'AR','pp'=>'51','payt'=>'1302','cbz'=>'RA','rdu'=>'ARTL','uva'=>'AIRT','uni'=>'211','anand'=>'airtel','apna'=>'A','magic'=>'RA','gem'=>'AT','durga'=>'AirTel','gitech'=>'HATL'),
					'voucher'=>array('id' => '','oss'=>'AR','pp'=>'51')
	),
				'3'=>array(
					'operator'=>'BSNL',
					'opr_code'=>'CG',
					'flexi'=>array('id' => '3','oss'=>'BSN','pp'=>'53','payt'=>'1','cbz'=>'BR','rdu'=>'BSNL','uva'=>'BSNL','magic'=>'TB','gem'=>'BS','gitech'=>'HBST'),
					'voucher'=>array('id' => '34','oss'=>'BSN','pp'=>'53','payt'=>'1','cbz'=>'BV','rdu'=>'BR','uva'=>'BSNL','magic'=>'RB','gem'=>'BR','gitech'=>'HBSV')
	),
				'4'=>array(
					'operator'=>'Idea',
					'opr_code'=>'ID',
					'flexi'=>array('id' => '4','oss'=>'IDE','pp'=>'107','payt'=>'5','cbz'=>'RI','rdu'=>'IDEA','uva'=>'IDEA','uni'=>'233','anand'=>'idea','apna'=>'I','magic'=>'RI','gem'=>'ID','durga'=>'Idea','uv'=>5,'gitech'=>'HIDE'),
					'voucher'=>array('id' => '','oss'=>'IDE','pp'=>'107')
	),
				'5'=>array(
					'operator'=>'Loop/BPL',
					'opr_code'=>'LM',
					'flexi'=>array('id' => '5','oss'=>'LOP','pp'=>'1','payt'=>'14','cbz'=>'BP','rdu'=>'LOOP','uva'=>'LOOP','apna'=>'LM','gem'=>'LP'),
					'voucher'=>array('id' => '','oss'=>'LOP','pp'=>'1')
	),
				'6'=>array(
					'operator'=>'MTS',
					'opr_code'=>'MT',
					'flexi'=>array('id' => '6','oss'=>'MTS','pp'=>'133','payt'=>'278','cbz'=>'DM','rdu'=>'MTS','uva'=>'MTS','gem'=>'MT','gitech'=>'HMTS'),
					'voucher'=>array('id' => '','oss'=>'MTS','pp'=>'133')
	),
				'7'=>array(
					'operator'=>'Reliance CDMA',
					'opr_code'=>'RC',
					'flexi'=>array('id' => '7','oss'=>'RC','pp'=>'23','payt'=>'1303','cbz'=>'RR','rdu'=>'RIMC','uva'=>'RELC','uni'=>'244','apna'=>'RC','magic'=>'RL','gem'=>'RC','gitech'=>'HREC'),
					'voucher'=>array('id' => '','oss'=>'RC','pp'=>'23')
	),
				'8'=>array(
					'operator'=>'Reliance GSM',
					'opr_code'=>'RG',
					'flexi'=>array('id' => '8','oss'=>'RC','pp'=>'84','payt'=>'6','cbz'=>'RR','rdu'=>'RIMG','uva'=>'RELG','uni'=>'244','apna'=>'RG','magic'=>'RR','gem'=>'RG','ecom'=>'R','gitech'=>'HREG'),
					'voucher'=>array('id' => '','oss'=>'RC','pp'=>'84')
	),
				'9'=>array(
					'operator'=>'Tata Docomo',
					'opr_code'=>'TD',
					'flexi'=>array('id' => '9','oss'=>'DOC','pp'=>'108','payt'=>'18','cbz'=>'RD','rdu'=>'DOCO','uva'=>'DOCO','apna'=>'D','magic'=>'TD','gem'=>'TD','gitech'=>'HTAD'),
					'voucher'=>array('id' => '27','oss'=>'DOC','pp'=>'108','payt'=>'18','cbz'=>'RDR','rdu'=>'DOCOS','uva'=>'DOCO','apna'=>'DS','magic'=>'RD','gem'=>'TL','gitech'=>'HTDS')
	),
				'10'=>array(
					'operator'=>'Tata Indicom',
					'opr_code'=>'TI',
					'flexi'=>array('id' => '10','oss'=>'TTS','pp'=>'26','payt'=>'3','cbz'=>'DI','rdu'=>'INDI','uva'=>'INDI','apna'=>'T','gem'=>'TA','gitech'=>'HTAI'),
					'voucher'=>array('id' => '27','oss'=>'TTS','pp'=>'26','payt'=>'3','cbz'=>'RDR','rdu'=>'DOCOS','uva'=>'INDI','gitech'=>'HTAI')
	),
				'11'=>array(
					'opr_code'=>'UN',
					'flexi'=>array('id' => '11','oss'=>'UNI','pp'=>'129','payt'=>'790','cbz'=>'UN','rdu'=>'UNR','uva'=>'UNIN','anand'=>'uninor','apna'=>'U','gem'=>'UN','gitech'=>'HUNI'),
					'operator'=>'Uninor',
					'voucher'=>array('id' => '29','oss'=>'UNI','pp'=>'129','payt'=>'790','cbz'=>'UNR','rdu'=>'UNRS','uva'=>'UNIN','anand'=>'uninor','apna'=>'US','gem'=>'US','gitech'=>'HUNS')
	),
				'12'=>array(
					'operator'=>'Videocon',
					'opr_code'=>'DC',
					'flexi'=>array('id' => '12','oss'=>'VID','pp'=>'134','cbz'=>'VR','rdu'=>'VCON','uva'=>'vidgsm','apna'=>'VD','magic'=>'TE','gem'=>'VT','gitech'=>'HVID'),
					'voucher'=>array('id' => '28','oss'=>'VID','pp'=>'134','cbz'=>'VS','rdu'=>'VCONS','uva'=>'vidgsm','apna'=>'VS','magic'=>'RE','gem'=>'VS','gitech'=>'HVIS')
	),
				'13'=>array(
					'operator'=>'Virgin CDMA',
					'opr_code'=>'VC',
					'flexi'=>array('id' => '13','oss'=>'VR','pp'=>'52'),
					'voucher'=>array('id' => '','oss'=>'VR','pp'=>'52')
	),
				'14'=>array(
					'operator'=>'Virgin GSM',
					'opr_code'=>'VG',
					'flexi'=>array('id' => '14','oss'=>'VG','pp'=>'52'),
					'voucher'=>array('id' => '','oss'=>'VG','pp'=>'52')
	),
				'15'=>array(
					'operator'=>'Vodafone',
					'opr_code'=>'VF',
					'flexi'=>array('id' => '15','oss'=>'VF','pp'=>'50','payt'=>'8','cbz'=>'RV','rdu'=>'VODA','uva'=>'VODA','uni'=>'292','anand'=>'vodafone','apna'=>'V','magic'=>'RV','gem'=>'VF','durga'=>'Vodafone','gitech'=>'HVOD'),
					'voucher'=>array('id' => '','oss'=>'VF','pp'=>'50')
	),
				'30'=>array(
					'operator'=>'MTNL',
					'opr_code'=>'MT',
					'flexi'=>array('id' => '30','payt'=>'13','rdu'=>'MTNL','uva'=>'MTNL','apna'=>'MTT','gem'=>'ML','gitech'=>'HMTN'),
					'voucher'=>array('id' => '31','payt'=>'13','rdu'=>'MTNLS','uva'=>'MTNL','apna'=>'MTR','gem'=>'MR')
	)
	),
			'dthRecharge' =>array(
				'1'=>array(
					'operator'=>'Airtel DTH',
					'flexi'=>array('id' => '16','oss'=>'ADT','pp'=>'152','payt'=>'15','cbz'=>'RH','rdu'=>'DA','uva'=>'AIRTELTV','uni'=>'255','magic'=>'DA')
	),
				'2'=>array(
					'operator'=>'Big TV DTH',
					'flexi'=>array('id' => '17','oss'=>'BTV','pp'=>'131','payt'=>'279','cbz'=>'DB','rdu'=>'DB','uva'=>'BIGTV','uni'=>'277','magic'=>'DB','gitech'=>'HBTV')
	),
				'3'=>array(
					'operator'=>'Dish TV DTH',
					'flexi'=>array('id' => '18','oss'=>'DIS','pp'=>'128','payt'=>'12','cbz'=>'DD','rdu'=>'DD','uva'=>'DISHTV','magic'=>'DD','gitech'=>'HDIS')
	),
				'4'=>array(
					'operator'=>'Sun TV DTH',
					'flexi'=>array('id' => '19','oss'=>'SUN','pp'=>'74','payt'=>'11','cbz'=>'DS','rdu'=>'DS','uva'=>'SUNTV','magic'=>'DS','gitech'=>'HSUN')
	),
				'5'=>array(
					'operator'=>'Tata Sky DTH',
					'flexi'=>array('id' => '20','oss'=>'TAS','pp'=>'44','payt'=>'10','cbz'=>'DT','rdu'=>'DT','uva'=>'TATASKY','magic'=>'DT','gitech'=>'HTSY')
	),
				'6'=>array(
					'operator'=>'Videocon DTH',
					'flexi'=>array('id' => '21','oss'=>'D2H','pp'=>'132','payt'=>'20','cbz'=>'VDOC','rdu'=>'DV','uva'=>'videocond2h','magic'=>'DV','gitech'=>'HVIH')
	)
	),
            'busBooking' =>array(
				'1'=>array(
					'operator'=>'Red Bus',
					'flexi'=>array('id' => '42')
	)
	),

            'billPayment' =>array(
                                            '1'=>array(
                                                    'operator'=>'Docomo Postpaid',
                                                    'flexi'=>array('id' => '36','apna'=>'PD')
	),
                                            '2'=>array(
                                                    'operator'=>'Loop Mobile PostPaid',
                                                    'flexi'=>array('id' => '37','apna'=>'PLM')
	),
                                            '3'=>array(
                                                    'operator'=>'Cellone PostPaid',
                                                    'flexi'=>array('id' => '38','apna'=>'PCL')
	),
                                            '4'=>array(
                                                    'operator'=>'IDEA Postpaid',
                                                    'flexi'=>array('id' => '39','apna'=>'IP')
	),
                                            '5'=>array(
                                                    'operator'=>'Tata TeleServices PostPaid',
                                                    'flexi'=>array('id' => '40','apna'=>'PTT')
	),
                                            '6'=>array(
                                                    'operator'=>'Vodafone Postpaid',
                                                    'flexi'=>array('id' => '41','uni'=>'266','apna'=>'VP')
	),
											'7'=>array(
                                                    'operator'=>'Airtel Postpaid',
                                                    'flexi'=>array('id' => '42','apna'=>'AP')
	),
											'8'=>array(
                                                    'operator'=>'Reliance GSM Postpaid',
                                                    'flexi'=>array('id' => '43','apna'=>'RGP')
	)
	),
	'utilityBillPayment' =>array(
                                            '1'=>array(
                                                    'operator'=>'Reliance Energy (Mumbai)',
                                                    'flexi'=>array('id' => '45')
	),
                                            '2'=>array(
                                                    'operator'=>'BSES Rajdhani',
                                                    'flexi'=>array('id' => '46')
	),
                                            '3'=>array(
                                                    'operator'=>'BSES Yamuna',
                                                    'flexi'=>array('id' => '47')
	),
                                            '4'=>array(
                                                    'operator'=>'North Delhi Power Limited',
                                                    'flexi'=>array('id' => '48')
	),
                                            '5'=>array(
                                                    'operator'=>'Airtel Landline',
                                                    'flexi'=>array('id' => '49')
	),
                                            '6'=>array(
                                                    'operator'=>'MTNL Delhi Landline',
                                                    'flexi'=>array('id' => '50')
	),
											'7'=>array(
                                                    'operator'=>'Mahanagar Gas Limited',
                                                    'flexi'=>array('id' => '51')
	)
	)
	);

?>
<style>
    .modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content/Box */
.modal-content {
    background-color: #fefefe;
    margin: 15% auto; /* 15% from the top and centered */
    padding: 20px;
    border: 1px solid #888;
    width: 80%; /* Could be more or less, depending on screen size */
}

/* The Close Button */
.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

ul.ws_drop_down {
	display: block;
	float: left;
	background-repeat: repeat;
	background-position: top;
}

ul.ws_drop_down li img {
	border: 0px;
	vertical-align: middle;
	padding: 1px
}

ul.ws_drop_down li {
	display: block;
	margin: 0px 0px 0px 0px;
	float: left;
}

ul.ws_drop_down a:hover ul, ul.ws_drop_down a:hover a:hover ul, ul.ws_drop_down a:hover a:hover a:hover ul
	{
	display: block;
}

ul.ws_drop_down li a {
	display: block;
	vertical-align: middle;
	text-decoration: none;
	text-align: left;
	font-size: 14px;
	line-height: 20px;
	padding: 2px 0px 2px 10px;
	margin: 0px;
	color: #666666;
	background-repeat: no-repeat;
	background-position: 75px center;
	width: 120px;
	outline: none;
}

ul.ws_drop_downm li a:hover, ul.ws_drop_downm li a {
	color: #000;
}

ul.ws_drop_down ul {
	position: absolute;
	left: -1px;
	top: 98%;
	background-color: #fff;
	margin: -2px 0px 0px 0px;
	border-bottom: 1px solid #7e9dba;
	border-left: 1px solid #7e9dba;
}

ul.ws_drop_down, ul.ws_drop_down ul {
	margin: 0px;
	list-style: none;
	padding: 0px;
}

ul.ws_drop_down a:active, ul.ws_drop_down a:focus {
	outline-style: none;
}

ul.ws_drop_down ul li {
	float: left;
	margin: 0px 0px 0px -1px;
}

ul.ws_drop_down ul a {
	white-space: nowrap;
	text-align: left; /*border-right: 1px solid #CCCCCC;*/
	border-left: 1px solid #7e9dba;
	border-top: 0px solid #7e9dba;
	width: 100px;
	background-image: none;
	padding: 3px 0px 3px 10px;
}

ul.ws_drop_down li:hover {
	position: relative;
}

ul.ws_drop_down li:hover>a {
	background-color: #fff;
	color: #a14209;
	text-decoration: none;
}

ul.ws_drop_down li a:hover {
	position: relative;
	background-color: #fff;
	color: #a14209;
	text-decoration: none;
}

ul.ws_drop_downm li a:hover {
	background-color: #f5db89;
}

ul.ws_drop_down ul, ul.ws_drop_down a:hover ul ul {
	display: none;
	z-index: 99999;
}

ul.ws_drop_down li:hover>ul {
	display: block
}
/* CSS for TABLE Tags for IE 6 and Lower START */
ul.ws_drop_down li a table, ul.ws_drop_down li a:hover table {
	border-collapse: collapse;
	margin: 0px 0px 0px 0px;
	border: 0px;
	padding: 0px;
}

ul.ws_drop_down li a table tr td, ul.ws_drop_down li a:hover table tr td
	{
	padding: 0px;
	border: 0px;
}

ul.ws_drop_down li a table ul, ul.ws_drop_down li a:hover table ul {
	border-collapse: collapse;
	padding: 0px;
	margin: 0px 0px 0px -1px;
}

ul.ws_drop_down table ul {
	left: 0px;
}
span.response_success:before {
	content: url('/img/green_circle_check14x14.png');
}

span.response_success {
	color: green;
	font-size: 12px;
	font-family: "Lucida Console", Monaco, monospace;
}

span.response_failure {
	color: #987107;
	font-size: 12px;
	font-family: "Lucida Console", Monaco, monospace;
}
#userTrans tr:nth-child(even), #retailers tr:nth-child(even), #user tr:nth-child(even) {
    background-color: #eee;
}
#userTrans tr:nth-child(odd), #retailers tr:nth-child(odd), #user tr:nth-child(odd) {
   background-color:#fff;
}
#user td, #retailers td {
	border: 1px solid white;
}
#all_comments td {
	background-color: #eee;
	margin: 2px;
}
#customers {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

#customers td, #customers th {
    border: 1px solid #ddd;
    padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #4CAF50;
    color: white;
}
</style>
<script>

function expand_collapse_table(id){
	e = $(id);
	if(e.visible()){
		e.hide();
		$(id + "_expand").update('+');
	}
	else{
		e.show();
		$(id + "_expand").update('-');
	}
}

function selectElement(element, name, id){
	$(element + '_selected').update(name);
	$(element).value = id;
	if(element == 'call_type')
		dropdownToggleNone();
}

function dropdownToggleNone(){
	if($('call_type').value != 'none'){
		$('default_tag').hide();
		$('other_tags').show();
	}
	else{
		$('other_tags').hide();
		$('default_tag').show();
	}
}

function takeComment(userId, retId){
	var comment = $('commentAreaForRetailer').value;
	var call_type = $('call_type').value;
	var tag = $('tag').value;
	if(comment == ''){
		alert('Give a valid comment');
		return false;
	}
	if(call_type != 'none' && tag == 'none'){
		alert('Tag this call');
		return false;
	}
	$('submit_comment').hide();
	$('ajax_loader').show();
	var url = '/panels/addComment';
	var pars   = "retId="+retId+"&text="+encodeURIComponent(comment)+"&callTypeId="+call_type+"&tagId="+tag;
	var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
		onSuccess:function(transport)
		{
			//var html = transport.responseText;
			//var text1=html.split("==") + "<br/>";
			//Element.insert('asdf',{top:text1});
			$('commentAreaForRetailer').value = "";
			$('ajax_loader').hide();
			$('submit_comment').show();
			window.location.reload( true );

		}
	});
}
</script>
<style>
#ret, #ret th, #ret td {
   border: 1px solid gray;
}

#service, #service th, #service td {
   border: 1px solid gray;
}
.new-retailer{
	background-color: rgb(199,243,255);
}


</style>
<?php echo $this->element('cc_search');

if(in_array($retId,$retailerData)){

	$classname = 'new-retailer';
} else {
	$classname = '';
}

?>
<div class="<?php echo $classname;?>">
<table>
	<tr><td>&nbsp;</td></tr>
</table>
<?php if(isset($mob)){ ?>
<form name="retInfo" method="POST" onSubmit="setAction()">
<!--
Retailer Mobile No :<input type="text" name="mobno" id="mobno" onfocus="$('retId').value='';" value="<?php if(isset($mob))echo $mob;?>" /> <b>OR</b>
Retailer Id  : <input type="text" name="retId" id="retId" value="<?php if(isset($info[0]['retailers']['id'])) echo $info[0]['retailers']['id']; ?>" onfocus="$('mobno').value='';"/>
 -->
From Date  : <input type="text" name="from" id="from"  onmouseover="fnInitCalendar(this, 'from','close=true')" value="<?php if(!empty($from))echo $from;?>" />
To Date:   <input type="text" name="to"   id="to"    onmouseover="fnInitCalendar(this, 'to','close=true')"   value="<?php if(!empty($to))echo $to;?>" />
<input type="submit" value="Submit" onclick="setAction()">
</form>
<a href="?res_type=csv" ><img id="export_csv" type="button" alt="xp" class="export_csv" src="/img/csv1.jpg" style="height:25px" /></a>




<table width="100%;"  style="" border='0' cellspacing="5">
<tr><!-- 1st row start -->
<td valign="top" width="800px"> <!-- 1st td -->
    <?php if(isset($info) && count($info)>0) { ?>

    <!-- <h4>Retailer Information (<a href="/shops/graphRetailer/?type=r&id=<?php echo $info[0]['retailers']['id'];?>">Analyze Performance</a>)</h4> -->
    <h4> Retailer Information <button type="button" class="retailBut enabledBut" id="txnhistory">Balance History</button><br>
    <a href="/panels/editRetailer/<?php echo $info[0]['retailers']['mobile']; ?>">Edit Info</a> (<a href="/shops/graphRetailer/?type=r&id=<?php echo $info[0]['retailers']['id'];?>">Analyze Performance</a>) &nbsp;Wallet Balance : <?php echo $info[0]['users']['balance']; ?></h4>
    <!--<table id="ret" cellpadding="4" cellspacing="0" width="100%" align="left" style="float: left">-->
    <table id="ret" cellpadding="4" cellspacing="0"  style="display: inline-block;float: left;">
        <tr><td colspan="9" align="center" class="appTitle">Profile Details</td></tr>
            <tbody>
                    <!--<tr> <b></b></tr>-->
                    <tr>
                        <th>Name</th>
                        <td><?php echo $info[0]['retailers']['name']; ?></td>
                    </tr>
                    <tr>
                        <th>User Id</th>
                        <td><?php echo $info[0]['retailers']['user_id']; ?></td>
                    </tr>
                    <tr>
                        <th>Mobile</th>
                        <td>
                        <?php echo $info[0]['retailers']['mobile']; ?>
                        <input type="button" value="Change" onClick="changeMobNumber('<?php echo $info[0]['retailers']['mobile']; ?>');">
                        </td>
                    </tr>
                    <tr align="left" id="checkdata1" style="display: none;">
                    <th>OTP</th>
                    <td><input  type="password" id="otp" name="otp" placeholder="Enter otp" autocomplete="off"/></td>
                </tr>
                <tr align="left" id="checkdata2" style="display: none;">
                    <th>New Mobile</th>
                    <td><input  type="new_mob" id="new_mob" name="newNumber" placeholder="Enter New Mobile No"  /></td>
                </tr>
                <tr align="left" id="checkdata3" style="display: none;">
                    <th>Old Mobile</th>
                    <td><input type="text"  value="" disabled="disabled"  id="old_mob"  name="oldNumber"/></td>
                </tr>
                <tr id="checkdata4"  style="display: none;">
                    <td>&nbsp;</td>
                    <td> <input type="button" class="retailBut enabledBut" value="submit" onclick="submit();"></td>
                </tr>
                    <tr>
                        <th>Alternate Mob No</th>
                        <td><?php echo $info[0]['retailers']['alternate_number']; ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?php echo $info[0]['retailers']['email']; ?></td>
                    </tr>
                    <tr>
                        <th>Shopname</th>
                        <td><?php echo $info[0]['retailers']['shopname']; ?></td>
                    </tr>
                    <tr>
                        <th>Shop Type</th>
                        <td><?php if(!empty($info[0]['retailers']['shop_type'])) echo $objShop->business_natureTypes($info[0]['retailers']['shop_type']); ?></td>
                    </tr>

                     <tr>
                        <th>Annual Turnover</th>
                        <td><?php if(!empty($info[0]['retailers']['annual_turnover'])) echo $objShop->annual_turnoverTypes($info[0]['retailers']['annual_turnover']); ?></td>
                    </tr>

                     <tr>
                        <th>Shop Area Type</th>
                        <td><?php if(!empty($info[0]['retailers']['shop_area_type'])) echo $objShop->shop_area_typeTypes($info[0]['retailers']['shop_area_type']); ?></td>
                    </tr>

                      <tr>
                        <th>Shop Ownership</th>
                        <td><?php if(!empty($info[0]['retailers']['shop_ownership'])) echo $objShop->shop_ownershipTypes($info[0]['retailers']['shop_ownership']); ?></td>
                    </tr>
                    <tr>
                        <th>Location</th>
                        <td><?php if(!empty($info[0]['retailers']['location_type'])) echo $objShop->location_typeTypes($info[0]['retailers']['location_type']); ?></td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td><?php echo $info[0]['retailers']['address'] . " - " . $info[0]['retailers']['pin']; ?></td>
                    </tr>
                    <tr>
                        <th>City</th>
                        <td><?php echo $info[0]['retailers']['area']; ?></td>
                    </tr>
                    <tr>
                        <th>Latitude / Longitude</th>
                        <td><?php if(isset($user_profile) && !empty($user_profile)){ echo $user_profile['latitude'] . "/" . $user_profile['longitude']; } ?></td>
                    </tr>
                    <tr>
                        <th>Logs</th>
                        <td><?php echo $info[0]['retailers']['id']; ?>&nbsp; <a href="http://www.smstadka.com/promotions/userDelivery/<?php echo $mob; ?> " target="_blank">SMS </a> / <a href="/panels/ussdLogs/<?php echo $mob; ?>  " target="_blank">USSD </a><br/> /<a href="/panels/appNotificationLogs/<?php echo $mob; ?>  " target="_blank">App Notification </a> log  <a href="/panels/ussdLogs/<?php echo $info[0]['retailers']['mobile']; ?>" target="_blank">USSD log</a></td>
                    </tr>
            </tbody>
        </table>
				<!--</tr>-->
        <table id="ret" cellpadding="4" cellspacing="0"  style="display: inline-block;float: left;">
            <tr><td colspan="9" align="center" class="appTitle">Other Info</td></tr>
            <tbody>
            <tr>
                <th>Distributor</th>
                <td><?php echo $info[0]['distributors']['company']; ?></td>
            </tr>
            <tr>
            	<?php $count= count($salesmenResult)+1;?>
                <th rowspan="<?php echo $count;?>">Related Salesmen</th>
                <?php
                    if(empty($salesmenResult))
                    {
                            echo '<td>No salesmen</td>' ;
                    }
                    else
                            {
                                    foreach($salesmenResult as $s)
                                    {?>
                                        <tr><td><?php echo $s["sm"]["name"]."-".$s["sm"]["mobile"]?></td></tr>
                                   <?php
                                   }
                            }
                    ?>
            </tr>
            <tr>
                    <th>Device Info</th>
                    <td><?php if(isset($user_profile) && !empty($user_profile)){ echo $user_profile['device_type'] . "-" . $user_profile['manufacturer'] . "-" . $user_profile['version']; } ?></td>
            </tr>
            <tr>
                    <th>Status</th>
                    <td>
                                <?php
                                if($info[0]['retailers']['block_flag'] == 0 )
                                    echo "Active";
                                else if($info[0]['retailers']['block_flag'] == 1)
                                    echo "Partially Blocked";
                                else if($info[0]['retailers']['block_flag'] == 2)
                                    echo "Fully Blocked";
                                ?>
                    </td>
            </tr>
            <tr>
                    <th>Created On</th>
                    <td><?php if(isset($info[0]['retailers']['created']))echo date('j M, y',strtotime($info[0]['retailers']['created'])); ?></td>
            </tr>
            </tbody>
        </table>

<!--        <table id="ret" cellpadding="4" cellspacing="0"  style="display: inline-block;">
            <caption><b>SmartPay Info</b></caption>
            <tr><td colspan="9" align="center" class="appTitle">SmartPay Info</td></tr>
            <tbody>
            <tr>
                <th>Bank Name</th>
                <td><?php echo $settings['bank_name'];?></td>
            </tr>
            <tr>
                <th>Account number </th>
                <td><?php echo $settings['acc_no'];?></td>
            </tr>
            <tr>
                <th>IFSC Code</th>
                <td><?php echo $settings['ifsc_code'];?></td>
            </tr>
            <tr>
                <th>AEPS</th>
                <td>
                    <?php echo $userServices['aeps'];?>
                </td>
                        </tr>
                        <tr>
                            <th>M-POS</th>
                            <td>
                                <?php // echo $userServices; ?>
                                <?php echo $mpos_service . '' . $mposdeviceId; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Activation Date</th>
                            <td><?php echo $mpos_created_at; ?> </td>
                        </tr>
                        <tr>
                            <th>M-POS Created by</th>
                            <td><?php echo $mpos_username[0]['users']['name']; ?> </td>
                        </tr>
                        if rental is not there for particular retailer it will not be shown
                        <?php if (!empty($mposrental_data)) { ?>
                            <tr>
                                <th>M-POS Rental</th>
                                <td> <?php echo $mposrental_data[0]['shop_transactions']['date'] . ' / ' . $mposrental_data[0]['shop_transactions']['amount']; ?> </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>

                                <table id="ret" cellpadding="4" cellspacing="0"  style="display: inline-block;">
                                            <caption><b>SmartPay Info</b></caption>
                                                <tr><td colspan="2" align="center" class="appTitle">DMT Info</td></tr>
                                                <tbody>
                                                    <tr>
                                                        <th>Activation Date</th>
                                                        <td><?php echo $dmt_data[0]['users_services']['created_on']; ?> </td>
                                                    </tr>
                                                    <tr>
                                                        <th>CSP ID</th>
                                                        <td><?php echo $dmtdeviceId; ?> </td>
                                                    </tr>
                                                <th>Created by</th>
                                                <td><?php echo $dmt_username[0]['users']['name']; ?> </td>
            </tr>
            </tbody>
            </table>

				</tr>
				<tr>

                    <th>Shop Structure</th>
					<td><?php if(!empty($info[0]['retailers']['shop_structure'])) echo $objShop->structureTypes($info[0]['retailers']['shop_structure']); ?></td>
				</tr>


				<tr>

					<th>Type</th>
					<td>
                        <?php
//                           if($info[0]['retailers']['rental_flag'] == 0 )
//                               echo "KIT";
//                           else
//                               echo "RENTAL"
                         ?>
                    </td>
				</tr>
				<tr>


				</tr>
				<tr>


				</tr>
				<tr>

					<th>Name</th>
					<th>Mobile</th>
				</tr>

			<tr align="left">
					<td>User Id </td>
					<td><?php echo $info[0]['retailers']['user_id']; ?>&nbsp;<a href="/panels/userInfo/<?php echo $info[0]['retailers']['mobile']; ?>">Search</a> </td>
				</tr>





				<tr align="left">
					<td>Maint Salesman</td>
					<td><?php echo $info[0]['salesmen']['name']; ?></td>
				</tr>

				<tr align="left">
					<td> Alternate Number </td>
					<td><?php echo $info[0]['retailers']['alternate_number']; ?></td>
				</tr>
				<tr align="left">
					<td colspan="2"><a href="/panels/editRetailer/<?php echo $info[0]['retailers']['mobile']; ?>">Edit Info</a></td>
				</tr>
				-->
			<!--</table>-->
			<?php } ?>

<!--			<div id="numberUpdation" style='display:none'>
					<table border="0" cellpadding="0" cellspacing="0" width="100%" align="left">
						<tr> sdfs
							<td>Old Number</td>
							<td><?php echo  $info[0]['retailers']['mobile'];  ?></td>
						</tr>
						<tr> sdfs
							<td>New Number</td>
						   <td><input type="text" id="newNumber" ></td>

						</tr>
						<tr>
							<td>Pin</td>
							<td><span id="pin"></span></td>
						</tr>
						<tr>
							<td><input type="button" value="CHANGE"
							 onclick="addNewNumber('<?php echo  $info[0]['retailers']['mobile'];  ?>',$('newNumber').value)">&nbsp;&nbsp;<input type=button id="change" value="Back"  onclick="display2();"></td>
							<td>&nbsp;
							<tr>
							</td>
						</tr>
					</table>
			</div>	-->
<!--
<div id="tags1" style= 'color:#98FB98'><a href="javascript:void(0);" onclick="displayTags();">View Tags</a></div>-->
<div id="tags" style="color:#00FF00; display:none;max-width:250px;overflow: auto;">
<table>
<tr>
<?php
$count=0;
foreach($tags as $t)
{

	if(!empty($t['tu']['transaction_id']))
		echo "<td bgcolor='#A4D3EE'><a href='/panels/tags/".$t['t']['name']."'>".$t['t']['name']." -> ".$t['tu']['transaction_id']."</a></td>";
	else
    	echo "<td bgcolor='#0099CC'><a href='/panels/tags/".$t['t']['name']."'>".$t['t']['name']."</a></td>";

	echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

	$count++;

	if($count%3==0)
	{
		echo "</tr><tr>";
	}
}
	//echo "Count is ".$count;
?>

</tr>
</table></div>


<td valign="top">
 <div><a href="javascript:void(0);" onclick="showRequests();">View Top Up Request By Retailer</a></div>


	<div id= "retailerTopUpRequest" style="display:none; text-align:left; padding:5px; width: 180px; height: 200px; border-color: #111111; border-width: 1px; border-style: solid; color: #000000; font-size: 10px; font-family: Arial; overflow: auto;">

		<table >
		<?php
			$payMode='';
			if(empty($topUpResult))
				echo 'No top up requested by retailer';
			else
			{
				foreach($topUpResult as $d)
				{
				if($d['tr']['type']==1)
					$payMode='CASH';
				else if($d['tr']['type']==2)
					$payMode='CHEQUE';
				else if($d['tr']['type']==3)
					$payMode='NEFT';
				else if($d['tr']['type']==4)
					$payMode='DD';
				echo "<tr><td>";
				echo"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$d['tr']['created'];echo "</br>";
				echo "Rs. ".$d['tr']['amount']; echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" ; echo $payMode;
				echo "</td></tr>";
				}
			}
		?>
		</table>
	</div>

	</br>


	<div id="ManualRequest" style="display:block; text-align:left; padding:3px; border-color: #111111; border-width: 1px; border-style: solid; color: #000000;">
	<h4>Take Manual Request</h4>
		<table id="manualReqTab" style="display:block;" border = '0'>
			<tr>
				<td width="80px"><input type="radio" name="manualRequestRadio" id="manualRequestRadio" checked value="Mobile" onchange="display1();"/> Mobile </td>
				<td><input type="radio" name="manualRequestRadio" id="manualRequestRadio" value="DTH" onchange="display1();"/> DTH</td>
                                <td><input type="radio" name="manualRequestRadio" id="manualRequestRadio" value="ENT" onchange="display1();"/> Entertainment</td>
			</tr>
			<tr>
				<td colspan="3">
                                        <span id="mobileDD" style="display:block">
                                                <label >Operator:</label>
                                                        <select id="mobileDDD" >
                                                        <option> mobile</option>
                                                        <option value='Reliance CDMA'>Reliance CDMA</option>
                                                        <option value='Reliance GSM'>Reliance GSM</option>
                                                                <?php
                                                                        /*foreach($operators as $oDD)
                                                                        {
                                                                        if($oDD['products']['service_id'] == 1)echo "<option value='".$oDD['products']['name']."'>".$oDD['products']['name']."</option>";
                                                                        }*/
                                                                ?>
                                                        </select>
                                        </span>
					<!--<span id="dthDD" style="display:none">
                                        <label >Operator:</label>
                                            <select id="dthDDD">
					<option> DTH</option>
						<?php
							foreach($operators as $oDD)
							{
							if($oDD['products']['service_id'] == 2)echo "<option value='".$oDD['products']['name']."'>".$oDD['products']['name']."</option>";
							}
						?>
					</select>
					</span>
                                        <span id="entDD" style="display:none">
                                        <label >Packs:</label>
                                        <select id="entDDD" onchange="selectEntertainment();">
					<option> Entertainment</option>
						<?php
							foreach($operators as $oDD)
							{
							if($oDD['products']['service_id'] == 3 && $oDD['products']['active'] == 1)echo "<option value='".$oDD['products']['name']."'>".$oDD['products']['name']."</option>";
							}
						?>

					</select>
					</span>-->
				</td>
			</tr>

			<tr id="subIdRow" style="display:none">
				<td>Sub Id :</td><td><input style="display:none;" type='text' name='manualRequestSubId' id='manualRequestSubId' value="" /></td>
			</tr>

			<tr>
				<td>Mobile :</td><td><input type="text" name="manualRequestMobNo" id="manualRequestMobNo" value="" /></td>
			</tr>



			<tr id="amtRow">
				<td>Amount:</td><td><input type="text" name="manualRequestAmount" id="manualRequestAmount" value="" /></td>
			</tr>

			<tr>
				<td ><input type="submit" value="submit" onclick="submitManualRequest('<?php echo $info[0]['retailers']['mobile']; ?>')"/></td>
			</tr>

		</table>
	</div>

	</br>

	<!--
	<div id="retStatus" style="display:block; text-align:left; padding:3px; border-color: #111111; border-width: 1px; border-style: solid; color: #000000;">
	<h4>Retailer Overall Summary</h4>
		<table id="manualReqTab" style="display:block;" border = '0'>
			<tr>
				<td>Last topup: </td><td><?php ?></td>
			</tr>
			<tr>
				<td>Avg Sale: </td><td><?php ?></td>
			</tr>
			<tr>
				<td>Last transaction: </td><td><?php ?></td>
			</tr>
			<tr>
				<td>Total complaints: </td><td><?php ?></td>
			</tr>
			<tr>
				<td>Total solved complaints: </td><td><?php ?></td>
			</tr>
			<tr>
				<td>Total unsolved complaints: </td><td><?php ?></td>
			</tr>
		</table>
	</div>
	-->
<!--

</td>-->
<!--<td valign="top" width="250">-->  

			<h4>Comments</h4>
			<div id="asdf"></div>
			<div style="max-height:250px;overflow:auto">
			<table  width="98%" cellspacing="2" cellpadding="2">
				<?php if(isset($comment))
			 	{
			 		 foreach($comment as $cm)
			 		 {
			 		 if(empty($cm['c']['ref_code']))
			 		 {
			 		 	 $ref_code=$cm['c']['ref_code'];
			 		 	 $color='#ffffe0';
			 		 }
			 		 else
			 		  {
			 		 	 $ref_code=$cm['c']['ref_code'];
			 		 	 $color=' #DFFFA5';
			 		  }

					if(empty($cm['u1']['name']))
							$var=$cm['u1']['mobile'];
					else
							$var=$cm['u1']['name'];

					echo "<tr bgcolor=$color>";
					echo "<td>".$var."&nbsp;&nbsp;&nbsp;";
					echo  $cm['c']['created']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					echo "<a href='/panels/transaction/".$ref_code."'>".$ref_code."</a></br>".$cm['c']['comments']."</td>";
					echo "</tr>";

					}
			  	 }
			  	 ?>
			 </table>
			 </div>
			 <table>
			 <tr style="height: 30px;">
						<td style="text-align: right;">Call Type:</td>
						<td><div>
							<select name="call_type" id="call_type">
								<option value="" selected disabled>Select Call Type</option>
								<?php foreach($call_types as $key => $call_type): ?>
								<option value="<?php echo $call_type['cc_call_types']['id'] ?>"><?php echo $call_type['cc_call_types']['name'] ?></option>
								<?php endforeach; ?>
							</select>
							</div>
						</td>
							<td style="text-align: right;">Tags:</td>
						<!--	 <td style="text-align:right;"><span title="Create a new tag" style="font-size:large;cursor:pointer;" onclick="createTag();"><span id="add_tag_plus">+</span><img src="/img/ajax-loader-bounce.gif" id="add_tag_load" style="display:none;"/></span> Tag:</td>-->
						<td>
							<div>
							<select id="tag">
		    					<?php foreach($taggings as $k => $tag): ?>
		    					<?php if($tag['taggings']['type'] == 'Retailer'): ?>
		    					<option value="<?php echo $tag['taggings']['id'] ?>"><?php echo $tag['taggings']['name'] ?></option>
		    					<?php endif ?>
		    					<?php endforeach; ?>
							</select>
							</div>
						</td>
					</tr>
					</table>
			<h4>Comment Box</h4>
				 <table>
					<tr>
						<td>
						<textarea class="input textarea" id="commentAreaForRetailer" style="height: 70px; width: 450px; line-height: 1.5em;
						font-family: Arial,Helvetica,sans-serif; font-size: 14px; direction: ltr; " autocomplete="off"></textarea>
						</td>
					</tr>
					<tr>
						<td>
							<img src="/img/ajax-loader-1.gif"
							id="ajax_loader" style="display: none;" />
							<input id="submit_comment" type="button" value="submit" onclick="takeComment('<?php echo $info[0]['retailers']['user_id']; ?>','<?php echo $info[0]['retailers']['id']; ?>')" >
						</td>
					</tr>
				</table>

</td>

<!-- top up request by retailer end -->
</tr>

</tr>
        
<!--
<tr>
<td>
<div id="logsBox" font size="12" bgcolor="#4876FF" width="100%">
<h4> USER EVENTS</h4>
<table>
					<?php
						if(isset($logs)){
						foreach($logs as $l){
							echo "<tr><td>".$l['l']['description']."</td></tr>";
							echo "<tr><td>".$l['l']['modified']."</td></tr>" ;

						}
						}
					?>
</table>
</div>
</td>
</tr>
-->
<!--services looping-->
<tr>
<div>

       <table id="service" cellpadding="4" cellspacing="0"  style="display: inline-block;">
            <!--<caption><b>SmartPay Info</b></caption>-->
            <tr><td colspan="9" align="center" class="appTitle">Service Info</td></tr>
            <tbody><tr>
                    <th> Service Name</th>
                    <th> Kit Status</th>
                    <th> Service Status</th>
                    <th> Service Activated By</th>
                    <th> Plan Name</th>
                    <th> Activated Date</th>
                    <th> Rental Due Date</th>
                    <th> Agent Id</th>                    
                   </tr>
                   <?php foreach($service_data as $servc) { ?>
                   <tr>
                        <td><?php echo $servc['s']['name'];?></td>
                        <td><?php echo $kitStatus[$servc['us']['kit_flag']];?></td>
                        <td><?php echo $servcStatus[$servc['us']['service_flag']];?></td>
                        <td><?php echo ($servc['u']['name'])?$servc['u']['name']:'Auto';?></td>
                        <td><?php echo $servc['sp']['plan_name'];?></td>
                        <td><?php echo $servc['us']['created_on'];?></td>
                        <td><?php echo $servc['us']['next_rental_debit_date'];?></td>                        
                        <td><?php echo $servc['us']['param1'];?></td>
                        
                   </tr>
                   <?php } ?>
               </tbody>
       </table>
        
</div>
</tr>
<h4>Amount transferred to retailer</h4>
		<table width="60%" border="1" cellspacing="0" width="" cellpadding="0" >
		<!--<table border="1" cellspacing="0" width="" cellpadding="0" style="display: inline-block;">-->
                    <!--<caption>Amount transferred to retailer</caption>-->
		<th width="20%">Salesman</th>
		<th width="20%">Amount</th>
		<th width="40%">Date</th>

		<?php
		$fAmount=0;
	//	$di = '';
		foreach($amountTransferred as $d)
		{
		//$di = $d['d']['name'];
		echo "<tr>";
		$fAmount += $d[0]['amount'];
		echo "<td>".$d[0]['s_name']."</td>";
		echo "<td>".$d[0]['amount']."</td>";
		echo "<td>".$d[0]['timestamp']."</td>";
		echo "</tr>";
		}

		echo "Total amount transferred Rs. ".$fAmount."</br></br>";
		//echo "Distributor: ".$di;
		?>

		</table>

	<?php if(!empty($smsResult)): ?>
	<h4>SMS based requests</h4>
		<div style="text-align:left; padding:5px; width: 190px; height: 150px;  border-color: #111111; border-width: 1px; border-style: solid; color: #000000; font-size: 10px; font-family: Arial; overflow: auto;">
			<?php
				foreach($smsResult as $d)
				{
					if(strpos($d['vn']['description'], 'Your password has ') !== false){

						$strlen = preg_replace("/[^0-9]/","",$d['vn']['description']);

						if(strlen($strlen)==4 || strlen($strlen)==6){

							$d['vn']['description'] = preg_replace('/[0-9]+/', '****', $d['vn']['description']);

						}
					}
				echo"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:void(0);' title='".$d['vn']['description']."'>".$d['vn']['timestamp'];echo "</br>";
				echo $d['vn']['message'] . " (".$d['vn']['virtual_num'].")";echo "</a></br></br>";
				}
			?>
		</div>
	<?php endif; ?>
<?php if(!empty($appRequests)): ?>
 <h4>App based requests</h4>

<div id= "appRequest" style="display:block; text-align:left; padding:5px; width: 190px; height: 150px;  border-color: #111111; border-width: 1px; border-style: solid; color: #000000; font-size: 10px; font-family: Arial; overflow: auto;">


		<?php

				foreach($appRequests as $d)
				{

					$params = json_decode($d['app_req_log']['params'],true);
					//print_r($params);
					$mOperator = $mapping[$params['method']][$params['operator']][$params['type']]['id'];
					$mNumber=$params['mobileNumber'];
					$mAmount=$params['amount'];


					if(isset($params['subId']) && $params['subId'] != $mNumber){
						$mSubId=$params['subId'];
						$text = "$mOperator*$mSubId*$mNumber*$mAmount";
					}
					else if(isset($params['accountNumber'])){
						$accountNum=$params['accountNumber'];
						if(!empty($params['param']))$accountNum = "$accountNum*".$params['param'];

						$text = "$mOperator*$accountNum*$mNumber*$mAmount";
					}
					else {
						$text = "$mOperator*$mNumber*$mAmount";
					}
					if(isset($params['special'])){
						$special=$params['special'];
						if($special == 1)$text .= "#";
					}
				echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:void(0);' title='".$d['app_req_log']['description']."'>".$d['app_req_log']['timesatmp']."</br>";
                                
				echo "$text</a></br></br>";
			}

	?>
		</div>
<?php endif; ?>
<?php if(!empty($ussdResult)): ?>
		<h4>USSD based requests</h4>
		<div style="text-align:left; padding:5px; width: 190px; height: 150px;  border-color: #111111; border-width: 1px; border-style: solid; color: #000000; font-size: 10px; font-family: Arial; overflow: auto;">
			<?php

				foreach($ussdResult as $d)
				{
					if(strpos($d['ussd_logs']['sent_xml'], 'Your password has ') !== false){

						$strlen = preg_replace("/[^0-9]/","",$d['ussd_logs']['sent_xml']);

						if(strlen($strlen)==4 || strlen($strlen)==6){

							$d['ussd_logs']['sent_xml'] = preg_replace('/[0-9]+/', '****', $d['ussd_logs']['sent_xml']);

						}
					}
				echo"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:void(0);' title='".$d['ussd_logs']['sent_xml']."'>".$d['ussd_logs']['date'] . " " . $d['ussd_logs']['time'];echo "</br>";
				echo $d['ussd_logs']['request'];echo "&nbsp;&nbsp;"."</a>";echo "</br></br>";
				}
			?>
		</div>
<?php endif ?>


<tr>
<td colspan="4"  style="overflow: hidden">
<a href="javascript:expand_collapse_table('transact');" style="color:black;"><span style="font-size: 125%;" id="transact_expand">+</span> Transaction Details</a>
<table id="transact" style="display:none;">
	<tr>
		<td valign="top">
			<table id="ret" border="1" cellpadding="0" width="100%" cellspacing="0" align="left">
			<tr><td colspan="13" align="center" class="appTitle">Retailer Transaction Information</td></tr>
			<tr>
			<th>Index</th>
  			<!-- <th width="5%">shop_transaction_id </th> -->
  			<th>Pay1 Txn Id</th>
  			<th>Recharge</th>
  			<th>Vendor</th>
  			<th>Cust Mob</th>
  			<th>Operator</th>
  			<th>Amount</th>
  			<th>Status</th>
  			<th>Via</th>
  			<th>Opening</th>
  			<th>Closing</th>
  			<th>Earning</th>
  			<th>Timestamp</th>
			</tr>
			<?php
			$i=1;
			$tot = 0;
			$tot_earn = 0;
			foreach($transRecords as $data)
			{
			echo "<tr>";
	        echo "<td>".$i."</td>";
			echo "<td><a href='/panels/transaction/".$data['va']['txn_id']."' >".$data['va']['txn_id']."</a></td>";


			echo "<td>".$data['s']['name']."&nbsp;</td>";
			echo "<td>".$data['v']['shortForm']."&nbsp;</td>";


			echo "<td><a href='/panels/userInfo/".$data['va']['mobile']."' >".$data['va']['mobile']."</a></td>";
			echo "<td>".$data['p']['name']."&nbsp;</td>";
			echo "<td>".$data['va']['amount']."&nbsp;</td>";
			$tot = $tot + $data['va']['amount'];
			$earn = ($data['va']['amount'] - ($data['st']['source_opening'] - $data['st']['source_closing']));
			$tot_earn = $tot_earn + $earn;

		   $reversalStats = '';
  		   if($data['va']['status'] == '0'){
			$reversalStats = 'In Process';
     		}else if($data['va']['status'] == '1'){
			$reversalStats = 'Successful';
	    	}else if($data['va']['status'] == '2'){
			$reversalStats = 'Failed';
		   }else if($data['va']['status'] == '3'){
			$reversalStats = 'Reversed';
		   }else if($data['va']['status'] == '4'){
			$reversalStats = 'Complaint In Process';
     		}else if($data['va']['status'] == '5'){
			$reversalStats = 'Complaint declined';
	     	}
			echo "<td>".$reversalStats."&nbsp;</td>";
			if($data['va']['api_flag'] == '0'){
				$via = 'SMS';
			}
			else if($data['va']['api_flag'] == '1'){
				$via = 'APP';
			}
			else if($data['va']['api_flag'] == '2'){
				$via = 'USSD';
			}
            else if($data['va']['api_flag'] == '3'){
				$via = 'ANDROID';
			}
           	else if($data['va']['api_flag'] == '5'){
				$via = 'JAVA';
			}
			else if($data['va']['api_flag'] == '4'){
				$via = 'PARTNER';
			}
			else if($data['va']['api_flag'] == '7'){
				$via = 'WIN7';
			}
			else if($data['va']['api_flag'] == '8'){
				$via = 'WIN8';
			}
            else if($data['va']['api_flag'] == '9'){
				$via = 'WEB';
			}
			echo "<td>$via</td>";
			echo "<td>".$data['st']['source_opening']."&nbsp;</td>";
			echo "<td>".$data['st']['source_closing']."&nbsp;</td>";
			echo "<td>".round($earn,2)."&nbsp;</td>";
			echo "<td>".$data['0']['timestamp']."&nbsp;</td>";
			echo "</tr>";
			$i++;
			}
			echo "<tr><td colspan='7' align='right'><b>Total</b></td><td><b>".$tot."</b></td><td></td><td></td><td></td><td>".round($tot_earn,2)."</td></tr>";
			?>
			</table>

                                                                                <table border="1" id="reversedTable" cellpadding="0" cellspacing="0" width="100%" align="left"  style="margin-top:1.5em;">
			<tr><td colspan="10" align="center" class="appTitle">Retailer other Transactions</td></tr>
			<tr>
			<th>Index</th>
			<th>Tran Id</th>
                        <th>Order Id</th>
			<th>Amount</th>
			<th>Description</th>
			<th>Status</th>
			<th>Txn Type (Cr/Db)</th>
			<th>Opening</th>
			<th>Closing</th>
			<th>Earning</th>
			<th>Timestamp</th>
			</tr>
			<?php
			$i=1;

                                foreach ($other_transactions as $mt) {
                                    $other = true;
                                    if (($mt['st']['user_id'] == 12) && ($mt['st']['confirm_flag'] != 0)) {
                                        $other = false;
                                    }
                                    if ($other) {
                                        $orderid = $order_id[$mt['st']['id']];
                                        echo "<tr><td>" . $i . "</td>";
                                        echo "<td>" . $mt['st']['id'] . "</td>";
                                        echo "<td>" . $orderid . "</td>";
                                        echo "<td>" . $mt['st']['amount'] . "</td>";
                                        echo "<td>" . $mt['st']['note'] . "</td>";
                                        echo "<td>" . (($mt['st']['confirm_flag'] == 0) ? 'Success' : 'Failed') . "</td>";
                                        echo "<td>" . (($mt['st']['type'] == DEBIT_NOTE) ? 'Debited' : (($mt['st']['type'] == CREDIT_NOTE) ? 'Credited' : (($mt['st']['type'] == REFUND) ? 'Refund' : ((($mt['st']['type'] == RENTAL) && ($mt['st']['user_id'] == 8)) ? 'MPOS Rental' : ((($mt['st']['type'] == RENTAL) && ($mt['st']['user_id'] != 8)) ? 'Rental' : ((($mt['st']['type'] == KITCHARGE) && ($mt['st']['user_id'] == 8)) ? 'MPOS Kit Charges' : ((($mt['st']['type'] == KITCHARGE) && ($mt['st']['user_id'] != 8)) ? 'Kit Charges' : 'Reversed - ' . $mt['st']['target_id']))))))) . "</td>";
                                        echo "<td>" . $mt['st']['source_opening'] . "</td>";
                                        echo "<td>" . ($mt['st']['source_closing'] + $mt['0']['earning']). "</td>";
                                        echo "<td>" . (($mt['st']['type'] != REFUND) ? $mt['0']['earning'] : $mt['st']['amount']) . "</td>";
                                        echo "<td>" . $mt['st']['timestamp'] . "</td></tr>";
                                        $i++;
                                    }
                                }
                                ?>
			</table>

                                                                                <table border="1" id="reversedTable" cellpadding="0" cellspacing="0" width="100%" align="left" style="margin-top:1.5em" >
			<tr><td colspan="12" align="center" class="appTitle">SmartPay Transaction Information</td></tr>
			<tr>
			<th>Index</th>
			<th>Pay1 Txn ID </th>
                                                                                <th>Description</th>
                                                                                <th>Customer Mobile</th>
			<th>Amount</th>
                                                                                <th>Txn Status</th>
			<th>Settlement Status</th>
			<th>Opening</th>
			<th>Closing</th>
			<th>Earning</th>
                                                                                <th>Time/Date</th>
			<th>Settlement Timestamp</th>
			</tr>
			<?php
                                                                                if(!empty($reports)):
			$i=1;
                                                                                $totalamt=0;
                                                                                $totalearning=0;

			foreach($reports as $report){
                                                                                                           if(in_array($report['txn_status'],array('P','S'))):
                                                                                                           $card_no= explode('-', $report['card_no']);
                                                                                                           $card_num= end($card_no);

                                                                                                           if($report['service_id']==8)
                                                                                                           {
                                                                                                               if($report['product_id']==$service_type[8]['MPOS Withdrawal : Non VISA'])
                                                                                                               {
                                                                                                                   $description="CW - DD : Non VISA ".$card_num;
                                                                                                               } else if( $report['product_id']==$service_type[8]['MPOS Withdrawal : VISA'] ){
                                                                                                                   $description="CW - DD : VISA ".$card_num;
                                                                                                               }
                                                                                                               elseif( ($report['product_id']==$service_type[8]['Sale - CC : EMI']) || ($report['product_id']==$service_type[8]['Sale - CC']) || ($report['product_id']==$service_type[8]['Sale - DC']))
                                                                                                               {
//                                                                                                                   $cardtype=$report['payment_card_type']=="DEBIT"?"DC":"CC";
                                                                                                                    $cardtype = '--';
                                                                                                                    if( strtolower($report['payment_card_type']) == "debit" ){
                                                                                                                        $cardtype = "DC";
                                                                                                                    } else if( strtolower($report['payment_card_type']) == "credit" ){
                                                                                                                        $cardtype = "CC";
                                                                                                                        if($report['product_id']==$service_type[8]['Sale - CC : EMI']){
                                                                                                                            $cardtype = "CC : EMI";
                                                                                                                        }
                                                                                                                    }
                                                                                                                   $description="Sale - ".$cardtype." : ".$card_num;
                                                                                                               }
                                                                                                           }
                                                                                                           elseif($report['service_id']==9)
                                                                                                           {
                                                                                                               $description="UPI - ".$report['vpa'];
                                                                                                           }
                                                                                                           elseif($report['service_id']==10)
                                                                                                           {
                                                                                                            //    $description="Aadhar - ".$report['aadhar_no'];
                                                                                                                $description="AEPS";
                                                                                                                if(in_array($report['product_id'],$service_type[$report['service_id']])){
                                                                                                                    $description = array_search($report['product_id'],$service_type[$report['service_id']]);
                                                                                                                }
                                                                                                           }
                                                                                                           $totalamt=$totalamt+$report['txn_amount'];
                                                                                                           $txn_status=$report['txn_status']=="P"?"Pending":(($report['txn_status']=="S")?"Success":"Failed - ");
                                                                                                           $settlement_flag=$report['settlement_flag']==0?"W - ":"B - ";
                                                                                                           $status=$report['settlement_status']=="P"?"Pending":(($report['settlement_status']=="S")?"Settled":"Failed");
                                                                                                           $settlement_status=$settlement_flag.$status;

																										   if( isset($report['incentive_details']) ){
																												$closing = $report['incentive_details']['closing'];
																												$amt_settled = $report['wallet_details']['amt_settled'] + $report['incentive_details']['amt_settled'];
																										   } else {
																											   $closing = $report['wallet_details']['closing'];
																											   $amt_settled = $report['wallet_details']['amt_settled'];
																										   }

																										   $earning=$amt_settled-$report['txn_amount'];
                                                                                                           $totalearning=$totalearning+$earning;

                                                                                                           if($report['settlement_flag']==0){
                                                                                                               $opening=(strtolower($report['wallet_details']['type'])=="cr")?($closing-$amt_settled):($closing-$amt_settled);
                                                                                                           } else if( isset($report['incentive_details']) ){
                                                                                                               $opening = $closing-$report['incentive_details']['amt_settled'];
                                                                                                           } else {
																											   $opening = 0;
																											   $earning = 0;
																										   }



				echo "<tr><td>".$i."</td>";
				echo "<td><a target='_blank' href='/panels/transaction/".$report['txn_id']."'>".$report['txn_id']."</td>";
				echo "<td>".$report['device_type']." ".$description."</td>";
				echo "<td><a target='_blank' href='/panels/userInfo/".$report['mobile_no']."'>".$report['mobile_no']."</a></td>";
                                                                                                           echo "<td>".$report['txn_amount']."</td>";
                                                                                                           echo "<td>".$txn_status." ".$report['status_description']."</td>";
                                                                                                           echo "<td>".$settlement_status."</td>";
                                                                                                           echo "<td>".$opening."</td>";
                                                                                                           echo "<td>".$closing."</td>";
                                                                                                           echo "<td>".$earning."</td>";
                                                                                                           echo "<td>".$report['txn_time']."</td>";
                                                                                                           echo "<td>".$report['settled_at']."</td>";
				$i++;
                                                                                                           endif;
			}
                                                                                echo "<tr><td colspan='4' align='right'><b>Total</b></td><td><b>".$totalamt."</b></td><td></td><td></td><td></td><td></td><td><b>".round($totalearning,2)."</b></td><td></td><td></td></tr>";
                                                                                else:
                                                                                        echo "No transaction found.";
                                                                                endif;
                                                                                ?>
			</table>
		</td>
		<td valign="top">
			<!-- Reverse request by retailer start -->


			<table border="1" id="reverseTable" cellpadding="0" cellspacing="0" width="100%" align="left" >
			<tr><td colspan="12" align="center" class="appTitle">Retailers request for  Reversal</td></tr>
			<tr>
			<th>Index</th>
			<th>Tran Id</th>
			<th>Recharge</th>
			<th>Vendor</th>
			<th>Cust Mob</th>
			<th>Operator</th>
			<th>Amount</th>
			<th>Status</th>
			<th>Via</th>
			<th>Opening</th>
			<th>Closing</th>
			<th>Timestamp</th>
			</tr>

		<?php

			$count=1;
			$ps='';
			foreach($reversalInProcess as $d)
				{
				//echo $count;
				if($d['va']['status']== '0')
					$ps = 'In Process';
				else if($d['va']['status']== '1')
					$ps = 'Successful';
			  	else if($d['va']['status']== '2')
					$ps = 'Failed';
				else if($d['va']['status']== '3')
					$ps = 'Reversed';
		      	else if($d['va']['status']== '4')
					$ps='Reversal In Process';
				else if($d['va']['status']== '5')
					 $ps='Reversal declined';

				echo "<tr>";
				echo "<td>".$count."</td>";
		    	echo "<td><a href='/panels/transaction/".$d['va']['txn_id']."'>".$d['va']['txn_id']."</a></td>";

		        echo"<td>".$d['s']['name']."</td>";
		    	echo"<td>".$d['v']['shortForm']."</td>";

		    	echo "<td><a href='/panels/userInfo/".$d['va']['mobile']."'>".$d['va']['mobile']."</a></td>";
		    	echo "<td>".$d['p']['name']."</td>";
		    	echo "<td>".$d['va']['amount']."</td>";
		   	    echo "<td>".$ps."</td>";
		   	    if($d['va']['api_flag'] == '0'){
					$via = 'SMS';
				}
				else if($d['va']['api_flag'] == '1'){
					$via = 'APP';
				}
				else if($d['va']['api_flag'] == '2'){
					$via = 'USSD';
				}
                else if($d['va']['api_flag'] == '3'){
					$via = 'ANDROID';
				}
                else if($d['va']['api_flag'] == '5'){
					$via = 'JAVA';
				}
				else if($d['va']['api_flag'] == '4'){
					$via = 'PARTNER';
				}
				else if($d['va']['api_flag'] == '7'){
					$via = 'WIN7';
				}
				else if($d['va']['api_flag'] == '8'){
					$via = 'WIN8';
				}
                else if($d['va']['api_flag'] == '9'){
					$via = 'WEB';
				}
				echo "<td>$via</td>";
		   	    echo "<td>".$d['st']['source_opening']."</td>";
		   	    echo "<td>".$d['st']['source_closing']."</td>";
		    	echo "<td>".$d['0']['timestamp']."</td>";
		    	echo "</tr>";
		    	$count++;
		 		}
				?>
				</table>
				<!-- Reverse request by retailer end -->
				</br>
				</br>
				</br>
				</br>

				<!-- Retailers reversed request start -->

				<table border="1" id="reversedTable" cellpadding="0" cellspacing="0" width="100%" align="left" >
			<tr><td colspan="12" align="center" class="appTitle">Retailers "REVERSED" Transactions</td></tr>
			<tr>
			<th>Index</th>
			<th>Tran Id</th>
			<th>Recharge</th>
			<th>Vendor</th>
			<th>Cust Mob</th>
			<th>Operator</th>
			<th>Amount</th>
			<th>Status</th>
			<th>Via</th>
			<th>Opening</th>
			<th>Closing</th>
			<th>Timestamp</th>
			</tr>

		<?php

			$count=1;
			$ps='';
			foreach($alreadyReversed as $d)
				{
				//echo $count;
				if($d['va']['status']== '0')
					$ps = 'In Process';
				else if($d['va']['status']== '1')
					$ps = 'Successful';
			  	else if($d['va']['status']== '2')
					$ps = 'Failed';
				else if($d['va']['status']== '3')
					$ps = 'Reversed';
		      	else if($d['va']['status']== '4')
					$ps='Reversal In Process';
				else if($d['va']['status']== '5')
					 $ps='Reversal declined';

				echo "<tr>";
				echo "<td>".$count."</td>";
		    	echo "<td><a href='/panels/transaction/".$d['va']['txn_id']."'>".$d['va']['txn_id']."</a></td>";

		        echo"<td>".$d['s']['name']."</td>";
		    	echo"<td>".$d['v']['shortForm']."</td>";

		    	echo "<td><a href='/panels/userInfo/".$d['va']['mobile']."'>".$d['va']['mobile']."</a></td>";
		    	echo "<td>".$d['p']['name']."</td>";
		    	echo "<td>".$d['va']['amount']."</td>";
		   	    echo "<td>".$ps."</td>";
		   	    if($d['va']['api_flag'] == '0'){
					$via = 'SMS';
				}
				else if($d['va']['api_flag'] == '1'){
					$via = 'APP';
				}
				else if($d['va']['api_flag'] == '2'){
					$via = 'USSD';
				}
                else if($d['va']['api_flag'] == '3'){
					$via = 'ANDROID';
				}
                else if($d['va']['api_flag'] == '5'){
					$via = 'JAVA';
				}
				else if($d['va']['api_flag'] == '4'){
					$via = 'PARTNER';
				}
				else if($d['va']['api_flag'] == '7'){
					$via = 'WIN7';
				}
				else if($d['va']['api_flag'] == '8'){
					$via = 'WIN8';
				}
                else if($d['va']['api_flag'] == '9'){
					$via = 'WEB';
				}
				echo "<td>$via</td>";
		   	    echo "<td>".$d['st']['source_opening']."</td>";
		   	    echo "<td>".$d['st']['source_closing']."</td>";
		    	echo "<td>";
		    	echo $d['0']['timestamp'];
		    	echo "</td>";
		    	echo "</tr>";
		    	$count++;
		 		}
				?>
				</table>
				<!-- Retailers  reversed request by  end -->
                                                                    <table border="1" id="reversedTable" cellpadding="0" cellspacing="0" width="100%" align="left" style="margin-top:1.5em" >
			<tr><td colspan="12" align="center" class="appTitle">SmartPay REVERSED Transaction</td></tr>
			<tr>
			<th>Index</th>
			<th>Pay1 Txn ID </th>
                                                                                <th>Description</th>
                                                                                <th>Customer Mobile</th>
			<th>Amount</th>
                                                                                <th>Txn Status</th>
			<th>Settlement Status</th>
			<th>Opening</th>
			<th>Closing</th>
			<th>Earning</th>
                                                                                <th>Time/Date</th>
			<th>Settlement Timestamp</th>
			</tr>
			<?php
                                                                                if(!empty($reports)):
			$i=1;
                                                                                $totalamt=0;
                                                                                $totalearning=0;

			foreach($reports as $report){
                                                                                                           if(!in_array($report['txn_status'],array('P','S'))):
                                                                                                           $card_no= explode('-', $report['card_no']);
                                                                                                           $card_num= end($card_no);

                                                                                                           if($report['service_id']==8)
                                                                                                           {
                                                                                                               if($report['product_id']==$service_type[8]['MPOS Withdrawal : Non VISA'])
                                                                                                               {
                                                                                                                   $description="CW - DD : Non VISA ".$card_num;
                                                                                                               } else if( $report['product_id']==$service_type[8]['MPOS Withdrawal : VISA'] ){
                                                                                                                   $description="CW - DD : VISA ".$card_num;
                                                                                                               }
                                                                                                               elseif( ($report['product_id']==$service_type[8]['Sale - CC : EMI']) || ($report['product_id']==$service_type[8]['Sale - CC']) || ($report['product_id']==$service_type[8]['Sale - DC']))
                                                                                                               {
//                                                                                                                   $cardtype=$report['payment_card_type']=="DEBIT"?"DC":"CC";

                                                                                                                    $cardtype = '--';
                                                                                                                    if( strtolower($report['payment_card_type']) == "debit" ){
                                                                                                                        $cardtype = "DC";
                                                                                                                    } else if( strtolower($report['payment_card_type']) == "credit" ){
                                                                                                                        $cardtype = "CC";
                                                                                                                        if( $report['product_id']==$service_type[8]['Sale - CC : EMI'] ){
                                                                                                                            $cardtype = "CC : EMI";
                                                                                                                        }
                                                                                                                    }
                                                                                                                   $description="Sale - ".$cardtype." : ".$card_num;
                                                                                                               }
                                                                                                           }
                                                                                                           elseif($report['service_id']==9)
                                                                                                           {
                                                                                                               $description="UPI - ".$report['vpa'];
                                                                                                           }
                                                                                                           elseif($report['service_id']==10)
                                                                                                           {
                                                                                                                // $description="Aadhar - ".$card_num;
                                                                                                                $description="AEPS";
                                                                                                                if(in_array($report['product_id'],$service_type[$report['service_id']])){
                                                                                                                    $description= array_search($report['product_id'],$report[$report['service_id']]);
                                                                                                                }
                                                                                                           }
                                                                                                           $totalamt=$totalamt+$report['txn_amount'];
                                                                                                           $txn_status=$report['txn_status']=="P"?"Pending":(($report['txn_status']=="S")?"Success":"Failed - ");
                                                                                                           $settlement_flag=$report['settlement_flag']==0?"W - ":"B - ";
                                                                                                           $status=$report['settlement_status']=="P"?"Pending":(($report['settlement_status']=="S")?"Settled":"Failed");
                                                                                                           $settlement_status=$settlement_flag.$status;
                                                                                                           $opening=(strtolower($report['wallet_details']['type'])=="cr")?($report['wallet_details']['closing']-$report['wallet_details']['amt_settled']):($report['wallet_details']['closing']+$report['wallet_details']['amt_settled']);
                                                                                                           $earning=$report['wallet_details']['amt_settled']-$report['txn_amount'];
                                                                                                           $totalearning=$totalearning+$earning;
				echo "<tr><td>".$i."</td>";
				echo "<td><a target='_blank' href='/panels/transaction/".$report['txn_id']."'>".$report['txn_id']."</td>";
				echo "<td>".$report['device_type']." ".$description."</td>";
				echo "<td><a target='_blank' href='/panels/userInfo/".$report['mobile_no']."'>".$report['mobile_no']."</a></td>";
                                                                                                           echo "<td>".$report['txn_amount']."</td>";
                                                                                                           echo "<td>".$txn_status." ".$report['status_description']."</td>";
                                                                                                           echo "<td>".$settlement_status."</td>";
                                                                                                           echo "<td>".$opening."</td>";
                                                                                                           echo "<td>".$report['wallet_details']['closing']."</td>";
                                                                                                           echo "<td>".$earning."</td>";
                                                                                                           echo "<td>".$report['txn_time']."</td>";
                                                                                                           echo "<td>".$report['settled_at']."</td>";
				$i++;
                                                                                                           endif;
			}
                                                                                echo "<tr><td colspan='4' align='right'><b>Total</b></td><td><b>".$totalamt."</b></td><td></td><td></td><td></td><td></td><td><b>".round($totalearning,2)."</b></td><td></td><td></td></tr>";

                                                                                else:
                                                                                        echo "No transaction found.";
                                                                                endif;
                                                                                ?>
			</table>
                            <table border="1" id="otherreversedTable" cellpadding="0" cellspacing="0" width="100%" align="right"  style="margin-top:1.5em;">
                                <tr><td colspan="10" align="center" class="appTitle">DMT Reversed Transactions</td></tr>
                                <tr>
                                    <th>Index</th>
                                    <th>Tran Id</th>
                                    <th>Order Id</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Txn Type (Cr/Db)</th>
                                    <th>Opening</th>
                                    <th>Closing</th>
                                    <th>Earning</th>
                                    <th>Timestamp</th>
                                </tr>
                                <?php
                                $i = 1;

                                foreach ($dmt_failed as $mt) {
                                    $orderid = $order_id[$mt['st']['id']];
                                    echo "<tr><td>" . $i . "</td>";
                                    echo "<td>" . $mt['st']['id'] . "</td>";
                                    echo "<td>" . $orderid . "</td>";
                                    echo "<td>" . $mt['st']['amount'] . "</td>";
                                    echo "<td>" . $mt['st']['note'] . "</td>";
                                    echo "<td>" . (($mt['st']['confirm_flag'] == 0) ? 'Success' : 'Failed') . "</td>";
                                    echo "<td>" . (($mt['st']['type'] == DEBIT_NOTE) ? 'Debited' : (($mt['st']['type'] == CREDIT_NOTE) ? 'Credited' : (($mt['st']['type'] == REFUND) ? 'Refund' : ((($mt['st']['type'] == RENTAL) && ($mt['st']['user_id'] == 8)) ? 'MPOS Rental' : ((($mt['st']['type'] == RENTAL) && ($mt['st']['user_id'] != 8)) ? 'Rental' : ((($mt['st']['type'] == KITCHARGE) && ($mt['st']['user_id'] == 8)) ? 'MPOS Kit Charges' : ((($mt['st']['type'] == KITCHARGE) && ($mt['st']['user_id'] != 8)) ? 'Kit Charges' : 'Reversed - ' . $mt['st']['target_id']))))))) . "</td>";
                                    echo "<td>" . $mt['st']['source_opening'] . "</td>";
                                    echo "<td>" . $mt['st']['source_closing'] . "</td>";
                                    echo "<td>" . (($mt['st']['type'] != REFUND) ? ($mt['st']['amount'] - ($mt['st']['source_opening'] - $mt['st']['source_closing'])) : ($mt['st']['amount'])) . "</td>";
                                    echo "<td>" . $mt['st']['timestamp'] . "</td></tr>";
                                    $i++;
                                }
                                ?>
                            </table>

		</td>
	</tr>
</table>

<div id="myModal" class="modal">
    <div class="modal-content">
    <span class="close">&times;</span>
    <h3><center>Balance History</center> </h3>
    
    <center>   
        <table class="table table-bordered table-hover table-striped table-responsive txntable" id="customers" style="border-collapse: collapse;border: 1px solid black;">
            <thead>
                  <tr>
                    <th><center>Time</center></th>
                    <th><center>Bal Transaction ID</center></th>
                    <th><center>Particular</center></th>
                    <th><center>Debit/Credit</center></th>
                    <th><center>Opening</center></th>
                    <th><center>Closing</center></th>
                  </tr>
            </thead>
        <tbody>
            <?php
                foreach($txns as $txn){
                    echo '<tr>';
                    echo '<td><center>'.$txn['time'].'</center></td>';
                    echo '<td><center>'.$txn['shop_transaction_id'].'</center></td>';
                    echo '<td><center>'.$txn['description'].'</center></td>';
                    echo '<td><center>'.($txn['credit'] > '0.0' ? $txn['credit'].' Cr' : $txn['debit'].' Dr').'</center></td>';
                    echo '<td><center>'.$txn['opening'].'</center></td>';
                    echo '<td><center>'.$txn['closing'].'</center></td>';
                    echo '</tr>';
                }
            ?>
        </tbody>   
        </center>
            </table>
    </center>
    </div>
</div>
</table>
</td>
</tr><!-- 3rd tr end -->

</table>
</div>
<?php } ?>
<script>
	var sel1 = document.getElementsByName('manualRequestRadio');
	sel1[0].checked = true;
        
        var modal          = document.getElementById('myModal');
        var btn            = document.getElementById("txnhistory");
        var span           = document.getElementsByClassName("close")[0];  
        
        span.onclick = function() {
            modal.style.display = "none";
        }
        
        btn.onclick = function() { 
            modal.style.display = "block";
            
        }
        
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
</script>

<?php } ?>
<script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script> 
<script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/boot/js/jquery.dataTables.min.js"></script>
<script>
    jQuery(document).ready(function($) {
        $('.txntable').dataTable({
            "aoColumnDefs": [{"bSortable": false, "aTargets": [0]}],
            "pageLength": 10,
            "lengthMenu": [10, 25, 50],
        });
    });
</script>
<script>jQuery.noConflict();</script>
