<style>
.tabsDropDown ul
{
	position:absolute;	  	
  	list-style:none;
	width:200px;border: 1px solid #2765B2;
	border-top: 0px;  	
}

.tabsDropDown ul li {
	float:none;
	background:#ececec;color:#4e79b0;padding:0px 0px;margin:0px;	
}

.tabsDropDown ul li.divider {			
	padding:0px;
	font-size:0pt;
	line-height:0px;
}

.tabsDropDown ul li.divider div
{
	border-top:1px solid #DEDEDE;
	height:0px;
	font-size:0pt;margin: 0 10px;	
}

.tabsDropDown ul li a {
	background:none;
	color:#222222;
	padding:5px 10px;
	border:0px;
}

.tabsDropDown ul li a:hover
{	
	background:#1854a0 !important;
	color:#ffffff !important;
	padding:5px 10px;
	border:0px;
}
</style>
<script>
function tagUpdate(val,id){
	var url = '/tags/findTag';
	
	var tags = new Array();
	tags = val.split(',');
	var tag = tags[tags.length - 1];
	
	new Ajax.Request(url, {
  		method: 'post',
  		parameters: 'tag='+tag,
  		onSuccess: function(transport) {
    		var tags = $(id);
    		var data = transport.responseText;
    		$('tags').innerHTML = data;
    		//alert(data);
}});

	
}
</script>

<?php
echo $form->create('Tag', array('action' => 'submitTags'));
?>

<textarea onkeyup="tagUpdate(this.value,this.id);" id="tagInput" name="data[Tag][names]" 
style="height: 20px; width: 302px; line-height: 1.5em; font-family: Arial,Helvetica,sans-serif; font-size: 14px; direction: ltr;" autocomplete="off"></textarea>
<div id="tags" class="tabsDropDown"> </div>
<input type="submit" value="Add">

<?php echo $form->end(); ?>