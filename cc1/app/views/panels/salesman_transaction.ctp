<?php

echo $salesmanMobile;
if($salesmanMobile == 0)
$var='Salesman';
if(empty($salesResult))
echo "EMPTY";
else
echo "Not empty";
// print_r($salesResult);
?>


<table border="1" >
<th width="15%">Retailer Name</th>
<?php if($salesmanMobile==0)
echo "<th>".$var."</th>";
?>

<th width="10%">Type</th>
<th width="10%">Amount</th>


<th width="10%">Payment Mode</th>
<th width="10%">Flag</th>
<th width="20%">Date</th>

<?php

foreach($salesResult as $d)
{
if(is_null($d['r']['name']))
$retDetails=$d['r']['mobile'];
else
$retDetails=$d['r']['name'];

if($d['sst']['payment_type']==1)
$paymentType='Set-Up Fees';
if($d['sst']['payment_type']==2)
$paymentType='Top-Up';

if($d['sst']['payment_mode']==1)
$paymentMode='Cash';
if($d['sst']['payment_mode']==2)
$paymentMode='Cheque';
if($d['sst']['payment_mode']==3)
$paymentMode='NEFT';
if($d['sst']['payment_mode']==4)
$paymentMode='DD';

if($d['sst']['confirm_flag']==0)
$cnfFlag='PAID';
else
$cnfFlag='NOT PAID ';

echo "<tr>";
echo "<td>".$retDetails."</td>";
 if($salesmanMobile == 0)
echo "<td>".$d['sm']['name']."</td>";

echo "<td>".$paymentType."</td>";
echo "<td>".$d['st']['amount']."</td>";


echo "<td>".$paymentMode."</td>";
echo "<td>".$cnfFlag."</td>";
echo "<td>".$d['sst']['created']."</td>";
echo "</tr>";
}
?>

</table>

