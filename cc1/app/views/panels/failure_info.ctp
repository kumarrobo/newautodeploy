
    
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
 <link rel="stylesheet" media="screen" href="/boot/css/bootstrap-theme.min.css">
   <script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script> 
   <link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
   <script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
   <script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
   <script src="http://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>
<script>
 jQuery(document).ready(function() {
        jQuery("#content").removeClass("container");
        jQuery("#content").addClass("container-fluid");
    });
	</script>
	<style type="text/css">
		.checkbox {
			font-size: 12px;
			line-height: 23px;
		}
		.reddiv{
			background-color:#FF0000;
			//border: 1px solid;
			box-shadow: 0 0 2px grey;
		}
		.greendiv{
			background-color: #00FF00;
			//border: 1px solid;
			box-shadow: 0 0 2px grey;
		}
	</style>
	
	<div class="container">
		
		<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto;display:none">
	
</div>
<div id="container1" style="min-width: 310px; height: 400px; margin: 0 auto;display:none">
	
</div>
<div id="container2" style="min-width: 310px; height: 400px; margin: 0 auto;display:none">
	
</div>
<div id="container3" style="min-width: 310px; height: 400px; margin: 0 auto;display:none">
	
</div>
<div id="container4" style="min-width: 310px; height: 400px; margin: 0 auto;display:none">
	
</div>
</div>

        <?php 
        
            foreach ($failuredata as $val){
                $all_modem[] = $val['vendors']['company'] ; 
                $all_response[] = $val['vendors_messages']['response'] ; 
                $all_circle[] = $val['mobile_numbering_area']['area_name'] ; 
            }

            $max_val_response = array_count_values($all_response); 
            $max_fail_response = array_search(max($max_val_response), $max_val_response);
            $count_max_fail_response = $max_val_response[$max_fail_response]; //Count of Max Failure Response
            $max_val_modem = array_count_values($all_modem); 
            $max_fail_modem = array_search(max($max_val_modem), $max_val_modem);
            $count_max_fail_modem = $max_val_modem[$max_fail_modem];     //Count of Max Failure Modem
            $max_val_circle = array_count_values($all_circle); 
            $max_fail_circle = array_search(max($max_val_circle), $max_val_circle);
            $count_max_fail_circle = $max_val_circle[$max_fail_circle];     //Count of Max Failure Circle
        ?>  
        
    <marquee behavior="alternate" scrollamount="1" class="row">
    	<div class="alert alert-danger" role="alert">
    	<label style="font-size:large;">Top modems:</label> <?php echo $max_fail_modem;  echo '  ('.$count_max_fail_modem.')'; ?>
        <br/>
        <label style="font-size:large;">Top reason:</label> <?php echo $max_fail_response; echo '  ('.$count_max_fail_response.')'; ?>
        <br/>
        <label style="font-size:large;">Top circle:</label> <?php echo $max_fail_circle; echo '  ('.$count_max_fail_circle.')'; ?>
	</div>
    </marquee>
        
        
		<h2><span style="color:red;"> <?php echo $products[$oprId]; ?><?php echo !empty($timerange) ? "(".$timerange.")"."Secs" : ""?></span></h2>
  <div class="panel panel-default">
	  <div class="panel-body"><h4>Process Time & Transactions Report</h4></div>
  </div>
  <div class="row" style="padding: 40px 10px 10px;">
	  
			
				
			
		
  
  <table class="table table-hover">
    <thead>
      <tr>
		<th class="col-lg-1"></th>
		<th class="col-lg-1">Tran Id</th>
		<th class="col-lg-2">VendorTxn Id</th>
		<th class="col-lg-1">Retailer Name / shop</th>
		<th class="col-lg-2">Vendor</th>
                <th>Cust Mob</th>
                <th>Amt</th>
                <th>Status</th>
		<th>Provider Response</th>
		<th>Process Time</th>
		<th>Area</th>
		<th>Date</th>
		<th>Update Time</th>
		
      </tr>
    </thead>
    <tbody>
		
		<?php $i = 1; foreach ($failuredata as $val){ 
			$ps = '';
  		if($val['vendors_activations']['status'] == '0'){
			$ps = 'In Process';
		}else if($val['vendors_activations']['status'] == '1'){
			$ps = 'Successful';
		}else if($val['vendors_activations']['status'] == '2'){
			$ps = 'Failed';
		}else if($val['vendors_activations']['status'] == '3'){
			$ps = 'Reversed';
		}else if($val['vendors_activations']['status'] == '4'){
			$ps = 'Reversal In Process';
		}else if($val['vendors_activations']['status'] == '5'){
			$ps = 'Reversal declined';
		}   ?>
		<tr>
			<td><?php echo $i;?></td>
			<td><a target="_blank" href="/panels/transaction/<?php echo $val['vendors_activations']['txn_id'];?>"><?php echo $val['vendors_activations']['txn_id'];?></a></td>
			<td><?php echo $val['vendors_activations']['vendor_refid'];?></td>
			<td><a target="_blank" href="/panels/retInfo/<?php echo $val['retailers']['mobile'];?>"><?php echo !empty($val['retailers']['shopname']) ? $val['retailers']['shopname'] : $val['retailers']['mobile'];?></a></td>
			<td><?php echo $val['vendors']['company'];?></td>
			<td><a target="_blank" href="/panels/userInfo/<?php echo $val['vendors_activations']['mobile']; ?>"><?php echo $val['vendors_activations']['mobile'];?></a></td>
			<td><?php echo $val['vendors_activations']['amount'];?></td>
			<td><?php echo $ps; ?></td>
                        <td><?php echo $val['vendors_messages']['response']; ?></td>
			<td><?php echo $val[0]['processtime'];?>(Secs)</td>
			<td><?php echo $val['mobile_numbering_area']['area_name']; ?></td>
			<td><?php echo $val['vendors_activations']['timestamp'];?></td>
			<td><?php echo $val['vendors_messages']['update_time'];?></td>
		</tr>
		<?php $i++;} ?>
		
	</tbody>
  </table>

			

		
 
