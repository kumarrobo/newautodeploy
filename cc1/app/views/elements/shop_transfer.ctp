
 <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  
<style type="text/css">
.autocomplete-suggestions {border: 1px solid #999; background: #fff; cursor: default; overflow: auto; }
.autocomplete-suggestion { padding: 10px 5px; font-size: 1.0em; white-space: nowrap; overflow: hidden; }
.autocomplete-selected { background: #f0f0f0; }
.autocomplete-suggestions strong { font-weight: normal; color: #3399ff; }
 ul#ui-id-1 { font-size:12px;text-align:left; }
</style>
  <script>
var $j = jQuery.noConflict()
  $j(function() {
      <?php if($_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR){
            foreach($retailers as $retailer) {
            $datavalue[] = array("value" => ($retailer['ur']['shopname'] != '' ? $retailer['ur']['shopname'] : $retailer['retailers']['shopname'])."-".$retailer['retailers']['mobile'],"data"=> $retailer['retailers']['id']);
                  } }else if($_SESSION['Auth']['User']['group_id'] == ADMIN || $_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == SUPER_DISTRIBUTOR){
            foreach($record_without_sd as $distributor) {
            $datavalue[] = array("value" => $distributor[$modelName]['company']." - ".$distributor[$modelName]['id'],"data"=> $distributor[$modelName]['id']."_".$distributor[$modelName]['margin']);
      } } ?>
    var data1 = <?php echo json_encode($datavalue); ?>;
    var projects = data1;
 
    $j('.autocomplete').autocomplete({
      minLength: 0,
      source: projects,
      focus: function( event, ui ) {
        $j('.autocomplete').val( ui.item.value );
        
        return false;
      },
      select: function( event, ui ) {
        $j('.autocomplete').val( ui.item.value );
         <?php if($_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR){ ?>
        getLastTrnfrd(ui.item.data);
         <?php } ?>
             
        <?php if($_SESSION['Auth']['User']['group_id'] == ADMIN || $_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == SUPER_DISTRIBUTOR) {?>
            $j("#shop_select1").val(ui.item.data);
            var shopval = $j("#shop_select").val();
            var val = $j("#shop_select1").val();
            var arr = val.split("_");
            var shop_id = arr[0];
            getLastTrnfrd(shop_id);
            $j("#shop").val(shop_id);
            $j("#shop2").val(shopval);
            var amt =  $j("#amount").val();
            amt = amt=="" ? 0 : amt;
            $j("#amount").val(parseInt(amt));
            var comm =  parseFloat($j("#commission_per").val()) * parseFloat( amt ) / 100 ;
            $j("#commission").val(parseFloat(comm).toFixed(2));
    <?php } ?>
        $j( "#shop" ).val( ui.item.data );
       
        return false;
      }
    })
    .autocomplete( "instance" )._renderItem = function( ul, item ) {
      return $j( "<li>" )
        .append( "<a>" + item.value +  "</a>" )
        .appendTo( ul );
    };
 });
  </script>

<?php echo $form->create('shop');?>
     	<fieldset class="fields1" style="border:0px;margin:0px;">
          <?php echo $this->Session->flash(); echo "<br/>";?>
           
			<div class="appTitle">Transfer Balance</div>
				<div style="width:60%; float:left">
				<div>
                <div>
				<div class="field" style="padding-top:5px;">
                    <div class="fieldDetail" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="amount">Enter Amount (<img src="/img/rs.gif" align="absmiddle">)</label></div>
                         <div class="fieldLabelSpace1">
                            <input tabindex="1" type="text" id="amount" name="data[amount]" autocomplete="off" onkeyup="numinwrd('amount')" value="<?php if(isset($data)) echo $data['amount'];?>"/><span style="color:green;font-size:11px" id="amount_word"></span>
                         </div>                     
                 	</div>
            	 </div>
            	 </div>
            	 <?php if($_SESSION['Auth']['User']['group_id'] == ADMIN || $_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == SUPER_DISTRIBUTOR){?>
                 <div class="field">
                    <div class="fieldDetail" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="shop"><span id="label">Select <?php echo $modelName; ?></span></label></div>
                         <div class="fieldLabelSpace1">
                           <input type ="text" tabindex="2" value="<?php if(isset($data) && isset($data['shop1'])) echo $data['shop1'];?>" id="shop_select" class="autocomplete" style="width:459px;" placeholder="Select <?php echo $modelName; ?>">
                             <input type="hidden"  value="<?php if(isset($data) && isset($data['shop'])) echo $data['shop'];?>" id="shop" name="data[shop]"/>
                             <input type="hidden"  value = "<?php if(isset($data) && isset($data['shop1'])) echo $data['shop1'];?>" id="shop2" name="data[shop1]"/>
                             <input type ="hidden" id="shop_select1" style="width:459px;">
                         	<div id="dist_sub"></div>
                         </div>
                    </div>
            	 </div>
            	 <?php } else if($_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR){ ?>
            	 <div class="field">
                     <div class="fieldDetail" style="width:350px;">
                        <div class="fieldLabel1 leftFloat"><label for="shop"><span id="label">Select Retailer</span></label></div>
                        <div class="fieldLabelSpace1">
                            <input type="text" class="autocomplete" style="width:459px;" id="shop1" name="data[shop1]" tabindex="2" placeholder="Select Retailer" value="<?php if(isset($data) && isset($data['shop1'])) echo $data['shop1'];?>">
                             <input type ="hidden" id="shop" name="data[shop]" style="width:459px;" value="<?php if(isset($data) && isset($data['shop'])) echo $data['shop'];?>">
                               <div id="dist_sub"></div>
                         </div>
                    </div>
            	 </div>
            	 <?php } ?>         	 
            	 </div>
				
            	 <?php if($_SESSION['Auth']['User']['group_id'] == ADMIN || $_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR){?>
            	 <div>
				 <div class="field" style="padding-top:5px; display:none">
                                     
                    <div class="fieldDetail" style="width:455px;">
                        
                         <div class="fieldLabel1 leftFloat"><label for="commission">Enter Discount (<img src="/img/rs.gif" align="absmiddle">)</label></div>
                        
                         <div class="fieldLabelSpace1">
                            <input tabindex="3" type="text" id="commission" name="data[commission]" autocomplete="off"  value="<?php if(isset($data)) echo $data['commission'];?>"/><span style="color:green;font-size:11px" id="commission_word"></span>
                            <a href="javascript:void(0)" onclick="setCommissionAndPer()">Auto calculate</a>
                         </div>
                         
                 	</div>
            	 </div>
                     <div class="field" style="padding-top:5px;">
                         <div class="fieldLabel1 leftFloat"><label for="commission">Discount ( % )</label></div>
                        
                         <div class="fieldLabelSpace1">
                           <input readonly tabindex="3" type="text" id="commission_per" name="data[commission_per]" autocomplete="off"  value="<?php if(isset($data)) echo $data['commission_per'];?>"/> 
                         </div>
                     </div>
            	 </div>
            	 <?php }?>
            	 
            	 <?php if($_SESSION['Auth']['User']['group_id'] == ADMIN || $_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == SUPER_DISTRIBUTOR){?>
                 
            	 <div>
            	 <div class="field">
                    <div class="fieldDetail" style="width:800px;">
                         <div class="fieldLabel1 leftFloat"><label for="type">Transfer Type</label></div>
                         <div class="fieldLabelSpace1">
                         	<input name="data[group]" type="hidden" value="<?php echo $_SESSION['Auth']['User']['group_id']; ?>">
                                <?php if($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR || $_SESSION['Auth']['User']['group_id'] == SUPER_DISTRIBUTOR) { ?>
                         	<input type="radio" name="data[typeRadio]" id="typeRadio" value="1" <?php if(isset($data) && $data['typeRadio'] == 1) echo "checked"; ?>/> Cash
                                <?php } else { ?>
                         	<input type="radio" name="data[typeRadio]" id="typeRadio" value="1" <?php if(!isset($data) || $data['typeRadio'] == 1) echo "checked"; ?>/> Cash
                                <?php } ?>
                                <input style="margin-left:10px;" type="radio" name="data[typeRadio]" id="typeRadio" value="2" <?php if(isset($data) && $data['typeRadio'] == 2) echo "checked"; ?> /> NEFT/RTGS
                                <input style="margin-left:10px;" type="radio" name="data[typeRadio]" id="typeRadio" value="3" <?php if(isset($data) && $data['typeRadio'] == 3) echo "checked"; ?> /> ATM Transfer
                                <input style="margin-left:10px;" type="radio" name="data[typeRadio]" id="typeRadio" value="4" <?php if(isset($data) && $data['typeRadio'] == 4) echo "checked"; ?> /> Cheque/DD
                         </div>
                    </div>
            	 </div>
            	 </div>

				
            <?php if($_SESSION['Auth']['User']['group_id'] == ADMIN || ($_SESSION['Auth']['User']['group_id'] == MASTER_DISTRIBUTOR && in_array($_SESSION['Auth']['id'],explode(",",MDISTS))) || ($_SESSION['Auth']['User']['group_id'] == DISTRIBUTOR && in_array($_SESSION['Auth']['id'],explode(",",DISTS)))){?>
                  
            <div>	 
                 <div class="field">
                 <div class="fieldDetail" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="type">Bank Details</label></div>
                         <div class="fieldLabelSpace1">
                         	<select name ="data[bank_name]" id ="bank_name"  value="">
                              <option value= "">--SELECT BANK----</option>
                             <?php foreach($bankDetails as $bval){ ?>
                             <option value ="<?php echo $bval['bank_details']['bank_name']; ?>" <?php if(isset($data) && isset($data['bank_name']) && $bval['bank_details']['bank_name'] == $data['bank_name']) echo " selected";?>><?php echo $bval['bank_details']['bank_name']; ?></option>
                             <?php } ?>
                            </select>
                         </div>
                    </div>
            	 </div>
                  </div>
            	 
            	 <?php } ?>
            	 
            	 <div class="altRow" id="divType">
            	 <div class="field">
                    <div class="fieldDetail" style="width:350px;">
                         <div class="fieldLabel1 leftFloat"><label for="type">Bank TxnID</label></div>
                         <div class="fieldLabelSpace1">
                           	<textarea id="description" name="data[description]" style="width:180px;height:55px;"><?php if(isset($data['description']))echo $data['description']; ?></textarea>
                         </div>
                    </div>
            	 </div>
            	 </div>
            	 <?php } ?>
            	<div class="field" style="padding-top:15px;">               		
                    <div class="fieldDetail" style="width:350px;">
                         <div class="fieldLabel1 leftFloat">&nbsp;</div>
                         <div class="fieldLabelSpace1" id="sub_butt">
                         	<?php echo $ajax->submit('Transfer Balance', array('id' => 'sub', 'tabindex'=>'3','url'=> array('controller' => 'shops', 'action'=>'amountTransfer'), 'class' => 'retailBut enabledBut', 'after' => 'showLoader2("sub_butt");', 'update' => 'innerDiv')); ?>
                         </div>                         
                    </div>
                </div>
                <?php echo $this->Session->flash();?>
                </div>
                
                <div id="lastTxns">
                
                
                </div>
		</fieldset>
<?php echo $form->end(); ?>
<script>
if($('amount'))
	$('amount').focus();
	
function typeCheck()
{
	var sel = document.getElementsByName('data[typeRadio]');
	var str = '';
	for (var i=0; i<sel.length; i++)
	 {
		if (sel[i].checked == true) 
		{ 
			str = sel[i].value; 
		}
	 }

	if(str == 1)
	{
		$('divType').hide();
	}
	else {
		$('divType').show();
	}
			
}

function autocalculate(){
	var id = $('shop').value;
	var amount = $('amount').value;
	if(id == 0){
		alert("Please " + $('label').innerHTML);
	}
	else if(amount <= 0){
		alert("Please enter correct amount");
	}
	else {
		var url = '/shops/calculateCommission';
                var params = {'id' : id,'amount': amount};
                var myAjax = new Ajax.Request(url, {method: 'post', parameters: params,
                onSuccess:function(transport)
                                {		
                                        var html = transport.responseText;
                                        $('commission').value=html;
                                        //numinwrd('commission');
                                }
                });
	}
}

function numinwrd(id)
  {
     var numbr=document.getElementById(id).value;
     var str=new String(numbr)   
     var splt=str.split("");
     var rev=splt.reverse();
     var once=['Zero', ' One', 'Two', 'Three', 'Four',  'Five', 'Six', 'Seven', 'Eight', 'Nine'];
     var twos=['Ten', ' Eleven', ' Twelve', ' Thirteen', ' Fourteen', ' Fifteen', ' Sixteen', ' Seventeen', ' Eighteen', ' Nineteen'];
     var tens=[ '', 'Ten', ' Twenty', ' Thirty', ' Forty', ' Fifty', ' Sixty', ' Seventy', ' Eighty', ' Ninety' ];
     numlen=rev.length;
     var word=new Array();
     
     var j=0;   
     for(i=0;i<numlen;i++)
       {
          switch(i)
           {
            case 0:
                  if((rev[i]==0) || (rev[i+1]==1))
                   {
                      word[j]='';                    
                   }
                   else
                   {
                     word[j]=once[rev[i]];
                    }
                   word[j]=word[j] ;
                   
                   break;
            case 1:
                abovetens();  
                   break;
              case 2:
                if(rev[i]==0)
                {
                  word[j]='';
                } 
               else if((rev[i-1]==0) || (rev[i-2]==0) )
                {
                   word[j]=once[rev[i]]+"Hundred ";                
                }
                else 
                {
                    word[j]=once[rev[i]]+"Hundred and";
                } 
               break;
             case 3:
                    if(rev[i]==0 || rev[i+1]==1)
                   {
                      word[j]='';                    
                   } 
                   else
                   {
                     word[j]=once[rev[i]];
                   }
                if((rev[i+1]!=0) || (rev[i] > 0))
                {
	                 word[j]= word[j]+" Thousand";
	              }
                  break;  
             case 4:
                  abovetens(); 
                    break;  
           
              case 5:
                   if((rev[i]==0) || (rev[i+1]==1))
                   {
                      word[j]='';                    
                   } 
                   else
                   {
                     word[j]=once[rev[i]];
                   }
                word[j]=word[j]+"Lakhs";
                  break;  
          
           case 6:
                  abovetens(); 
                    break;
         
          case 7:
                   if((rev[i]==0) || (rev[i+1]==1))
                   {
                      word[j]='';                    
                   } 
                   else
                   {
                     word[j]=once[rev[i]];
                   }
              word[j]= word[j]+"Crore";
                    break;  
          
           case 8:
                  abovetens(); 
                    break;    
                 default:
	               break;
              }
       
          j++;  
       
       }   
  
function abovetens()
{
	if(rev[i]==0)
    {
        word[j]='';
    }
	else if(rev[i]==1)
    {
    	word[j]=twos[rev[i-1]];
    }
   	else
    {
    	word[j]=tens[rev[i]];
    }
}

word.reverse();
var finalw='';
for(i=0;i<numlen;i++)
{

  finalw= finalw+word[i];

}

	$(id+'_word').innerHTML = finalw;
}


function setCommissionAndPer(){
    
    var amt =  $("amount").value == "" ? 0 : $("amount").value;
    var comm =  parseFloat($("commission_per").value) * parseFloat( amt ) / 100 ;
 
    $("commission").value =   parseFloat(comm).toFixed(2)  ;
}
function checkConfirm(){
    if($('amount').value != $('p_amount').value){
        alert("Plz enter same amount !");return false;
    }
    <?php if($this->Session->read('Auth.User.id') == 1 || $_SESSION['Auth']['User']['group_id'] == ADMIN){ ?>
    if($('pass').value!='' && $('password').value==''){
        alert("Please Enter Pasword !");return false;
    }
    <?php } ?>

    showLoader2("loading_sym");
    $('tran_confirm').disable();
    var url = '/shops/amountTransfer';
    var params = $('confirmAmountTransferForm').serialize();
    var myAjax = new Ajax.Request(url, {method: 'post', parameters: params,
    onSuccess:function(transport)
                    {		
                            var html = transport.responseText;
                            $("innerDiv").update(html);
                    }
    });
}

 function getLastTrnfrd(object){
        
        <?php if($this->Session->read('Auth.User.group_id') != DISTRIBUTOR && $this->Session->read('Auth.User.group_id') != SUPER_DISTRIBUTOR) { ?>
        $("commission_per").value = 0;
        <?php } ?>
        var shop_id = object;
        var url = '/shops/lastTransferred';
    	var params = {'id' : shop_id};
    	$('lastTxns').innerHTML = "";
    	showLoader2("dist_sub");
    	var myAjax = new Ajax.Request(url, {method: 'post', parameters: params,
    	onSuccess:function(transport)
                    {
                            var html = transport.responseText;
                            var parsedJSON = eval('('+html+')');
                            <?php if($this->Session->read('Auth.User.group_id') != DISTRIBUTOR && $this->Session->read('Auth.User.group_id') != SUPER_DISTRIBUTOR) { ?>
                            $("commission").value = 1;
                            <?php } 
                                if($this->Session->read('Auth.User.group_id') == MASTER_DISTRIBUTOR) { ?>
                                    $("commission_per").value = parsedJSON.dist.comm == 1 ? 0 : parsedJSON.dist.margin;
                                    var parsedJSON = parsedJSON.rec;
                            <?php } ?>
                            $('dist_sub').innerHTML = "";
                            var text = '<div style="float:left; background:#bbff1f;height=100px; width:38%;">';
                            	
                            if(parsedJSON.length > 0){
                            	text += '<div class="appTitle" style="margin-top:20px;">Last Transferred</div>';
                            	
                            	text += '<table width="100%" cellspacing="0" cellpadding="0" border="0" class="ListTable" summary="Transactions">'+
                            		'<thead>'+
			          					'<tr class="noAltRow altRow">'+
				            				'<th style="width:80px;">Txn Id</th>'+
								            '<th style="width:80px;">Amount</th>'+
								            '<th style="width:80px;">Comm</th>'+
								            '<th style="width:80px;">Time</th>'+
                                            '<th style="width:80px;">Bank Id</th>'+
							          	'</tr>'+
			        				'</thead>'+
                    				'<tbody>';
                
	                            for(var i=0;i<parsedJSON.length;i++){
	                            	var arr = parsedJSON[i];
	                            	if(i%2 == 0)class1 = '';
	                    			else class1 = 'altRow';
	                    			text += '<tr class="'+class1+'">';
				            		text += '<td>'+arr.st1.id+'</td>';
				            		text += '<td>'+arr.st1.amount+'</td>';
				            		if(arr.st2.amount == null)comm = 0;
				            		else comm = arr.st2.amount;
				            		text += '<td>'+comm+'</td>';
	                            	text += '<td>'+arr.st1.timestamp+'</td>';
                                    text += '<td>'+arr.st1.note+'</td>';
	                            	text += '</tr>';
	                            }
	                           
	                           	text += '</tbody></table>';
                            }
                            else {
                            	text += 'No transfer in last 7 days';
                            }
                            text += '</div>';
                            $('lastTxns').innerHTML = text;
                    }
    	});
        
    }
    
    function typeChange() {
  
        $j('#shop1').val('');
        $j('#shop').val('');

        var projects = <?php echo json_encode($datavalue); ?>;
        $j('.autocomplete').autocomplete('option', 'source', projects);
    }
    
  
</script>