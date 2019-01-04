<!DOCTYPE html>
<html>
<head>
	<title>Document Management</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/boot/css/normalize.css">
	<link rel="stylesheet" type="text/css" href="/boot/css/custom.css">
	<link rel="stylesheet" type="text/css" href="/boot/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="/boot/css/owl.carousel.min.css">
	<link rel="stylesheet" type="text/css" href="/boot/css/magnific-popup.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
        <link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
	<script type="text/javascript" src="/boot/js/jquery-3.1.0.min.js"></script>
        <script type="text/javascript" src="/boot/js/docmanagement.js"></script>
        <script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>

	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="/boot/js/owl.carousel.min.js"></script>
    <style>
        #umap, #vmap {
            height:300px;
        }
        .top-left {
            position: absolute;
            top: 0px;
        }
        .top-left0 {
            position: absolute;
            top: 30px;
        }
        figcaption {
            width: 120px;
        }
        img {
            max-width: 220px;
        }
        .rotate90 {
            -ms-transform: rotate(90deg); /* IE 9 */
            -webkit-transform: rotate(90deg); /* Chrome, Safari, Opera */
            transform: rotate(90deg);
            transform-origin: 50% 50%;
        }
        .rotate180 {
            -ms-transform: rotate(180deg); /* IE 9 */
            -webkit-transform: rotate(180deg); /* Chrome, Safari, Opera */
            transform: rotate(180deg);
            transform-origin: 50% 50%;
        }
        .rotate270 {
            -ms-transform: rotate(270deg); /* IE 9 */
            -webkit-transform: rotate(270deg); /* Chrome, Safari, Opera */
            transform: rotate(270deg);
            transform-origin: 50% 50%;
        }
        .rotate0 {
            -ms-transform: rotate(0deg); /* IE 9 */
            -webkit-transform: rotate(0deg); /* Chrome, Safari, Opera */
            transform: rotate(0deg);
            transform-origin: 50% 50%;
        }
        img.full-size {
            max-width: none;
            max-height: 500px;
        }
        .vl {
            /*border-right: 1.5px solid black;*/
            border: 0.1px solid black;
            /*height: 500px;*/
        }
        .hl {
            border-bottom: 0.1px solid black;
            /*height: 500px;*/
        }
/*        .row-container {
    display: table;  Make the container element behave like a table 
    width: 100%;  Set full-width to expand the whole page 
    border: 0.1px solid black;
}
.col {
    display: table-cell;  Make elements inside the container behave like table cells 
    
}*/
        .row-container {
            display: flex;
}
.flex-col {
    /*flex: 1;  additionally, equal width */
  
  padding: 1em;
  border: 0.1px solid;
}
    </style>

