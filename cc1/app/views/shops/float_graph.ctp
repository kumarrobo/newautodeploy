<script type="text/javascript" src="https://www.google.com/jsapi"></script>
 
<script type="text/javascript">
    function findData(){
	var html = $('submit').innerHTML;
	showLoader3('submit');
	var date_from = $('fromDate').value;
	var date_to = $('toDate').value;
	if(date_from == '' || date_to == ''){
		$('date_err').show();
		$('submit').innerHTML = html;
	}
	else {
		$('date_err').hide();
		date_from = date_from.replace(/-/g,"");
		date_to = date_to.replace(/-/g,"");
		window.location.href = "/shops/floatGraph/?type=<?php echo $type;?>&from="+date_from+"&to="+date_to;
	}
}
function drawVisualization() {
  //google.load('visualization', '1.0', {'packages':['corechart']});

  // Create and populate the data table.
  var data = google.visualization.arrayToDataTable( <?php echo json_encode($saledata);?>);

  // Create and draw the visualization.
  var ac = new google.visualization.ComboChart(document.getElementById('visualization'));
  ac.draw(data,<?php echo json_encode($optionSales);?> );


  //var data2 = google.visualization.arrayToDataTable( <?php echo json_encode($floatdata);?>);
  var dataTable = new google.visualization.DataTable();
  
  dataTable.addColumn('string', 'Date');
  // Use custom HTML content for the domain tooltip.
  dataTable.addColumn({'type': 'string', 'role': 'tooltip', 'p': {'html': true}});
  //dataTable.addColumn({type: 'string', role: 'tooltip'});
  
  dataTable.addColumn('number', 'Min');
  dataTable.addColumn('number', 'Max');
  dataTable.addColumn('number', 'Day End');
  
  //var dataTable = new google.visualization.DataTable();
  //dataTable.addColumn('string', 'Year');
  //dataTable.addColumn('number', 'Sales');
  // A column for custom tooltip content
  
   dataTable.addRows(<?php echo json_encode($floatdata);?>);
  // Create and draw the visualization.
  var ac2 = new google.visualization.ComboChart(document.getElementById('visualization2'));
  ac2.draw(dataTable,<?php echo json_encode($optionFloat);?> );
  
  
  var data2 = google.visualization.arrayToDataTable( <?php echo json_encode($datewisesaledata);?>);

  // Create and draw the visualization.
  var ac2 = new google.visualization.ComboChart(document.getElementById('visualization3'));
  ac2.draw(data2,<?php echo json_encode($optionDateWiseSale);?> );

}
</script>
<div>
<?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'reports'));?>
    
    	<div class="loginCont">
    		<?php echo $this->element('shop_side_reports',array('side_tab' => 'float_graph'));?>
</div>
 <div id="innerDiv">
<span style="font-weight:bold;margin-right:10px;">
    Select Date Range: 
</span>
        From <input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'fromDate','restrict=true,open=true')" id="fromDate" name="fromDate" value="<?php if(!empty($from)) echo date('d-m-Y', strtotime($from));?>"> - To<input type="text" style="margin-left:10px; width: 100px; cursor: pointer;" maxlength="10" onmouseover="fnInitCalendar(this, 'toDate','restrict=true,open=true')" id="toDate" name="toDate" value="<?php if(!empty($to)) echo date('d-m-Y', strtotime($to));?>">

<span style="margin-left:30px;" id="submit"><input type="button" value="Search" class="retailBut enabledBut" style="padding: 0 5px 3px" id="sub" onclick="findData();"></span>
</div>
<div style="margin-top:10px;"><span id="date_err" class="error" style="display:none;">Error: Please select dates</span></div>
​<div id='visualization' style="margin-left:220px;">Loading Hourly Sale Graph ...</div>
<div id='visualization2' style="margin-left:220px;">Loading Float Graph ...</div>
<div id='visualization3' style="margin-left:220px;">Loading Daily Sale Graph ...</div>
   <script type="text/javascript">

      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawVisualization);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      /*function drawChart() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
          ['Mushrooms', 3],
          ['Onions', 1],
          ['Olives', 1],
          ['Zucchini', 1],
          ['Pepperoni', 2]
        ]);

        // Set chart options
        var options = {'title':'How Much Pizza I Ate Last Night',
                       'width':400,
                       'height':300};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }*/
    //drawVisualization()
    </script>
   