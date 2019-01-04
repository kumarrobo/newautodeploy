<div class="loginCont">
  <div>
     <div>
     	<ul class="box6Cont2" id="recentMessege">
     		<?php echo $this->element('dashboard_box',array('data' => $data,'upper'=>$upper));?>     		
     	</ul>
     	<?php if(count($data) == DASH_QRY_LMT) {?>
     	<ul class="box6Cont2" id="getMoreDashboard">     		
     		<li class="box6Cont2" style="background:none; border-top:1px solid #f3f2f2;border-bottom:1px solid #f3f2f2;">
     			<a href="javascript:void(0)" onclick="getmoreDashbord();">MORE...</a><span id="getmoreDashbordLoader"><span>
     		</li>
     	</ul> 
     	<?php } ?>    
    <div class="clearLeft"></div>
  </div>
</div>