<!DOCTYPE html>
<html>
<head>
	<title>Document Management</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/boot/css/normalize.css">
	<link rel="stylesheet" type="text/css" href="/boot/css/custom.css">
	<link rel="stylesheet" type="text/css" href="/boot/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="/boot/css/owl.carousel.min.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
        <link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">

        <script type="text/javascript" src="/boot/js/jquery-3.1.0.min.js"></script>
        <script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
	<script type="text/javascript" src="/boot/js/docmanagement.js"></script>

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/boot/js/owl.carousel.min.js"></script>
</head>
<body>
	<header>
		<div class="container-fluid">
			<div class="col-md-4 logo">
				<img src="/boot/images/logo.png">
			</div>
			<div class="col-md-3 pull-right details">
				<span class="name"><b><?php echo "Welcome ".$_SESSION['Auth']['User']['name'];?></b></span>
				<a href="/shops/logout" class="logout"><img src="/boot/images/logout.png" alt="Logout" title="Logout"></a>
				<!--<a href="javascript:;" class="home"><img src="/boot/images/home.png" alt="Home" title="Home"></a>-->
			</div>
		</div>
	</header>
	<div class="filter-panel">
            <form method="post" action="/docmanagement/getUserDocuments">
                <?php $messages=$this->Session->flash(); ?>
                <?php if(!empty($messages) && preg_match('/Error/',$messages)): ?>
                    <div class="alert alert-danger">
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                    <p><?php  echo $messages; ?></p>
                    </div>
                <?php endif; ?>
                <?php if(!empty($messages) && preg_match('/Success/',$messages)): ?>
                    <div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                    <p><?php  echo $messages; ?></p>
                    </div>
                <?php endif; ?>
		<div class="container-fluid">
