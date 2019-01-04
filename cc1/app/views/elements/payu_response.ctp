<form id="form" method="post" action="<?php echo RETPANEL_URL; ?>pay/response">
<input type="hidden" id="response" name="response" value="" />
</form>
<div id="response_data" style='display:none;'>
<?php 
if ($status == "success") {
                $successHtml = "<div style='width:auto;height:auto;float:left;border-radius: 20px;margin:10px;background-color:#c5bde2;font-family:Encode Sans Normal;font-size:16px'><div style='font-family:Encode Sans Normal;font-weight:100;background-color:#efefef;text-align:center;margin:10px;padding:16px;border: 1px solid #503c97;border-radius: 8px'><div style='float:left;'><img src=\"".DISTPANEL_URL."img/success.png\" width=\"32\" height=\"32\" style='padding:0px;margin:0' /></div>";
                $successHtml.="<span style='color:#006600;width:auto;height:auto;font-size:18px;'>Your transaction has been successfully Processed!!!</span></div><div style='font-family:Encode Sans Normal;font-size:20px;font-weight:400;float:left;background-color:#e2def2;margin:10px;padding:10px;border: 1px solid #e1dddd;border-radius: 8px;'><div align=\"center\">Online Payment Receipt</div>";
                $successHtml.="<div style='width:auto;height:auto;float:left;padding:10px;border-radius: 16px;margin:10px;font-family:Encode Sans Normal;font-size:12px'><div style='padding:10px;width:auto;height:auto;border-radius: 8px;margin:10px;'></div><div style='padding:10px;width:auto;height:auto;border-radius: 8px;margin:10px;font-family:Encode Sans Normal;font-size:15px;'>Transaction Id:" . $response_data['txnid'] . "</div><div style='padding:10px;width:auto;height:auto;border-radius: 8px;margin:10px;font-family:Encode Sans Normal;font-size:15px;'>Transaction Amount :" . $transData[0]['shop_transactions']['amount'] . "</div><div style='padding:10px;width:auto;height:auto;border-radius: 8px;margin:10px'></div>";
                $successHtml.="</div><div style='width:auto;height:auto;float:left;padding:10px;border-radius: 16px;margin:10px;font-family:Encode Sans Normal;font-size:12px'><div style='padding:10px;width:auto;height:auto;border-radius: 8px;margin:10px'></div><div style='padding:10px;width:auto;height:auto;border-radius: 8px;margin:10px;font-family:Encode Sans Normal;font-size:15px;'>Closing Amount" . $transData[0]['retailers']['balance'] . "</div><div style='padding:10px;width:auto;height:auto;border-radius: 8px;margin:10px;font-family:Encode Sans Normal;font-size:15px;  '>Mobile Number :" . $transData[0]['retailers']['mobile'] . "</div>";
                $successHtml.="</div></div></div>";
                
                echo $successHtml;
            } else {
                $errorHtml = "<div style='width:auto;height:auto;float:left;border-radius: 20px;margin:10px;background-color:#c5bde2;font-family:Encode Sans Normal;font-size:16px'><div style='font-family:Encode Sans Normal;font-weight:100;background-color:#efefef;text-align:center;margin:10px;padding:16px;border: 1px solid #503c97;border-radius: 8px'><div style='float:left;'><img src=\"".DISTPANEL_URL."img/error.png\" width=\"32\" height=\"32\" style='padding:0px;margin:0' /></div>";
                $errorHtml.="<span style='color:#FF0000;width:auto;height:auto;font-size:18px;'>Your transaction has failed!!!</span></div><div style='font-family:Encode Sans Normal;font-size:20px;font-weight:400;float:left;background-color:#e2def2;margin:10px;padding:10px;border: 1px solid #e1dddd;border-radius: 8px;'><div align=\"center\">Online Payment Receipt</div>";
                $errorHtml.="<div style='width:auto;height:auto;float:left;padding:10px;border-radius: 16px;margin:10px;font-family:Encode Sans Normal;font-size:12px'><div style='padding:10px;width:auto;height:auto;border-radius: 8px;margin:10px;'></div><div style='padding:10px;width:auto;height:auto;border-radius: 8px;margin:10px;font-family:Encode Sans Normal;font-size:15px;'>Transaction Id:" . $response_data['txnid'] . "</div><div style='padding:10px;width:auto;height:auto;border-radius: 8px;margin:10px;font-family:Encode Sans Normal;font-size:15px;'>Transaction Amount :" . $transData[0]['shop_transactions']['amount'] . "</div><div style='padding:10px;width:auto;height:auto;border-radius: 8px;margin:10px'></div>";
                $errorHtml.="</div><div style='width:auto;height:auto;float:left;padding:10px;border-radius: 16px;margin:10px;font-family:Encode Sans Normal;font-size:12px'><div style='padding:10px;width:auto;height:auto;border-radius: 8px;margin:10px'></div><div style='padding:10px;width:auto;height:auto;border-radius: 8px;margin:10px;font-family:Encode Sans Normal;font-size:15px;'>Closing Amount:" . $transData[0]['retailers']['balance'] . "</div><div style='padding:10px;width:auto;height:auto;border-radius: 8px;margin:10px;font-family:Encode Sans Normal;font-size:15px;'>Mobile Number :" . $transData[0]['retailers']['mobile'] . "</div>";
                $errorHtml.="</div></div></div>";
                echo $errorHtml;
            } 
?>
</div>
<h2>Redirecting back to <?php echo RETPANEL_URL; ?></h2>
<script>
	document.getElementById("response").value = document.getElementById("response_data").innerHTML;
	document.getElementById("form").submit();
</script>