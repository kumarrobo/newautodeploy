<script>
function countChars(){
	var str = $('transl').value;
	$('left').innerHTML= (str.length + <?php echo ADSPACE; ?>) + " chars";
	/*if(DND_FLAG) {
		$('left').innerHTML= (str.length + 21) +" chars";
	} else {
		$('left').innerHTML= (str.length + 10) +" chars"; //TRAI changes
	}*/
}

function Ajaxupdate(cat_div,id){
	if(id != 0){
		var url = '/groups/getPackages';
		new Ajax.Updater(cat_div, url, {
	  			parameters: {catid: id},
	  			evalScripts:true,
	  			onComplete: function(response){
					$('data').innerHTML = '';
				}
			});
	}
}

function getPackageInfo(pack_id,cat_id){
	var url = '/groups/getPackageInfo';
	new Ajax.Updater('data', url, {
  			parameters: {packid: pack_id,catid: cat_id},
  			evalScripts:true
		});
}

function nextMessage(data_id,pck_id,cat_id,table){
	var url = '/groups/nextMessage';
	var rand   = Math.random(9999);
	var pars   = "dataid="+data_id+"&table="+table+"&rand="+rand;
	
	var myAjax = new Ajax.Request(url, {method: 'post', parameters: pars,
					onSuccess:function(transport)
					{ 
						getPackageInfo(pck_id,cat_id);
					}
				});
}

function changeFlag(pack_id,flag){
	if(flag == 0){
		$('pack_'+pack_id).removeClassName('taggLinkBG1');
	}
	else if(flag == 1){
		$('pack_'+pack_id).addClassName('taggLinkBG1');
	}
}

function dataValidate(name,maxlength){
	if( (name == null) || (name.length == 0)){
		alert("Enter some data");
		return false;
	}
	if(maxlength != -1 && name.length > maxlength){
		alert("Message should contain maximum of " + maxlength + " characters");
		return false;
	}	
	return true;	
}

function read(obj,id){
	var readflag = 0;
	if(obj.checked){
		readflag = 1;	
	}
	
	var url = '/rss/readUnreadFeed';
	new Ajax.Updater('', url, {
  			parameters: {feedid: id,read: readflag},
  			evalScripts:true,
  			onComplete: function(response){
				if(obj.checked){
					$("div_"+id).addClassName("read");
				}
				else {
					$("div_"+id).removeClassName("read");
				}
			}
		});
}

function addFeeds(){
	var arr = $$('div#feeds input');
	var html = '';
	for(var i =0;i<arr.length;i++){
		if(arr[i].type == "checkbox" && arr[i].checked){
			html += "\n#" + $('data_'+arr[i].value).innerHTML;
		}
	}
	$('transl').value = $('transl').value +  html;
	countChars();
}

function removeFeeds(pack_id){
	var url = '/rss/refreshFeeds';
	new Ajax.Updater('feeds', url, {
  			parameters: {packid: pack_id},
  			evalScripts:true
		});
}

function showHide(id){
	if($('desc_' + id).style.display=="none"){
		$('desc_' + id).show();
	}
	else {
		$('desc_' + id).hide();
	}
}

</script>

	<div style="min-height:700px;">
	
		Choose Category: <select id="Category" onchange="Ajaxupdate('ocategory',this.options[this.selectedIndex].id)">
							<option id="0" value="0"> Select </option>
							<?php foreach($categories as $category) {?>
								<option id="<?php echo $category['Category']['id'];?>" value="<?php echo $category['Category']['id'];?>"><?php echo $category['Category']['name']?></option>
							<?php } ?>
						</select>
						
						<div style="padding-top:10px;" id="ocategory"></div>
						<div style="padding-top:10px;" id="data"></div>
						
		</div>