<!--			<div class="col-md-3">
				<select class="form-control" name="dist_id">
				  <option value="">All Distributor</option>
                                  <?php foreach ($distributors as $dist){?>
                                  <option value="<?php echo $dist['id']?>" <?php  if($this->params['form']['dist_id']==$dist['id']): echo "selected";  endif; ?>><?php echo $dist['company'];?></option>
                                  <?php } ?>
				</select>
			</div>-->
			<div class="col-md-3">
				<div class="form-group">
				    <div class="input-group">

				      <div class="input-group-addon"><i class="fa fa-search" aria-hidden="true"></i></div>

                                      <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Search by Mobile No." value="<?php echo isset($this->params['form']['mobile'])?$this->params['form']['mobile']:"";?>">
				    </div>
				  </div>
				  <a href="/docmanagement/getActiveUsers" target="_blank">Active Users</a>
			</div>
			<?php /*<div class="col-md-2">
				<a href="javascript:;" class="verified"><i class="fa fa-check" aria-hidden="true"></i> Verified</a>
			</div>
			<div class="col-md-2">
				<a href="javascript:;" class="review"><i class="fa fa-hourglass-half" aria-hidden="true"></i> Review</a>
			</div> */?>
                    <button type="submit" class="btn btn-primary btn-sm" id="btnsearchpendingdocs">Search</button>
		</div>
            </form>
	</div>
	<div class="docs-list-main">
		<div class="container-fluid">
			<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
		    <?php /*?><!-- Copy From Here -->
		        <div class="panel panel-default pending-main">
		            <div class="panel-heading" role="tab" id="headingOne">
		                <h4 class="panel-title">
		                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
		                        <i class="more-less glyphicon glyphicon-menu-down"></i>
		                        Pending
		                    </a>
		                </h4>
		            </div>
		            <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
		                <div class="panel-body">
                                    <?php
                                     foreach ($user_docs as $key=>$docs)
                                     {
//                                         echo "<pre>";
//                                         print_r($docs);
//                                         echo "</pre>";
//                                         exit;
                                        $user_services=array();
                                        $service_ids=explode(",",$docs[0]['service_id']);
                                        foreach ($service_ids as $id)
                                        {
                                            $user_services[]=$services[$id];
                                        }
                                        $user_services= implode(",", $user_services);
                                        $urls=array_map(function($value){
                                                                                                return DOCUMENT_URL.$value;},
                                                                                                explode(',',$docs[0]['description'])
                                                                                                );
//                                    {
//                                        if($docs['lsh']['pay1_status']==0){
                                     ?>
		                    <div class="profile-detail">
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <span><a href="javascript:;" class="profile-link"><?php echo $docs['r']['name'];?></a></span>
                                            </div>
<!--		                    	<a href="javascript:;" class="download">Download All Docs</a>-->
                                        <!--<input type="hidden" id="document_urls" value='<?php echo  json_encode($urls); ?>'>-->
                                            <div class="pull-right">
                                                <form method="post" action="/docmanagement/downloadDocs">
                                                    <input type="hidden" id="user_id" name="user_id" value="<?php echo $docs['luh']['user_id'];?>">
                                                    <input type="hidden" id="document_urls" name="document_urls" value='<?php echo $docs[0]['description']; ?>'>
                                                    <!--<button id="" type="submit" class="btn btn-primary btn-sm">Download All Docs</button>-->
                                                </form>
                                            </div>
                                        </div>
		                    	<div class="col-md-12">
		                    		<div class="col-md-3">
		                    			<span class="profile-number"><?php echo $docs['r']['mobile'];?></span>
		                    			<span class="shop-number"><?php echo $docs['r']['shopname'];?></span>
		                    			<span class="product-list"><?php echo $user_services;?></span>
		                    			<span class="date">23/05/2017</span>
                                                        <a href="/docmanagement/userProfile/<?php echo $docs['luh']['user_id']; ?>" target="_blank">Accept/Reject Documents</a>
                                                </div>
                                            <input type="hidden" id="user_id" value="<?php echo $docs['lsh']['user_id'];?>">
		                    		<?php /*<div class="col-md-8">
		                    			<div id="pending_<?php echo $key;?>" class="carousel slide" data-ride="carousel">

											  <!-- Wrapper for slides -->
											  <div class="carousel-inner" role="listbox">
                                                                                              <div class="item active col-md-12">
                                                                                              <?php
                                                                                                $urls=array_map(function($value){
                                                                                                return DOCUMENT_URL.$value;},
                                                                                                explode(',',$docs[0]['description'])
                                                                                                );
                                                                                                $i=1;
                                                                                                foreach ($urls as $url)   {
                                                                                                    if($i%3==0)
                                                                                                        {
                                                                                                            echo '</div><div class="item">';
                                                                                                        }
                                                                                                ?>

											    	<div class="col-md-3 closed-main">
											    		<img id="myImg" src="<?php echo $url; ?>" alt="your image" />
                                                                                                        <!--<input type='file' name="myImage" accept="image/*" multiple="multiple" />-->
                                                                                                </div>
                                                                                                <?php $i++;}?>
                                                                                              </div>
                                                                                              <!--<div class="item col-md-12">-->
                                                                                                 <?php
//                                                                                                        $urls=array_map(function($value){
//                                                                                                        return DOCUMENT_URL.$value;},
//                                                                                                        explode(',',$docs[0]['description'])
//                                                                                                    );
//                                                                                                    foreach ($urls as $url)   {
                                                                                                ?>

											    	<!--<div class="col-md-3 closed-main">-->
											    		<!--<img id="myImg" src="<?php echo $url; ?>" alt="your image" />-->
												     	<!--<input type='file' name="myImage" accept="image/*" />-->
                                                                                                <!--</div>-->
                                                                                                <?php // }?>
<!--											     	<div class="col-md-3 closed-main">
											    		<img id="myImg" src="/boot/images/no-photo.png" alt="your image" />
												     	<input type='file' name="myImage" accept="image/*" />
												     </div>
												     <div class="col-md-3 pending-main">
											    		<img id="myImg" src="/boot/images/no-photo.png" alt="your image" />
												     	<input type='file' name="myImage" accept="image/*" />
												     </div>
												     <div class="col-md-3 closed-main">
											    		<img id="myImg" src="/boot/images/no-photo.png" alt="your image" />
												     	<input type='file' name="myImage" accept="image/*" />
												     </div>-->
											    </div>
											  </div>
											  <!-- Controls -->
												  <a class="left carousel-control" href="#pending_<?php echo $key;?>" role="button" data-slide="prev">
												    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
												    <span class="sr-only">Previous</span>
												  </a>
												  <a class="right carousel-control" href="#pending_<?php echo $key;?>" role="button" data-slide="next">
												    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
												    <span class="sr-only">Next</span>
												  </a>
										*/?></div>


		                    		</div>
		                    	</div>
		                    <!--</div>-->
                                        <?php // }

                                  /*  }?>
		                </div>
		            </div>
		        </div>
		        <!-- Till Here -->

		        <!-- Copy From Here -->
		        <div class="panel panel-default closed-main">
		            <div class="panel-heading" role="tab" id="headingTwo">
		                <h4 class="panel-title">
		                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="true" aria-controls="collapseOne">
		                        <i class="more-less glyphicon glyphicon-menu-down"></i>
		                        Closed
		                    </a>
		                </h4>
		            </div>
		            <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
		                <div class="panel-body">
                                    <?php
                                     foreach ($user_docs as $docs)
                                    {
                                        if($docs['lsh']['pay1_status']!=0){
//     echo "<pre>";
//print_r($docs);
//echo "</pre>";
//     exit;
                                        ?>
		                    <div class="profile-detail">
		                    	<a href="javascript:;" class="profile-link"><?php echo $docs['r']['name']?></a>
		                    	<a href="javascript:;" class="download">Download All Docs</a>
		                    	<div class="col-md-12">
		                    		<div class="col-md-3">
		                    			<span class="profile-number"><?php echo $docs['r']['mobile'];?></span>
		                    			<span class="shop-number"><?php echo $docs['r']['shopname'];?></span>
		                    			<span class="product-list"><?php echo $docs[0]['service_name'];?></span>
		                    			<span class="date">23/05/2017</span>
		                    			<a href="javascript:;" class="accept/reject">Accept/Reject Documents</a>
		                    		</div>
                                            <div class="col-md-8">
		                    			<div id="closing_<?php echo $key;?>" class="carousel slide" data-ride="carousel">

											  <!-- Wrapper for slides -->
											  <div class="carousel-inner" role="listbox">
                                                                                              <div class="item active col-md-12">
                                                                                              <?php
                                                                                                $urls=array_map(function($value){
                                                                                                return DOCUMENT_URL.$value;},
                                                                                                explode(',',$docs[0]['description'])
                                                                                                );
                                                                                                $i=1;
                                                                                                foreach ($urls as $url)   {
                                                                                                    if($i%3==0)
                                                                                                        {
                                                                                                            echo '</div><div class="item">';
                                                                                                        }
                                                                                                ?>

											    	<div class="col-md-3 closed-main">
											    		<img id="myImg" src="<?php echo $url; ?>" alt="your image" />
												     	<input type='file' name="myImage" accept="image/*" />
                                                                                                </div>
                                                                                                <?php $i++;}?>
                                                                                              </div>
                                                                                              <!--<div class="item col-md-12">-->
                                                                                                 <?php
//                                                                                                        $urls=array_map(function($value){
//                                                                                                        return DOCUMENT_URL.$value;},
//                                                                                                        explode(',',$docs[0]['description'])
//                                                                                                    );
//                                                                                                    foreach ($urls as $url)   {
                                                                                                ?>

											    	<!--<div class="col-md-3 closed-main">-->
											    		<!--<img id="myImg" src="<?php echo $url; ?>" alt="your image" />-->
												     	<!--<input type='file' name="myImage" accept="image/*" />-->
                                                                                                <!--</div>-->
                                                                                                <?php // }?>
<!--											     	<div class="col-md-3 closed-main">
											    		<img id="myImg" src="/boot/images/no-photo.png" alt="your image" />
												     	<input type='file' name="myImage" accept="image/*" />
												     </div>
												     <div class="col-md-3 pending-main">
											    		<img id="myImg" src="/boot/images/no-photo.png" alt="your image" />
												     	<input type='file' name="myImage" accept="image/*" />
												     </div>
												     <div class="col-md-3 closed-main">
											    		<img id="myImg" src="/boot/images/no-photo.png" alt="your image" />
												     	<input type='file' name="myImage" accept="image/*" />
												     </div>-->
											    </div>
											  </div>
											  <!-- Controls -->
												  <a class="left carousel-control" href="#closing_<?php echo $key;?>" role="button" data-slide="prev">
												    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
												    <span class="sr-only">Previous</span>
												  </a>
												  <a class="right carousel-control" href="#closing_<?php echo $key;?>" role="button" data-slide="next">
												    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
												    <span class="sr-only">Next</span>
												  </a>
										</div>
		                    		</div>
<!--		                    		<div class="col-md-8">
		                    			<div id="closed" class="carousel slide" data-ride="carousel">

											   Wrapper for slides
											  <div class="carousel-inner" role="listbox">
											    <div class="item active col-md-12">
											    	<div class="col-md-3 closed-main">
											    		<img id="myImg" src="/boot/images/no-photo.png" alt="your image" />
												     	<input type='file' name="myImage" accept="image/*" />
												     </div>
												     <div class="col-md-3 pending-main">
												      	<img id="myImg" src="/boot/images/no-photo.png" alt="your image" />
												     	<input type='file' name="myImage" accept="image/*" />
												     </div>
												     <div class="col-md-3 closed-main">
												      	<img id="myImg" src="/boot/images/no-photo.png" alt="your image" />
												     	<input type='file' name="myImage" accept="image/*" />
												     </div>
											    </div>
											    <div class="item col-md-12">
											     	<div class="col-md-3 closed-main">
											    		<img id="myImg" src="/boot/images/no-photo.png" alt="your image" />
												     	<input type='file' name="myImage" accept="image/*" />
												     </div>
												     <div class="col-md-3 pending-main">
											    		<img id="myImg" src="/boot/images/no-photo.png" alt="your image" />
												     	<input type='file' name="myImage" accept="image/*" />
												     </div>
												     <div class="col-md-3 closed-main">
											    		<img id="myImg" src="/boot/images/no-photo.png" alt="your image" />
												     	<input type='file' name="myImage" accept="image/*" />
												     </div>
											    </div>
											  </div>
											   Controls
												  <a class="left carousel-control" href="#closed" role="button" data-slide="prev">
												    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
												    <span class="sr-only">Previous</span>
												  </a>
												  <a class="right carousel-control" href="#closed" role="button" data-slide="next">
												    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
												    <span class="sr-only">Next</span>
												  </a>
										</div>
		                    		</div>-->
		                    	</div>
		                    </div>
                                    <?php }

                                    }?>
		                </div>
		            </div>
		        </div>
                            <?php */?>
		        <!-- Till Here -->


    </div><!-- panel-group -->
		</div>
	</div>



	<script type="text/javascript">
	 function toggleIcon(e) {
    $(e.target)
        .prev('.panel-heading')
        .find(".more-less")
        .toggleClass('glyphicon-menu-down glyphicon-menu-up');
}
$('.panel-group').on('hidden.bs.collapse', toggleIcon);
$('.panel-group').on('shown.bs.collapse', toggleIcon);


$('.owl-carousel').owlCarousel({
    loop:true,
    margin:10,
    navText:["<i class='fa fa-arrow-left' aria-hidden='true'></i>","<i class='fa fa-arrow-right' aria-hidden='true'></i>"],
    nav:true,
    responsive:{
        0:{
            items:1
        },
        600:{
            items:3
        },
        1000:{
            items:5
        }
    }
});

$('.carousel').carousel({
  interval: 9999999999
})
	</script>
	<script type="text/javascript">

		$(function () {
    $(":file").change(function () {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = imageIsLoaded;
            reader.readAsDataURL(this.files[0]);
        }
    });
});

function imageIsLoaded(e) {
    $('#myImg').attr('src', e.target.result);
};
	</script>
</body>
</html>