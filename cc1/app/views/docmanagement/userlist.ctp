<!-- <link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css"> -->
<!-- <script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script> -->
<script type="text/javascript" src="/boot/js/docmanagement.js"></script>
<style>
    table{
        font-size: 14px;
    }
</style>

    <div class="row">
    <div class="col-lg-12">
           <div class="page-header">
                    <h3>Active Users<?php echo (count($active_users) > 0) ? '('.count($active_users).')' : null; ?></h3>
           </div>
        </div>
    </div>

        <div class="row" id="active_users">
            <div class="col-lg-12">
                    <?php
                    if( count($active_users) > 0 ){ ?>
                        <table class="table table-hover table-stripped table-bordered">
                            <thead style="background-color:#428bca;color:#fff;">
                                <tr>
                                    <th>#</th>
                                    <th>User ID</th>
                                    <th>Mobile</th>
                                    <th>Name</th>
                                    <th>Document Info</th>
                                    <th>Textual Info</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <?php
                                    $i = 0;
                                    foreach ( $active_users as $user_id => $details ){
                                        $i++;
                                        ?>
                                        <tr>
                                            <td>
                                                <?php
                                                    echo $i;
                                                ?>
                                            </td>
                                            <td><a href="/docmanagement/userProfile/<?php echo $user_id; ?>" target="_blank"><?php echo $user_id; ?></a></td>
                                            <td><a href="/docmanagement/userProfile/<?php echo $user_id; ?>" target="_blank"><?php echo $details['info']['mobile']; ?></a></td>
                                            <td><?php echo $details['info']['name']; ?></td>
                                            <td>
                                                <?php
                                                    if( $details['document'] && (count($details['document']) > 0) ){
                                                        foreach($details['document'] as $label => $label_details){
                                                            echo '<strong>'.ucfirst($label).'</strong>('.$label_details['pay1_status'].'):<br>';
                                                            foreach($label_details['urls'] as $index => $url){
                                                                echo '<a target="_blank" href="'.$url.'">'.$label.($index+1).'</a><br>';
                                                            }
                                                        }
                                                    }
                                                ?>
                                            </td>
                                            <td><?php
                                                    if( $details['textual'] && (count($details['textual']) > 0) ){
                                                        foreach($details['textual'] as $label => $value){
                                                            echo '<strong>'.ucfirst(str_ireplace('_',' ',$label)).'</strong>: '.$value.'<br>';
                                                        }
                                                    }
                                                ?></td>
                                        </tr>
                                    <?php } ?>
                            </tbody>
                        </table>
                        <?php } ?>
            </div>
        </div>