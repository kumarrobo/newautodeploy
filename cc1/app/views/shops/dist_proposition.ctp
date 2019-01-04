<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <style>
            .table{
                margin-top: 65px;
                margin-left: 65px;
            }
            
        </style>
    </head>

    <body>

        <div class="container">
<div>
    <?php echo $this->element('shop_upper_tabs',array('tob_tab' => 'support'));?>
    
    <div id="pageContent" style="min-height:500px;position:relative;">
    	<div class="loginCont">
                                    <?php echo $this->element('shop_side_support',array('side_tab' => 'proposition'));?>
    	<div id="innerDiv" class="leftFloat">
  
            <h2> Distributor proposition</h2>
                    
          <table class="table table-bordered table-striped">
            <thead>
                <tr> 
                  <th>Product</th>
                  <th>Distributor Margin </th>
                </tr>
           </thead>
           <tbody>
               <tr> 
                   <td> Mobile Recharge </td>
                   <td> 0.5% of mobile recharge </td>                   
               </tr>
               <tr> 
                   <td> DTH </td>
                   <td> 0.5%  of DTH recharge </td>                   
               </tr>
               <tr> 
                   <td> Postpaid Bills </td>
                   <td> 0.5% of postpaid bills </td>                   
               </tr>
               <tr> 
                   <td> Utility Bills </td>
                   <td> 0.1% of bill payments </td>                   
               </tr>
               <tr> 
                   <td> C2D Recharge </td>
                   <td> 0% </td>                   
               </tr>
               <tr> 
                   <td> MPOS </td>
                   <td>Per Device: Rs 250 / Monthly Rental: Rs 20 </td>                   
               </tr>
               <tr> 
                   <td> DMT </td>
                   <td> 0.2% of transactions </td>                   
               </tr>
                        
            </tbody>
          </table>
                    
         </div>
     <br class="clearLeft" />
    </div>
    </div>
 </div>
 </div>            
<br class="clearRight" />
</body>
</html>