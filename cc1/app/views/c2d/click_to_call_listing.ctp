    <link rel="stylesheet" media="screen" href="/boot/css/c2d.css">
    
    <span>-> &nbsp;Click To Call Listing</span> &nbsp;&nbsp;<strong>|</strong>&nbsp;&nbsp; <span><a href="/c2d/postInterestListing/">Post Interest</a></span> &nbsp;&nbsp;<strong>|</strong>&nbsp;&nbsp; <span><a href="/c2d/c2dPost/">C2D Posts</a></span> <br/><br/>
    <nav class="navbar navbar-default">
            <div class="container-fluid">
                    <div class = "row">	
                            <div class = "col-md-2">
                            <div class="navbar-header">
                                <?php echo $html->image("pay1_logo.svg?213", array("url" => array('controller'=>'c2d','view'=>'clickToCallListing'))); ?>
                            </div>
                            </div>
           <div class = "col-md-8" align = "center">
                                    <h2><b>Click-To-Call Listing</b></h2>
                       </div>  
       </div>
            </div>
    </nav>
    <span>
        Page : <select onchange="window.location='/c2d/clickToCallListing/'+this.value+'/<?php echo $recs ?>';" style="margin-right: 25px;">
                    <?php for($i=1;$i<=ceil($totalrecords/$recs);$i++) { ?>
                    <option <?php if($page == $i) { echo "selected"; } ?>><?php echo $i; ?></option>
                    <?php } ?>
                </select>
        Records / Page : <select onchange="window.location='/c2d/clickToCallListing/1/'+this.value;" style="margin-right: 25px;">
                            <option <?php if($recs == 100) { echo "selected"; } ?>>100</option>
                            <option <?php if($recs == 500) { echo "selected"; } ?>>500</option>
                            <option <?php if($recs == 1000) { echo "selected"; } ?>>1000</option>
                            <option <?php if($recs == 5000) { echo "selected"; } ?>>5000</option>
                        </select>
        <strong>Total Records : <span style="color: blue;"><?php echo $totalrecords; ?></span></strong>
    </span><br /><br />

    <div class="tab-content">
            <div class="tab-pane active" id="list">
                    <div class="table-responsive">
                            <table class="tablesorter table table-hover table-bordered" id = "plantable">
                                    <thead>
                                            <tr>
                                                    <th class = "field-label active" style = "width: 4%;">#</th>
                                                    <th class = "field-label active" style = "width: 6%;">POST TITLE</th>
                                                    <th class = "field-label active" style = "width: 10%;">POST DESCRIPTION</th>
                                                    <th class = "field-label active" style = "width: 10%;">WHOLESALER NAME</th>
                                                    <th class = "field-label active" style = "width: 6%;">WHOLESALER MOBILE</th>
                                                    <th class = "field-label active" style = "width: 10%;">RETAILER NAME</th>
                                                    <th class = "field-label active" style = "width: 10%;">RETAILER SHOP NAME</th>
                                                    <th class = "field-label active" style = "width: 6%;">RETAILER MOBILE</th>
                                                    <th class = "field-label active" style = "width: 6%;">ORDER TAG</th>
                                                    <th class = "field-label active" style = "width: 16%;">COMMENTS</th>
                                                    <th class = "field-label active" style = "width: 24%;">CALL TIME</th>
                                            </tr>
                                    </thead>
                                    <tbody>
                                            <?php foreach($listing_data as $list) { ?>
                                            <tr>
                                                    <td><?php echo $list['c2d_click_to_call']['id']; ?></td>
                                                    <td><?php echo $list['c2d_posts']['title'] == '' ? '<center>-<center>' : $list['c2d_posts']['title']; ?></td>
                                                    <td><?php echo $list['c2d_posts']['description'] == '' ? '<center>-<center>' : $list['c2d_posts']['description']; ?></td>
                                                    <td><?php echo $list['cash_payment_client']['company_name']; ?></td>
                                                    <td><?php echo $list['c2d_click_to_call']['wsmobile'] == '' ? '<center>-<center>' : $list['c2d_click_to_call']['wsmobile']; ?></td>
                                                    <td><?php echo $retailers[$list['c2d_click_to_call']['retailer_id']]['name'] == '' ? '<center>-</center>' : $retailers[$list['c2d_click_to_call']['retailer_id']]['name']; ?></td>
                                                    <td><?php echo $retailers[$list['c2d_click_to_call']['retailer_id']]['shopname'] == '' ? '<center>-</center>' : $retailers[$list['c2d_click_to_call']['retailer_id']]['shopname']; ?></td>
                                                    <td><?php echo $list['c2d_click_to_call']['retailermobile'] == '' ? '<center>-</center>' : "<a href='/panels/retInfo/".$list['c2d_click_to_call']['retailermobile']."' target='_blank'>".$list['c2d_click_to_call']['retailermobile']."</a>"; ?></td>
                                                    <td>
                                                            <select class="tags" data-id="<?php echo $list['c2d_click_to_call']['post_id']; ?>" style="width: 85px;">
                                                                    <option value="">Select Tag</option>
                                                                    <?php foreach($order_tags as $tag) { ?>
                                                                    <option data-tag="<?php echo $tag['c2d_order_tags']['id'] ?>" <?php if($tag['c2d_order_tags']['id'] == $list['c2d_post_order_tag']['tag_id']) { echo "selected"; } ?>><?php echo $tag['c2d_order_tags']['tags'] ?></option>
                                                                    <?php } ?>
                                                            </select>
                                                    </td>
                                                    <td><button class="addComment" data-id="<?php echo $list['c2d_click_to_call']['post_id']; ?>">Add</button> &nbsp; <button class="viewComment" data-id="<?php echo $list['c2d_click_to_call']['post_id']; ?>">View</button></td>
                                                    <td><?php echo date('d-M-Y', strtotime($list['c2d_click_to_call']['call_timestamp'])) . ' &nbsp;<strong>at</strong>&nbsp; ' . date('h:i A', strtotime($list['c2d_click_to_call']['call_timestamp'])); ?></td>
                                            </tr>
                                            <?php } ?>
                                    </tbody>
                            </table>

                    </div>
            </div>
    </div>
    <div class="modal fade" id="view-details" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                    <div class="modal-content">
                            <div class = "modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h3 class="modal-title" align="center">Comment Details</h3>
                            </div>

                            <div class = "modal-body">
                                    <h5 class="modal-title" align="center">Comment Details</h5>
                            </div>

                            <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                    </div>
            </div>
    </div>
    <script type="text/javascript" src="/boot/js/c2d.js"></script>