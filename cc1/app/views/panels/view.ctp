<table>
	<tr>
		<td colspan="2">PANEL</td>
	</tr>
    <?php if(in_array($_SESSION['Auth']['User']['group_id'],array(ADMIN,CUSTCARE)) || $_SESSION['Auth']['User']['id'] == 1) { ?>	
	<tr>
		<td></td><td><a href="/panels/retInfo" target="_blank">Retailer info</a></td>
	</tr>
	<tr>
		<td></td><td><a href="/panels/userInfo" target="_blank">User Info</a></td>
	</tr>
	<tr>
		<td></td><td><a href="/panels/transaction" target="_blank">Transactions</a></td>
	</tr>
	<tr>
		<td></td><td><a href="/panels/tranRange" target="_blank">Transactions from-to</a></td>
	</tr>
<?php } ?>
<?php if(in_array($_SESSION['Auth']['User']['group_id'],array(ADMIN,BACKENDJR,BACKENDSR)) || $_SESSION['Auth']['User']['id'] == 1) { ?>
	<tr>
		<td></td><td><a href="/panels/prodVendor" target="_blank">Provider switching</a></td>
	</tr>
<?php } ?>
 <?php if(in_array($_SESSION['Auth']['User']['group_id'],array(ADMIN)) || $_SESSION['Auth']['User']['id'] == 1){ ?>
	<tr>
		<td></td><td><a href="/recharges/ossTest" target="_blank">OSS Recharges</a></td>
	</tr>
  
	<tr>
		<td></td><td><a href="/panels/tranReversal" target="_blank">Complaints</a></td>
	</tr>
<?php } ?>
<?php if(in_array($_SESSION['Auth']['User']['group_id'],array(ADMIN,DISTRIBUTOR_SUPPORT)) || $_SESSION['Auth']['User']['id'] == 1){ ?>
	<tr>
		<td></td><td><a href="/panels/salesmanReport" target="_blank">Salesman Report</a></td>
	</tr>

	<tr>
		<td></td><td><a href="/panels/retColl" target="_blank">Retailer Collection</a></td>
	</tr>
 <?php } ?>
</table>