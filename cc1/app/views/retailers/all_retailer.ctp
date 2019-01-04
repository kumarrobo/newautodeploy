<?php foreach($data as $retailer) {?>
<div style="border:1px solid #444;margin-bottom:2px; padding:4px; padding-bottom:0px;">
  <table>
    <tbody><tr>
      <td style="vertical-align:top;"><table cellspacing="0" cellpadding="0" border="1" align="left">
          <caption class="header">
          Products
          </caption>
          <tbody>
            <tr align="left">
              <th width="25%">Name</th>
              <th width="12%">Total</th>
              <th width="13%">Sold</th>
            </tr>
            <?php foreach($retailer['products'] as $product){ ?>
			<tr align="left">
				<td> <?php echo $product['products']['name'];?> </td>
				<td> <?php echo $product['0']['total'];?></td>
				<td> <?php echo $product['0']['sold'];?></td>
			</tr>
			<?php } ?>
          </tbody>
        </table>
        <div>
        Total Payments: <?php echo $retailer['payments']['0']['0']['amounts'];?>
        </div>
        </td>
      <td><div style="border-bottom: 1px solid #222222;padding-bottom:4px;">Name: <span class="strng"><?php echo $retailer['retailer']['Retailer']['name']; ?></span> | Mobile: <span class="strng"><?php echo $retailer['retailer']['Retailer']['mobile']; ?></span> | Register on:<span class="strng"><?php echo $retailer['retailer']['Retailer']['created']; ?></span></div>
        <div style="border-bottom: 1px solid #222222;padding-bottom:4px;">Address: <span class="strng"><?php echo $retailer['retailer']['Retailer']['shopname'] . ", " . $retailer['retailer']['Retailer']['address']; ?></span></div>
        <div id="commentBox">
          <?php
			foreach($retailer['comments'] as $comment){ 
				echo $this->element('commentElement',array('comment' => $comment)); 
			}
		?>
        </div></td>
    </tr>
  </tbody></table>
  <div style="border-bottom: 1px solid #222222;padding-bottom:4px;"> </div>
</div>
<?php } ?>