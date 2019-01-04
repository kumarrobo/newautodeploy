<script>
function countChars(id){
	if($('transl')){
		var str = $('transl').value;
		$('left').innerHTML=str.length +" chars";
	}
}

function Ajaxupdate(cat_div,id){
	var url = '/groups/getEntryFields';
	new Ajax.Updater(cat_div, url, {
  			parameters: {catid: id},
  			evalScripts:true
		});		
}

</script>

	<div style="height:700px;">
	
Choose Category: <select id="Category"  onchange="Ajaxupdate('ocategory',this.options[this.selectedIndex].value)">
					<option  value="0"> Select </option>
					<?php foreach($required as $category)  {?>
						<option value="<?php echo $category;?>"><?php echo $categoryMapping[$category];?></option>
					<?php } ?>
				</select>
				
				<div style="padding-top:10px;" id="ocategory"></div>
				<div style="padding-top:10px;" id="data"></div>
	</div>