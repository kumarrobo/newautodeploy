<div class="loginCont" style="min-height:300px;">
  <div>
    <div class="leftFloat dashboardPack">
      <div class="catList">
        <ul id='innerul'>                       
          <li name='innerli'><?php echo $ajax->link( 
    				'Shop Locator', 
    				array('action' => 'locateUser'),
    				array('id' => 'locate', 'class' => 'sel', 'update' => 'innerDiv','onclick' => 'changeInnerTabClass(this);' ,'complete' => '$("locate").removeClassName("loader")')
					); ?> </li>
          <li name='innerli'><?php echo $ajax->link( 
    				'Retail Products', 
    				array('action' => 'retailProducts'),
    				array('id' => 'retailProducts', 'update' => 'innerDiv','onclick' => 'changeInnerTabClass(this);','complete' => '$("retailProducts").removeClassName("loader")' )
					); ?> </li>
		 <li name='innerli'><?php echo $ajax->link( 
    				'Business Partner Benefits', 
    				array('action' => 'disRetailBenefits'),
    				array('id' => 'disRetailBenefits', 'update' => 'innerDiv','onclick' => 'changeInnerTabClass(this);','complete' => '$("disRetailBenefits").removeClassName("loader")' )
					); ?> </li>
		 <li name='innerli'><?php echo $ajax->link( 
    				'Become a Business Partner', 
    				array('action' => 'becomeRetailer'),
    				array('id' => 'becomeRetailer', 'update' => 'innerDiv','onclick' => 'changeInnerTabClass(this);','complete' => '$("becomeRetailer").removeClassName("loader")' )
					); ?> </li>
					</ul>
      </div>
      <!-- Testimonial -->
      <div><img src="/img/talha.gif"/></div>
      <div style="display: none;">
      	<div style="position: relative;" class="color4 fntSz19">
      		<span style="top: 35px;" class="testimonial">"</span>PNR Alert helped me track my PNR and inturn I saved my precious time doing the thesis i am required to. Thanks SMS Tadka for this service.
      		<span style="bottom: -17px; position: absolute;" class="testimonial">"</span>
      	</div>
      	<div style="padding-top: 10px; text-align: right;">
      		<div class="color3 fntSz17 strng rightFloat">
      			<img width="60px" height="60px" style="float: left; margin-right: 5px;" src="/img/spacer.gif">
      			<div style="overflow: hidden; float: left;"><div>-&nbsp;Astha&nbsp;Gulatee</div><div>College&nbsp;Student</div></div>
      			<div class="clearLeft"></div>
      		</div>
      	</div>
      </div>
      <!-- Testimonial -->
    </div>
    
    <div style="margin-left:223px;min-height:300px;" id="innerDiv">
    <?php if($flag == 'bRetailer'){ 
    	 echo "<script>$('becomeRetailer').simulate('click');</script>";
     }elseif($flag == 'products'){ 
    	 echo "<script>$('retailProducts').simulate('click');</script>";
     }else{ 
      	echo "<script>$('locate').simulate('click');</script>";
     } ?>       
    </div>
    <br class="clearLeft" />
 </div>
 <?php if(!$_SESSION['Auth']['User']['id']){echo $this->element("horizontal_bar"); echo '<div class="catRow ">'; echo $this->element('categories'); echo '</div>';}?>
