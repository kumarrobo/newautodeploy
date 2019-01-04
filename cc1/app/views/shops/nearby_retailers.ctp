<?php 
$tableHtml = '<table border=1 cellspacing=0 cellpadding=0><tr>
                     <td colspan="12"><h3>Nearby Retailers</h3></td>                    
                     </tr><tr><th>Name</th><th>Shop Name</th><th>Number</th><th>Address</th></tr>';

foreach ($nearbyRetailers as $key => $retailer) {
	$tableHtml .= "<tr><td>" . $retailer['retailer_name'] . "</td><td>" . $retailer['shop_name'] . "</td><td>" . $retailer['retailer_mobile'] . "</td><td>" . $retailer['retailer_address'] . "</td></tr>";
}
$tableHtml .= "</table>";
echo $tableHtml;



?>