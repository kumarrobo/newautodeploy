    <link rel="stylesheet" media="screen" href="/boot/css/c2d.css">

    <span>-> &nbsp;<a href="/c2d/clickToCallListing/">Click To Call Listing</a></span> &nbsp;&nbsp;<strong>|</strong>&nbsp;&nbsp; <span><a href="/c2d/postInterestListing/">Post Interest</a></span> &nbsp;&nbsp;<strong>|</strong>&nbsp;&nbsp; <span>C2D Posts</span> <br/><br/>
    <nav class="navbar navbar-default">
            <div class="container-fluid">
                    <div class = "row">	
                            <div class = "col-md-2">
                            <div class="navbar-header">
                                <?php echo $html->image("pay1_logo.svg?213", array("url" => array('controller'=>'c2d','view'=>'clickToCallListing'))); ?>
                            </div>
                            </div>
           <div class = "col-md-8" align = "center">
                                    <h2><b>C2D Posts</b></h2>
                       </div>  
       </div>
            </div>
    </nav>
    <span>
        Page : <select onchange="window.location='/c2d/c2dPost/'+this.value+'/<?php echo $recs ?>';" style="margin-right: 25px;">
                    <?php for($i=1;$i<=ceil($totalrecords/$recs);$i++) { ?>
                    <option <?php if($page == $i) { echo "selected"; } ?>><?php echo $i; ?></option>
                    <?php } ?>
                </select>
        Records / Page : <select onchange="window.location='/c2d/c2dPost/1/'+this.value;" style="margin-right: 25px;">
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
                                                    <th class = "field-label active" style = "width: 3%;">#</th>
                                                    <th class = "field-label active" style = "width: 10%;">TITLE</th>
                                                    <th class = "field-label active" style = "width: 4%;">IMAGE</th>
                                                    <th class = "field-label active" style = "width: 7%;">CATEGORY</th>
                                                    <th class = "field-label active" style = "width: 10%;">DESCRIPTION</th>
                                                    <th class = "field-label active" style = "width: 7%;">COMPANY NAME</th>
                                                    <th class = "field-label active" style = "width: 8%;">CONTACT PERSON</th>
                                                    <th class = "field-label active" style = "width: 4%;">CONTACT NO</th>
                                                    <th class = "field-label active" style = "width: 10%;">TIME</th>
                                            </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                            $i = ($page-1)*$recs+1;
                                            $image_path = "http://c2dcdn.s3.amazonaws.com/production/products/";

                                            foreach($listing_data as $list) {
                                                $k = 1;
                                                $temp_img = array();

                                                foreach($listing_images[$list['c2d_posts']['id']] as $image) {
                                                    $temp_img[] = "<a href='$image_path$image' target='_blank'>Image ".$k++."</a>";
                                                }
                                        ?>
                                            <tr>
                                                    <td><?php echo $i++; ?></td>
                                                    <td><?php echo $list['c2d_posts']['title'] == '' ? '<center>-<center>' : $list['c2d_posts']['title']; ?></td>
                                                    <td><?php echo implode('<br>', $temp_img); ?></td>
                                                    <td><?php echo $list['c2d_categories']['group_name'] == '' ? '<center>-<center>' : $list['c2d_categories']['group_name']; ?></td>
                                                    <td><?php echo $list['c2d_posts']['description'] == '' ? '<center>-<center>' : $list['c2d_posts']['description']; ?></td>
                                                    <td><?php echo $list['cash_payment_client']['company_name']; ?></td>
                                                    <td><?php echo $list['cash_payment_client']['contact_person_name']; ?></td>
                                                    <td><?php echo $list['cash_payment_client']['contact_no']; ?></td>
                                                    <td><?php echo date('d-M-Y', strtotime($list['c2d_posts']['post_time'])) . ' &nbsp;<strong>at</strong>&nbsp; ' . date('h:i A', strtotime($list['c2d_posts']['post_time'])); ?></td>
                                            </tr>
                                            <?php } ?>
                                    </tbody>
                            </table>
                    </div>
            </div>
    </div>
    
    <script type="text/javascript" src="/boot/js/c2d.js"></script>