</div>
	
 
	



<script>
	

            // When the document is ready
            $(document).ready(function () {
                $('#datepicker').datepicker({
                    format: "yyyy-mm-dd",
					//startDate: "-365d",
						endDate: "1d",
						multidate: false,
						autoclose: true,
						todayHighlight: true
                });  
            
            });
       

	function submit(){
		var frm = $("#frm_time").val();
		var to = $("#to_time").val();
		var url = '/panels/getProcessTime/'+$("#datepicker").val()+"/"+frm+"/"+to;
		window.location = url;
	}
	
	<?php if($type=='all'){ ?>
	
	$(function () {
	
	var failurepercentage = <?php echo json_encode($failurepercen); ?>
	
	
    $('#container').highcharts({
        title: {
            text: 'Failure Report',
            x: -20 //center
        },
        subtitle: {
            text: 'Source: pay1',
            x: -20
        },
        xAxis: {
            categories: <?php echo json_encode(array_values($hourarray)); ?>
        },
        yAxis: {
            title: {
                text: 'Failure Percentage'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
			
			 backgroundColor: '#FCFFC5',
			borderColor: 'black',
			borderRadius: 10,
			borderWidth: 3,
			
        formatter: function() {
			//console.log(this);
			var data = failurepercentage[this.series.name][this.key];
            var text = "Total failure ("+data['failure']+")% ("+data['totalFail']+"/"+data['totalCount']+"):"+this.series.name+'<br/>';
            
		     
			 $.each(data,function(k,y){
				  if(typeof k!='undefined' && typeof k!=null && k!='failure' && k!='totalCount' && k!='totalFail'){
				 text+="<span style ='color:red;width:400px;'>"+k+ ":" + y+ '%'+"</span><br/>" ;
			 }
			 });
			 
			 return text;
        }
           
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },


     series : <?php echo json_encode($data2); ?>
   });
	
});
	<?php } ?>

<?php if($timerange == '0-30'){ ?>
$("#container1").show();	
	
$(function () {

var modemSuccess = <?php echo $modemSuccesspercent; ?>;
    $('#container1').highcharts({
        title: {
            text: '0-30 secs Process Time',
            x: -20 //center
        },
        subtitle: {
            text: 'Source: pay1',
            x: -20
        },
        xAxis: {
            categories: <?php echo json_encode(array_values($hourarray)); ?>
        },
        yAxis: {
            title: {
                text: 'Percentage Transactions'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            
			backgroundColor: '#FCFFC5',
			borderColor: 'green',
			borderRadius: 10,
			borderWidth: 3,
			
        formatter: function() {
			//console.log(this);
			var data = modemSuccess[this.series.name][this.key]['0-30'];
           
			var text = "Total Success ("+data['success']+")% :"+this.series.name+'<br/>';
           
			
			 $.each(data,function(k,v){
				 if(typeof k!='undefined' && typeof k!=null && k!='success' && k!='count'){
				text+="<P style ='color:red;width:400px;'>"+k+ ":" +v+ "%" +" |   ("+data['count'][k]+") Transaction</P><br/>";
			 }
			 });
			 
			 return text;
        }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },


     series : <?php echo json_encode($processtimedata['0-30']); ?>
	
    });
	
	
	
});
<?php } ?>
<?php if($timerange == '30-60'){ ?>
	
$("#container2").show();
$(function () {

var modemSuccess = <?php echo $modemSuccesspercent; ?>;
    $('#container2').highcharts({
        title: {
            text: '30-60 secs Process Time',
            x: -20 //center
        },
        subtitle: {
            text: 'Source: pay1',
            x: -20
        },
        xAxis: {
            categories: <?php echo json_encode(array_values($hourarray)); ?>
        },
        yAxis: {
            title: {
               text: 'Percentage Transactions'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            backgroundColor: '#FCFFC5',
			borderColor: 'red',
			borderRadius: 10,
			borderWidth: 3,
			
        formatter: function() {
			//console.log(this);
		  var data = modemSuccess[this.series.name][this.key]['30-60'];
           var text = "Total Success ("+data['success']+")% :"+this.series.name+'<br/>';
           
		     
			 $.each(data,function(k,v){
				  if(typeof k!='undefined' && typeof k!=null && k!='success' && k!='count'){
				text+="<P style ='color:red;width:400px;'>"+k+ ":" +v+ "%" +" |   ("+data['count'][k]+") Transaction</P><br/>";
			 }
			 });
			 
			 return text;
        }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },


     series : <?php echo json_encode($processtimedata['30-60']); ?>
	
    });
	
	
	
});
<?php } ?>

<?php if($timerange == '60-90'){ ?>
	
$("#container3").show();

$(function () {

var modemSuccess = <?php echo $modemSuccesspercent; ?>;
    $('#container3').highcharts({
        title: {
            text: '60-90 secs Process Time',
            x: -20 //center
        },
        subtitle: {
            text: 'Source: pay1',
            x: -20
        },
        xAxis: {
            categories: <?php echo json_encode(array_values($hourarray)); ?>
        },
        yAxis: {
            title: {
                text: 'Percentage Transactions'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
             backgroundColor: '#FCFFC5',
			borderColor: 'yellow',
			borderRadius: 10,
			borderWidth: 3,
			
        formatter: function() {
			//console.log(this);
		    var data = modemSuccess[this.series.name][this.key]['60-90'];
            var text = "Total Success ("+data['success']+")% :"+this.series.name+'<br/>';
		     
			 $.each(data,function(k,v){
				  if(typeof k!='undefined' && typeof k!=null && k!='success' && k!='count'){
				text+="<P style ='color:red;width:400px;'>"+k+ ":" +v+ "%" +" |   ("+data['count'][k]+") Transaction</P><br/>";
			 }
			 });
			 
			 return text;
        }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },


     series : <?php echo json_encode($processtimedata['60-90']); ?>
	
    });
	
	
	
});
<?php } ?>

<?php if($timerange == '90-100'){ ?>
	
$("#container4").show();

$(function () {

var modemSuccess = <?php echo $modemSuccesspercent; ?>;
    $('#container4').highcharts({
        title: {
            text: '90-100 secs prcoess time',
            x: -20 //center
        },
        subtitle: {
            text: 'Source: pay1',
            x: -20
        },
        xAxis: {
            categories: <?php echo json_encode(array_values($hourarray)); ?>
        },
        yAxis: {
            title: {
                text: 'Percentage Transactions'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            backgroundColor: '#FCFFC5',
			borderColor: 'pink',
			borderRadius: 10,
			borderWidth: 3,
			
        formatter: function() {
			 var data = modemSuccess[this.series.name][this.key]['90-100'];
             var text = "Total Success ("+data['success']+")% :"+this.series.name+'<br/>';
           
			 $.each(data,function(k,v){
				  if(typeof k!='undefined' && typeof k!=null && k!='success' && k!='count'){
				text+="<P style ='color:red;width:400px;'>"+k+ ":" +v+ "%" +" |   ("+data['count'][k]+") Transaction</P><br/>";
			 }
			 });
			 
			 return text;
        }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },


     series : <?php echo json_encode($processtimedata['90-100']); ?>
	
    });
	
	
	
});
<?php } ?>
	

</script>







