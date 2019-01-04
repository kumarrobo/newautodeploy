    <link rel="stylesheet" media="screen" href="/boot/css/c2d.css">

    <span>-> &nbsp;<a href="/c2d/clickToCallListing/">Click To Call Listing</a></span> &nbsp;&nbsp;<strong>|</strong>&nbsp;&nbsp; <span>Post Interest</span> &nbsp;&nbsp;<strong>|</strong>&nbsp;&nbsp; <span><a href="/c2d/c2dPost/">C2D Posts</a></span> <br/><br/>
    <nav class="navbar navbar-default">
            <div class="container-fluid">
                    <div class = "row">	
                            <div class = "col-md-2">
                                    <div class="navbar-header">
                                            <?php echo $html->image("pay1_logo.svg?213", array("url" => array('controller'=>'c2d','view'=>'postInterestListing'))); ?>
                                    </div>
                            </div>
                            <div class = "col-md-8" align = "center">
                                    <h2><b>List-Interest's Listing</b></h2>
                            </div>  
                    </div>
            </div>
    </nav>
    <span>
        Page : <select onchange="window.location='/c2d/postInterestListing/'+this.value+'/<?php echo $recs ?>';" style="margin-right: 25px;">
                    <?php for($i=1;$i<=ceil($totalrecords/$recs);$i++) { ?>
                    <option <?php if($page == $i) { echo "selected"; } ?>><?php echo $i; ?></option>
                    <?php } ?>
                </select>
        Records / Page : <select onchange="window.location='/c2d/postInterestListing/1/'+this.value;" style="margin-right: 25px;">
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
                                                    <th class = "field-label active" style = "width: 5%;">#</th>
                                                    <th class = "field-label active" style = "width: 10%;">POST TITLE</th>
                                                    <th class = "field-label active" style = "width: 10%;">POST DESCRIPTION</th>
                                                    <th class = "field-label active" style = "width: 10%;">RETAILER NAME</th>
                                                    <th class = "field-label active" style = "width: 10%;">RETAILER SHOP NAME</th>
                                                    <th class = "field-label active" style = "width: 10%;">RETAILER MOBILE</th>
                                                    <th class = "field-label active" style = "width: 10%;">IS STILL INTERESTED</th>
                                                    <th class = "field-label active" style = "width: 10%;">SHARE MY NUMBER</th>
                                                    <th class = "field-label active" style = "width: 10%;">MESSAGE</th>
                                                    <th class = "field-label active" style = "width: 10%;">INTERESTED DATE</th>
                                            </tr>
                                    </thead>
                                    <tbody>
                                            <?php foreach($listing_data as $list) { ?>
                                            <tr>
                                                    <td><?php echo $list['c2d_posts_interests']['id']; ?></td>
                                                    <td><?php echo $list['c2d_posts']['title'] == '' ? '<center>-<center>' : $list['c2d_posts']['title']; ?></td>
                                                    <td><?php echo $list['c2d_posts']['description'] == '' ? '<center>-<center>' : $list['c2d_posts']['description']; ?></td>
                                                    <td><?php echo $retailers[$list['c2d_posts_interests']['retailer_id']]['name'] == '' ? '<center>-</center>' : ucwords(strtolower($retailers[$list['c2d_posts_interests']['retailer_id']]['name'])); ?></td>
                                                    <td><?php echo $retailers[$list['c2d_posts_interests']['retailer_id']]['shopname'] == '' ? '<center>-</center>' : $retailers[$list['c2d_posts_interests']['retailer_id']]['shopname']; ?></td>
                                                    <td><?php echo $retailers[$list['c2d_posts_interests']['retailer_id']]['mobile'] == '' ? '<center>-</center>' : "<a href='/panels/retInfo/".$retailers[$list['c2d_posts_interests']['retailer_id']]['mobile']."' target='_blank'>".$retailers[$list['c2d_posts_interests']['retailer_id']]['mobile']."</a>"; ?></td>
                                                    <td><center><?php echo $list['c2d_posts_interests']['is_still_interested'] == 1 ? 'Yes' : 'No'; ?></center></td>
                                                    <td><center><?php echo $list['c2d_posts_interests']['can_share_my_number'] == 1 ? 'Yes' : 'No'; ?></center></td>
                                                    <td><?php echo $list['c2d_posts_interests']['message']; ?></td>
                                                    <td><?php echo date('d-M-Y', strtotime($list['c2d_posts_interests']['interested_date'])); ?></td>
                                            </tr>
                                            <?php } ?>
                                    </tbody>
                            </table>
                    </div>
            </div>
    </div>
    
    <script type="text/javascript" src="/boot/js/c2d.js"></script>