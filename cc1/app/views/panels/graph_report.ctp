<?php
     //error_reporting(0);
?>
<link rel="stylesheet" media="screen" href="/boot/css/bootstrap.min.css">
   <script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script> 
   <link rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
   <script type="text/javascript" src="/boot/js/bootstrap-datepicker.min.js"></script>
   <script type="text/javascript" src="/boot/js/bootstrap.min.js"></script>
<script src="http://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>
<div class="container">
<div class="row">
			<div class="col-lg-3" style="text-align: center;border:1px;">
				<input type="text" class="form-control" style='width:270px;margin-bottom: 20px;'  id="frm_date"   value="<?php echo $date; ?>">
			</div>
	       
	
	<div class="col-lg-9" style="text-align:center;">
		<?php if($type!='weekwise'){  ?>
				<label class="control-label">Select Time: </label>
				<select name ="frm_time" id="frm_time" class="">
					<option value="">---Select time----</option>
					<?php for($i=0;$i<24;$i++){ ?>
					<option value="<?php echo $i; ?>" <?php if($frm == $i) { echo "selected" ;}?>><?php echo  date("H.iA", strtotime("$i:00")); ?></option>
					<?php } ?>

				</select>
			 To
			 <select name ="to_time" id="to_time"  class="">
					<option value="">---Select time----</option>
					<?php for($i=0;$i<24;$i++){ ?>
					<option value="<?php echo $i; ?>" <?php if($to == $i) { echo "selected" ;}?>><?php echo  date("H.iA", strtotime("$i:00")); ?></option>
					<?php } ?>

				</select>
		<?php }  ?>
			  <label class="control-label">Select Type: </label>
			  <select name ="report_type" id="report_type"  class="">
					
					<option value="hourwise">Hourwise</option>
					<option value="weekwise" <?php if($type =="weekwise"){ echo "selected"; }?>>Weekwise</option>

				</select>
			 
		<?php //} ?>
			
			
				<input type="button" value="submit" onclick="submit();" class="btn btn-primary">
		

		</div>
</div>
<?php if($type!='weekwise'){ ?>
<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto;display: none;">
	
</div>
<div id="container1" style="min-width: 310px; height: 400px; margin: 0 auto;display: none;">
	
</div>
<div id="container2" style="min-width: 310px; height: 400px; margin: 0 auto;display: none;">
	
</div>
<div id="container3" style="min-width: 310px; height: 400px; margin: 0 auto;display: none;">
	
</div>
<div id="container4" style="min-width: 310px; height: 400px; margin: 0 auto;display: none;">
	
</div>
	
	
<?php } ?>
	
<div id="container5" style="min-width: 310px; height: 400px; margin: 0 auto;display: none;">
	
</div>
<div id="container6" style="min-width: 310px; height: 400px; margin: 0 auto;display: none;">
	
</div>
	<input type="hidden" id="opr_id" value="<?php echo $oprId ?>">
	
<script type="text/javascript">
<?php if($type!='weekwise'){ ?>
	
$("#container").show();
$("#container1").show();
$("#container2").show();
$("#container3").show();
$("#container4").show();
$(function () {
	
	var errortype = <?php echo json_encode($errortype); ?>;
	
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
//        series: [{
//            name: 'Tokyo',
//            data: [7.0, 6.9, 9.5, 14.5, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
//        }, {
//            name: 'New York',
//            data: [-0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 14.1, 8.6, 2.5]
//        }, {
//            name: 'Berlin',
//            data: [-0.9, 0.6, 3.5, 8.4, 13.5, 17.0, 18.6, 17.9, 14.3, 9.0, 3.9, 1.0]
//        }, {
//            name: 'London',
//            data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
//        }]

     series : <?php echo json_encode($data2); ?>
	
    });
	
	
});

$(function () {

var modemSuccess = <?php echo $modemSuccesspercent; ?>;
    $('#container1').highcharts({
        title: {
            text: '0-40 secs Process Time',
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
			var data = modemSuccess[this.series.name][this.key]['0-40'];
           
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


     series : <?php echo json_encode($processtimedata['0-40']); ?>
	
    });
	
	
});

$(function () {
var modemSuccess = <?php echo $modemSuccesspercent; ?>;
    $('#container2').highcharts({
        title: {
            text: '40-60 secs Process Time',
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
		  var data = modemSuccess[this.series.name][this.key]['40-60'];
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


     series : <?php echo json_encode($processtimedata['40-60']); ?>
	
    });
	
	
});

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
<?php } else { ?>

$("#container5").show();
$("#container6").show();

$(function () {
	
	var totalfailure = <?php  echo $totalfailure;?>;
	
    $('#container5').highcharts({
        title: {
            text: 'day wise failure report',
            x: -20 
        },
        subtitle: {
            text: 'Source: pay1',
            x: -20
        },
        xAxis: {
			
			categories: <?php echo json_encode(array_values($datearray)); ?>
		
        },
        yAxis: {
            title: {
                text: 'Failure report'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
			  formatter: function() {
				  
				   var text = 'Failure Percentage   :<br/>';
				   
				   
				   
				   text+="<P style ='color:red;width:400px'>"+this.key+ ":" +totalfailure[this.key]+ '%'+"</P><br/>" ;
			       
				   
				   return text;
				  
			  }
           
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },


     series : <?php echo json_encode($dayFailure); ?>
	
    });
	
	
});


$(function () {
    $('#container6').highcharts({
        title: {
            text: 'date wise Process time report',
            x: -20 
        },
        subtitle: {
            text: 'Source: pay1',
            x: -20
        },
        xAxis: {
			
			categories: ["0-30","30-60","60-90","90-100"]
		
        },
        yAxis: {
            title: {
                text: 'Failure report'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: '%'
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },


     series : <?php echo json_encode($data6); ?>
	
    });
	
	
});


<?php } ?>
$(document).ready(function () {
                $('#frm_date').datepicker({
                    format: "yyyy-mm-dd",
					//startDate: "-365d",
						endDate: "1d",
						multidate: false,
						autoclose: true,
						todayHighlight: true
                });  
				$('#to_date').datepicker({
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
		var oprId = $("#opr_id").val();
		var frmdate = $('#frm_date').val();
		var type  = $("#report_type").val();
		if(type=="weekwise"){
			var url = "/panels/graphReport/?date="+frmdate+"&oprId="+oprId+"&type="+type;
		} else {
	
			var url = "/panels/graphReport/?date="+frmdate+"&frm="+frm+"&to="+to+"&oprId="+oprId+"&type="+type;
		}
//		var url = "/panels/graphReport/?date="+frmdate+"&frm="+frm+"&to="+to+"&oprId="+oprId+"&type="+type;
		window.location = url;
				
	         }

</script>