</head>
<body>
	<header>
		<div class="container-fluid">
			<div class="col-md-4 logo">
				<a href="/"><img src="/boot/images/logo.png"></a>
			</div>
			<div class="col-md-3 pull-right details">
				<span class="name"><b>Welcome, <?php echo $_SESSION['Auth']['User']['name'];?></b></span>
				<a href="/shops/logout" class="logout"><img src="/boot/images/logout.png" alt="Logout" title="Logout"></a>
				<!--<a href="javascript:;" class="home"><img src="/boot/images/home.png" alt="Home" title="Home"></a>-->
			</div>
		</div>
	</header>
	<section>
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
            <div class="filter-panel">
            <!--<section>-->
                <form method="get" action="/docmanagement/getUserInformation">
		<!--<div class="container-fluid">-->
			<div class="col-md-3">
				<div class="form-group">
				    <div class="input-group">

				      <div class="input-group-addon"><i class="fa fa-search" aria-hidden="true"></i></div>

                                      <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Search by Mobile No." value="<?php echo isset($this->params['url']['mobile'])?$this->params['url']['mobile']:"";?>">
				    </div>
				  </div>
				  <a href="/docmanagement/getActiveUsers" target="_blank">Active Users</a><br>
				  <a href="/docmanagement/getUserSectionReport" target="_blank">User Section Report</a><br>
				  <a href="/docmanagement/sectionStatusSummaryReport" target="_blank">Summary Report</a>
			</div>
                    <button type="submit" class="btn btn-primary btn-sm" id="btnsearchpendingdocs">Search</button>
		<!--</div>-->
            </form>
            </div>
            </section>
	<!--</div>-->
            <?php if(!empty($documents)) { ?>
        <section>
		<div class="customer-details">
                    <?php if(isset($profileData['ret_info'])){?>
                        <span class="profile-number"><?php echo $profileData['ret_info']['ret_mobile'];?></span>
                        <span class="distributor-company"><?php echo $profileData['ret_info']['dist_company'];?></span>
                        <span class="shop-number"><?php echo $profileData['ret_info']['ret_shop_name'];?></span>
                        <span class="product-list"><?php echo $profileData['services'];?></span>
                    <?php }
                    elseif(isset($profileData['dist_info'])){?>
                        <span class="distributor-name"><?php echo $profileData['dist_info']['dist_name'];?></span>
                        <span class="distributor-company"><?php echo $profileData['dist_info']['dist_company'];?></span>
                        <span class="product-list"><?php echo $profileData['services'];?></span>
                    <?php }?>
		</div>
	</section>
<?php foreach ($documents as $section_id=>$doc){?>
	<section class="pan-main">
<!--		<div class="detail-head col-md-12">
                    
                    
		</div>-->
                    <h3>
                                <?php echo $sections[$section_id]['name'];?>
                            </h3><hr>
		<div class="row-container">

                    
			<div class="col-md-4 flex-col">
                            <b><u>Textual Info</u></b><hr>
                                     <?php
                                     if(isset($doc['textual']) && count($doc['textual'])){?>
                                        <div id="textualinfoForm_<?php echo $section_id; ?>">
                                            <input type="checkbox" name="checkall_labels" value="<?php echo $section_id; ?>"/> Select All
                                        <?php foreach ($doc['textual'] as $id => $val){ ?>
                                          <div class="form-group">
                                            <label for="<?php echo $id; ?>"><?php echo $val['label']; ?></label>
                                            <?php                                             
                                            if(isset($val['input_type']) && ($val['input_type'] == 'dropdown') ){ 
                                                ?>
                                                <br>
                                                <div class="row">
                                                <div class="col-md-1">
                                                    <!--<input type="checkbox" id="chk_<?php echo $id.'_'.$section_id; ?>" name="chk_<?php echo $id.'_'.$section_id; ?>">-->
                                                    <input type="checkbox" name="label_ids[]" class="chk_label" value="<?php echo $id; ?>">
                                                </div>
                                                <div class="col-md-8">
                                                    
                                                <select id="label_id_<?php echo $id.'_'.$section_id; ?>" name="label_id_<?php echo $id.'_'.$section_id; ?>" >
                                                    <?php

                                                        foreach($val['default_values'] as $value => $label) {
                                                            $selected = '';
                                                            if($value == $val['curr_desc']){
                                                                $selected = 'selected';
                                                            }
                                                            echo '<option value="'.$value.'" '.$selected.'>'.$label.'</option>';
                                                        }
                                                    ?>
                                                </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <div id="reject_docs_div_<?php echo $id.'_'.$section_id; ?>">
                                                    <?php if(!empty($val['prev_desc'])) { ?>
                                                    <a id="rejectdocs_<?php echo $id; ?>" class="reject" onclick="rejectDocs('<?php echo $id;?>','<?php echo $profileData['user_id'];?>',2,'<?php echo $section_id; ?>')">
                                                    <i style="cursor: pointer;" title="click to reject docs" class="fa fa-times" aria-hidden="true"></i>
                                                    </a>
                                                    <?php } ?>
                                                    </div>
<!--                                                    <div id="accept_docs_div_<?php echo $id.'_'.$section_id; ?>">
                                                    <a id="acceptdocs_<?php echo $id;?>" class="accept" onclick="approveDocs('<?php echo $id;?>','<?php echo $profileData['user_id'];?>',2,'<?php echo $section_id; ?>')">
                                                    <i style="cursor: pointer;" title="click to aprove docs" class="fa fa-check" aria-hidden="true"></i>
                                                    </a>   
                                                    </div>-->
                                                </div>
                                                </div>
                                                <?php if(!empty($val['prev_desc'])) { ?>
                                                <div id="last_ver_label_id_<?php echo $id.'_'.$section_id;?>">
                                                <label for="last_ver_label_id_<?php echo $id.'_'.$section_id;?>">Last verified value : <?php echo $labels_dropdown[$val['prev_desc']]; ?></label>
                                                </div>
                                                <!--<input type="text" class="form-control" id="label_id[<?php echo $id;?>][1]" name="label_id[<?php echo $id;?>][1]" placeholder="" value="<?php echo $val['prev_desc'];?>" required>-->
                                                
                                                <?php } }elseif (isset($val['input_type']) && ($val['input_type'] == 'datepicker') ) { ?>
                                                     <div class="row">
                                                    <div class="col-md-1">
                                                        <!--<input type="checkbox" id="chk_<?php echo $id.'_'.$section_id; ?>" name="chk_<?php echo $id.'_'.$section_id; ?>">-->
                                                        <input type="checkbox" name="label_ids[]" class="chk_label"  value="<?php echo $id; ?>">
                                                    </div>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control datepicker" id="label_id_<?php echo $id.'_'.$section_id;?>" name="label_id_<?php echo $id.'_'.$section_id;?>" placeholder="" value="<?php echo $val['curr_desc'];?>" required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div id="reject_docs_div_<?php echo $id.'_'.$section_id; ?>">
                                                        <?php if(!empty($val['prev_desc'])) { ?>
                                                        <a id="rejectdocs_<?php echo $id;?>" class="reject" onclick="rejectDocs('<?php echo $id;?>','<?php echo $profileData['user_id'];?>',2,'<?php echo $section_id; ?>')">
                                                        <i style="cursor: pointer;" title="click to reject docs" class="fa fa-times" aria-hidden="true"></i>
                                                        </a>
                                                        <?php } ?>
                                                        </div>
<!--                                                        <div id="accept_docs_div_<?php echo $id.'_'.$section_id; ?>">
                                                        <a id="acceptdocs_<?php echo $id;?>" class="accept" onclick="approveDocs('<?php echo $id;?>','<?php echo $profileData['user_id'];?>',2,'<?php echo $section_id; ?>')">
                                                        <i style="cursor: pointer;" title="click to aprove docs" class="fa fa-check" aria-hidden="true"></i>
                                                        </a>    
                                                        </div>-->
                                                    </div>
                                                </div>
                                                <?php if(!empty($val['prev_desc'])) { ?>
                                                <div id="last_ver_label_id_<?php echo $id.'_'.$section_id;?>">
                                                <label for="last_ver_label_id_<?php echo $id.'_'.$section_id;?>">Last verified value : <?php echo $val['prev_desc']; ?></label>
                                                </div>
                                                <?php }} else { ?>
                                                <div class="row">
                                                    <div class="col-md-1">
                                                        <!--<input type="checkbox" id="chk_<?php echo $id.'_'.$section_id; ?>" name="chk_<?php echo $id.'_'.$section_id; ?>">-->
                                                        <input type="checkbox" name="label_ids[]" class="chk_label" value="<?php echo $id; ?>">
                                                    </div>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control" id="label_id_<?php echo $id.'_'.$section_id;?>" name="label_id_<?php echo $id.'_'.$section_id;?>" placeholder="" value="<?php echo $val['curr_desc'];?>" required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div id="reject_docs_div_<?php echo $id.'_'.$section_id; ?>">
                                                        <?php if(!empty($val['prev_desc'])) { ?>
                                                        <a id="rejectdocs_<?php echo $id;?>" class="reject" onclick="rejectDocs('<?php echo $id;?>','<?php echo $profileData['user_id'];?>',2,'<?php echo $section_id; ?>')">
                                                        <i style="cursor: pointer;" title="click to reject docs" class="fa fa-times" aria-hidden="true"></i>
                                                        </a>
                                                        <?php } ?>
                                                        </div>
<!--                                                        <div id="accept_docs_div_<?php echo $id.'_'.$section_id; ?>">
                                                        <a id="acceptdocs_<?php echo $id;?>" class="accept" onclick="approveDocs('<?php echo $id;?>','<?php echo $profileData['user_id'];?>',2,'<?php echo $section_id; ?>')">
                                                        <i style="cursor: pointer;" title="click to aprove docs" class="fa fa-check" aria-hidden="true"></i>
                                                        </a>    
                                                        </div>-->
                                                    </div>
                                                </div>
                                                <?php if($id == 9) { ?>
                                                    <div class="row" style="margin-top:5px;">
                                                        <div class="col-md-1">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button class="btn btn-xs btn-info" id="btn_id_<?php echo $id.'_'.$section_id;?>" name="btn_id_<?php echo $id.'_'.$section_id;?>" value="" onclick="getPanStatus('<?php echo $id;?>','<?php echo $profileData['user_id'];?>','<?php echo $section_id; ?>')">GetPanStatus</button>
                                                        </div>
                                                    </div>
                                                    <div class="row" style="margin-top:5px;">
                                                        <div class="col-md-1">
                                                        </div>
                                                        <div id="user_pan_details" class="col-md-8"></div>
                                                    </div>
                                                <?php } ?>
                                                <?php if(!empty($val['prev_desc'])) { ?>
                                                <div id="last_ver_label_id_<?php echo $id.'_'.$section_id;?>">
                                                <label for="last_ver_label_id_<?php echo $id.'_'.$section_id;?>">Last verified value : <?php echo $val['prev_desc']; ?></label>
                                                </div>
                                                <?php }} ?>
                                          </div>
                                            <input type="hidden" id="curr_desc_<?php echo $id.'_'.$section_id; ?>" name="curr_desc_<?php echo $id.'_'.$section_id; ?>" value='<?php echo $val['curr_desc'];?>'>
                                            <input type="hidden" id="prev_desc_<?php echo $id.'_'.$section_id; ?>" name="prev_desc_<?php echo $id.'_'.$section_id; ?>" value='<?php echo $val['prev_desc'];?>'>
                                            <?php
                                            if( ($id == 46) && ($section_id == 3) ){  // after address field and inside aadhar ?>
                                                <div class="row">
                                                    <div class="col-lg-6 form-group">
                                                    <label for="name">Latitude:</label>
                                                        <input class="form-control input-sm" id="lat" name="location_data[lat]" value="<?php echo $lat;?>" type="text">
                                                    </div>
                                                    <div class="col-lg-6 form-group">
                                                    <label for="name">Longitude:</label>
                                                        <input class="form-control input-sm" id="long" name="location_data[long]" value="<?php echo $long;?>" type="text">
                                                    </div>
					                            </div>
                                                <div class="row">
                                                    <div class="col-lg-12 form-group" id="umap"></div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12 form-group">
                                                        <a id="fetch_address" class="btn btn-default btn-block">Get Address From Lat-Long</a>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="area">Area</label><br>
                                                    <input type="text" class="form-control" id="area" name="" placeholder="" value="<?php echo $area;?>" disabled>
                                                </div>
                                                <div class="form-group">
                                                    <label for="city">City</label><br>
                                                    <input type="text" class="form-control" id="city" name="" placeholder="" value="<?php echo $city;?>" disabled>
                                                </div>
                                                <div class="form-group">
                                                    <label for="area">State</label><br>
                                                    <input type="text" class="form-control" id="state" name="" placeholder="" value="<?php echo $state;?>" disabled>
                                                </div>
                                                <div class="form-group">
                                                    <label for="pincode">Pincode</label>
                                                    <input type="text" class="form-control" id="pincode" name="" placeholder="" value="<?php echo $pincode;?>" disabled>
                                                </div>
                                            <?php }
                                        } ?>
                                            <input type="hidden" id="user_id" name="user_id" value='<?php echo $profileData['user_id'];?>'>
                                            <div class="submit-button" id="submit_button_<?php echo $section_id; ?>">
                                                <button class="btn btn-default btn-xs" id="btnsavetextualinfo" onclick="updateTextualInfo('<?php echo $profileData['user_id'];?>',2,'<?php echo $section_id; ?>')">Submit</button>
                                            </div>
                                        </div>

                                    <?php }?>

			</div>
                    
			
                    
                <!--</div>-->
<!--<hr>-->
<div class="col-md-8 flex-col">
    <b><u>Documents</u></b><hr>
                <?php  if(count($doc['documents']) > 1){
                            $div_class = 'col-md-12 doc-images hl'; 
                        } else { 
                            $div_class = 'col-md-12 doc-images';
                        } 
                if(isset($doc['documents']) && count($doc['documents'])){
                   
                   foreach ($doc['documents'] as $doc_id => $data){ ?>
                
			<div class="col-md-12">
                    <form name="uploadForm_<?php echo $doc_id.'_'.$section_id;?>" id="uploadForm_<?php echo $doc_id.'_'.$section_id;?>" method="post" action="" enctype='multipart/form-data'>
			<div class="row">
                        <div class="col-md-4">
				<h4><?php echo $labels[$doc_id]['label'];?></h4>
			</div>
                             <!--<b><?php // echo $labels[$doc_id]['label'];?></b><br/>-->
                            <?php // foreach ($doc['documents'] as $doc_id=>$data){ ?>
                            <div id="upload_docs_div_<?php echo $doc_id.'_'.$section_id;?>" class="col-md-4">
                                <!--<b><?php echo $labels[$doc_id]['label'];?></b><br/>-->
                                <?php if($data['pay1_status']!=1 || $labels[$doc_id]['dynamic_flag'] == 1){?>
                                        <label for="document">Upload</label>
                                        <input type='file' name="document[]" id="document_<?php echo $doc_id.'_'.$section_id;?>" accept="image/*" multiple="multiple" alt="Upload Docs"/>
                                        <div class="submit-button" id="btn_upload_documents_<?php echo $doc_id.'_'.$section_id; ?>">
                                            <input class="btn btn-default btn-primary btn-sm" type="button" value="Upload" onclick="uploadDocs('<?php echo $doc_id;?>','<?php echo $profileData['user_id'];?>',1,'<?php echo $section_id;?>')">
                                        </div>
                                        <?php } ?>
                            </div>
                            <div class="col-md-2">
                                <div id="doc_status_<?php echo $doc_id.'_'.$section_id;?>">
                                    <?php if(isset($data['pay1_status'])){?>
                                            status :<b>
                                            <?php echo $pay1_status[$data['pay1_status']]; ?>
                                            </b>
                                    <?php } ?>
                                </div>
                            </div>
                        <div class="col-md-2">
                                            <div id="verify_docs_div_<?php echo $doc_id.'_'.$section_id;?>">
                                                <?php if(isset($data['description'])){ ?>
                                                    <div id="reject_docs_div_<?php echo $doc_id.'_'.$section_id;?>">
                                                        <?php if($data['pay1_status']!=2){?>
                                                            <a id="rejectdocs_<?php echo $doc_id;?>" class="reject" onclick="rejectDocs('<?php echo $doc_id;?>','<?php echo $profileData['user_id'];?>',1,'<?php echo $section_id; ?>')">
                                                                <i style="cursor: pointer;" title="click to reject docs" class="fa fa-times" aria-hidden="true"></i>
                                                            </a>
                                                        <?php }?>
                                                    </div>
                                                    <div id="accept_docs_div_<?php echo $doc_id.'_'.$section_id;?>">
                                                        <?php if($data['pay1_status']!=1){?>
                                                            <a id="acceptdocs_<?php echo $doc_id;?>" class="accept" onclick="approveDocs('<?php echo $doc_id;?>','<?php echo $profileData['user_id'];?>',1,'<?php echo $section_id; ?>')">
                                                                <i style="cursor: pointer;" title="click to aprove docs" class="fa fa-check" aria-hidden="true"></i>
                                                            </a>
                                                        <?php }?>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                        </div>
                        <br>
                        <div class="row">
                        <div class="<?php echo $div_class; ?>" id="image_container_<?php echo $doc_id.'_'.$section_id;?>">
                        
                        <?php foreach ($data['description'] as $url){?>
				<div class="col-md-4">
					<a class="test-popup-link" href="<?php echo $url;?>">
                                            <img src="<?php echo $url;?>" id="img_url">
					</a>
					<span>
						<a href="<?php echo $url;?>" class="download" download><i class="fa fa-download" aria-hidden="true"></i></a>
						<a class="test-popup-link view" href="<?php echo $url;?>"><i class="fa fa-search-plus" aria-hidden="true"></i></a>
					</span>
				</div>
                            <?php }?>
                        </div>
                        </div>
                                <input type="hidden" name="label_id" id="label_id" value="<?php echo $doc_id;?>">
                                <input type="hidden" name="user_id" id="user_id" value="<?php echo $profileData['user_id'];?>">
                                <input type="hidden" id="dynamic_flag_<?php echo $doc_id.'_'.$section_id; ?>" value="<?php echo $labels[$doc_id]['dynamic_flag']; ?>">
                    </form>
                </div>
                                
                                <?php } ?>
                <?php }
//                    }?>
</div>
                </div>
                <!--</div>-->
        </section>
    <?php }
            }?>

<div  class="modal fade rejectdocsmodal" >
        <div class="modal-dialog" style="width:500px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Reject Documents</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-5"><label class="">Pay1 Comment</label></div>
                    <div class="col-lg-7"><textarea id="pay1comment" name="pay1comment"></textarea></div>
                </div>
                <input type="hidden" name="label_id" id="label_id" value="">
                <input type="hidden" name="user_id" id="user_id" value="">
                <input type="hidden" name="label_type" id="label_type" value="">
                <input type="hidden" name="curr_desc" id="curr_desc" value="">
                <input type="hidden" name="prev_desc" id="prev_desc" value="">
                <input type="hidden" name="section_id" id="section_id" value="">
            </div>
            <div class="modal-footer">
                <div class="save_comments col-lg-2 col-lg-offset-4" id="save_comments">
                    <button type="button" class="btn btn-primary btnsavecomments" onclick='javascript:saveComment()' >Save</button>
                </div>
                <button type="button" class="btn btn-default col-lg-2" data-dismiss="modal">Close</button>
            </div>
        </div>
        </div>
    </div>
<script type="text/javascript" src="/boot/js/jquery.magnific-popup.min.js"></script>
<script type="text/javascript">

	$('.test-popup-link').magnificPopup({
  type: 'image'
  // other options
});
</script>
</body>
</html>
<div class="modal"><!-- Place at bottom of page --></div>
<?php if(isset($profileData['ret_info'])){
    $shop_name = $profileData['ret_info']['ret_shop_name'];
} else {
    $shop_name = $profileData['dist_info']['dist_company'];
}?>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_API_KEY;?>"></script>
<script>
function initializeU() {
	var myLatLng = {lat: <?php echo round($lat, 5) ?>, lng: <?php echo round($long, 5) ?>};

	  var map = new google.maps.Map(document.getElementById('umap'), {
	    zoom: 8,
	    center: myLatLng
	  });

	  var marker = new google.maps.Marker({
	    position: myLatLng,
	    map: map,
	    title: '<?php echo $shop_name; ?>'
	  });

	  var geocoder = new google.maps.Geocoder;
	  var infowindow = new google.maps.InfoWindow;
		$('#fetch_address').click(function(){
			geocodeLatLng(geocoder, map, infowindow, 0);
		});
}
google.maps.event.addDomListener(window, 'load', initializeU);

// function initializeV() {
// 	var myLatLng = {lat: <?php echo round($lat, 5) ?>, lng: <?php echo round($long, 5) ?>};

// 	  var map = new google.maps.Map(document.getElementById('vmap'), {
// 	    zoom: 8,
// 	    center: myLatLng
// 	  });

// 	  var marker = new google.maps.Marker({
// 	    position: myLatLng,
// 	    map: map,
// 	    title: '<?php echo $retailer['r']['shopname'] ?>'
// 	  });

// 	  var geocoder = new google.maps.Geocoder;
// 	  var infowindow = new google.maps.InfoWindow;
// 		$('#fetch_address1').click(function(){
// 			geocodeLatLng(geocoder, map, infowindow, 1);
// 		});
// }
// google.maps.event.addDomListener(window, 'load', initializeV);

function geocodeLatLng(geocoder, map, infowindow) {
	var latlng = getLatLng();
  geocoder.geocode({'location': latlng}, function(results, status) {
    if (status === google.maps.GeocoderStatus.OK) {
      if (results[0]) {
        map.setZoom(11);
        var marker = new google.maps.Marker({
          position: latlng,
          map: map
        });
        infowindow.setContent(results[0].formatted_address);
        infowindow.open(map, marker);
		var area = city = state = postal_code = extra = "";
        results[0]['address_components'].forEach(function(element){
			switch(element['types'][0]){
				case "sublocality_level_1":
					area = element['long_name'];
					break;
				case "locality":
					city = element['long_name'];
					break;
				case "administrative_area_level_1":
					state = element['long_name'];
					break;
				case "administrative_area_level_2":
					extra = element['long_name'];
				case "postal_code":
					pincode = element['long_name'];
					break;
			}
        });
        if(area == ""){
			area = city;
			if(extra != ""){
				city = extra;
			}
        }
        setAddress(area, city, state, pincode);
      } else {
        window.alert('No results found');
      }
    } else {
      window.alert('Geocoder failed due to: ' + status);
    }
  });
}

function setAddress(area, city, state, pincode){
	$('#area').val(area);
	$('#city').val(city);
	$('#state').val(state);
	$('#pincode').val(pincode);
}

function getLatLng(){
	var latLng = {lat: parseFloat($('#lat').val()), lng: parseFloat($('#long').val())};
	return latLng;
}
</script>