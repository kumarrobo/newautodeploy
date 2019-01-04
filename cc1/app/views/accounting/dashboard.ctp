<script src="/boot/js/jquery-2.0.3.min.js"></script>
<link rel="stylesheet" href="/css/bootstrap.min.css">
<script src="/boot/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="http://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css">
<script type="text/javascript" src="/boot/js/jquery.dataTables.min.js"></script>

<div class="container">
    <div class="row">
        <h3 style="text-align:center;">Overview Report </h3>
        <div class="col-md-6">
            <div class="panel panel-primary filterable">
                <div class="panel-heading panel-title">
                    <div class="panel-title" style="text-align:center;"><font size="5" face="Georgia, serif">Profit / Loss Overall</font></div>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th class="col-xs-2">Id</th>
                            <th class="col-xs-8">Services</th>
                            <th class="col-xs-2">Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($service as $services) {
                            echo '<tr id="tablerow' . $lead['ln']['id'] . '">';
                            echo '<td>' . $services['services']['id'] . '</td>';
                            echo '<td>' . $services['services']['name'] . '</td>';
                            echo '<td>' . ($service [0][0]['amount_settled'] - $service [0][0]['amount']) . '</td>';
                            echo '</tr>';
                        }
                        ?>

                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel panel-primary filterable">
                <div class="panel-heading">
                    <div class="panel-title" style="text-align:center;"><font size="5" face="Georgia, serif">Other Report Count</font></div>
                </div>
                <table class="table">

                    <tbody>
                        <tr>
                            <td class="col-xs-2">1</td><td class="col-xs-8"> Total Txns Matched </td><td class="col-xs-2">23</td>
                        </tr>
                        <tr>
                            <td class="col-xs-2">2</td><td class="col-xs-8"> Total Txns Mismatched </td><td class="col-xs-2">44</td>
                        </tr>
                        <tr>
                            <td class="col-xs-2">3</td><td class="col-xs-8"> Vendor Margins Mismatched</td><td class="col-xs-2">86</td>
                        </tr>
                        <tr>
                            <td class="col-xs-2">4</td><td class="col-xs-8">Txns Settled in Bank</td><td>23</td>
                        </tr>
                        <tr>
                            <td class="col-xs-2">5</td><td class="col-xs-8">Txns Settled in Wallet</td><td class="col-xs-2">44</td>
                        </tr>
                        <tr>
                            <td class="col-xs-2">6</td><td class="col-xs-8">Total Sale</td><td class="col-xs-2">26</td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">

        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <div class="panel-title" style="text-align:center;"><font size="5" face="Georgia, serif	">Txns Mismatched</font></div>
            </div>
            <table class="table table-fixed" id="example3">
                <thead>
                    <tr>
                        <th class="col-xs-2">#</th><th class="col-xs-8">Name</th><th class="col-xs-2">Points</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="col-xs-2">1</td><td class="col-xs-8">Mike Adams</td><td class="col-xs-2">23</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">2</td><td class="col-xs-8">Holly Galivan</td><td class="col-xs-2">44</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">3</td><td class="col-xs-8">Mary Shea</td><td class="col-xs-2">86</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">4</td><td class="col-xs-8">Jim Adams</td><td>23</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">5</td><td class="col-xs-8">Henry Galivan</td><td class="col-xs-2">44</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">6</td><td class="col-xs-8">Bob Shea</td><td class="col-xs-2">26</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">7</td><td class="col-xs-8">Andy Parks</td><td class="col-xs-2">56</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">8</td><td class="col-xs-8">Bob Skelly</td><td class="col-xs-2">96</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">9</td><td class="col-xs-8">William Defoe</td><td class="col-xs-2">13</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">10</td><td class="col-xs-8">Will Tripp</td><td class="col-xs-2">16</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">11</td><td class="col-xs-8">Bill Champion</td><td class="col-xs-2">44</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">12</td><td class="col-xs-8">Lastly Jane</td><td class="col-xs-2">6</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <div class="panel-title" style="text-align:center;"> <font size="5" face="Georgia, serif">Vendor Margins Mismatched</font></div>
            </div>
            <table class="table table-fixed" id="example2">
                <thead>
                    <tr>
                        <th class="col-xs-2">#</th><th class="col-xs-8">Name</th><th class="col-xs-2">Points</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="col-xs-2">1</td><td class="col-xs-8">Mike Adams</td><td class="col-xs-2">23</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">2</td><td class="col-xs-8">Holly Galivan</td><td class="col-xs-2">44</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">3</td><td class="col-xs-8">Mary Shea</td><td class="col-xs-2">86</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">4</td><td class="col-xs-8">Jim Adams</td><td>23</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">5</td><td class="col-xs-8">Henry Galivan</td><td class="col-xs-2">44</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">6</td><td class="col-xs-8">Bob Shea</td><td class="col-xs-2">26</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">7</td><td class="col-xs-8">Andy Parks</td><td class="col-xs-2">56</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">8</td><td class="col-xs-8">Bob Skelly</td><td class="col-xs-2">96</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">9</td><td class="col-xs-8">William Defoe</td><td class="col-xs-2">13</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">10</td><td class="col-xs-8">Will Tripp</td><td class="col-xs-2">16</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">11</td><td class="col-xs-8">Bill Champion</td><td class="col-xs-2">44</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">12</td><td class="col-xs-8">Lastly Jane</td><td class="col-xs-2">6</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <div class="panel-title" style="text-align:center; "><font size="5" face="Georgia, serif"> Bank Settlement Pending </font></div>
            </div>
            <table class="table table-fixed" id="example1">
                <thead>
                    <tr>
                        <th class="col-xs-2">#</th><th class="col-xs-8">Name</th><th class="col-xs-2">Points</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="col-xs-2">1</td><td class="col-xs-8">Mike Adams</td><td class="col-xs-2">23</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">2</td><td class="col-xs-8">Holly Galivan</td><td class="col-xs-2">44</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">3</td><td class="col-xs-8">Mary Shea</td><td class="col-xs-2">86</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">4</td><td class="col-xs-8">Jim Adams</td><td>23</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">5</td><td class="col-xs-8">Henry Galivan</td><td class="col-xs-2">44</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">6</td><td class="col-xs-8">Bob Shea</td><td class="col-xs-2">26</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">7</td><td class="col-xs-8">Andy Parks</td><td class="col-xs-2">56</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">8</td><td class="col-xs-8">Bob Skelly</td><td class="col-xs-2">96</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">9</td><td class="col-xs-8">William Defoe</td><td class="col-xs-2">13</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">10</td><td class="col-xs-8">Will Tripp</td><td class="col-xs-2">16</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">11</td><td class="col-xs-8">Bill Champion</td><td class="col-xs-2">44</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">12</td><td class="col-xs-8">Lastly Jane</td><td class="col-xs-2">6</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="panel panel-primary filterable">
            <div class="panel-heading">
                <div class="panel-title" style="text-align:center;"> <font size="5" face="Georgia, serif"> Loss Transactions</font></div>

            </div>
            <table class="table table-fixed" id="example">
                <thead>
                    <tr>
                        <th class="col-xs-2">#</th><th class="col-xs-8">Name</th><th class="col-xs-2">Points</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="col-xs-2">1</td><td class="col-xs-8">Mike Adams</td><td class="col-xs-2">23</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">2</td><td class="col-xs-8">Holly Galivan</td><td class="col-xs-2">44</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">3</td><td class="col-xs-8">Mary Shea</td><td class="col-xs-2">86</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">4</td><td class="col-xs-8">Jim Adams</td><td>23</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">5</td><td class="col-xs-8">Henry Galivan</td><td class="col-xs-2">44</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">6</td><td class="col-xs-8">Bob Shea</td><td class="col-xs-2">26</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">7</td><td class="col-xs-8">Andy Parks</td><td class="col-xs-2">56</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">8</td><td class="col-xs-8">Bob Skelly</td><td class="col-xs-2">96</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">9</td><td class="col-xs-8">William Defoe</td><td class="col-xs-2">13</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">10</td><td class="col-xs-8">Will Tripp</td><td class="col-xs-2">16</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">11</td><td class="col-xs-8">Bill Champion</td><td class="col-xs-2">44</td>
                    </tr>
                    <tr>
                        <td class="col-xs-2">12</td><td class="col-xs-8">Lastly Jane</td><td class="col-xs-2">6</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $('#example,#example1,#example2,#example3').dataTable({
//        "order": [[0, "desc" ]],
        "scrollY": "200px",
        "scrollCollapse": true,
        "aoColumnDefs": [{"bSortable": false, "aTargets": [0]}],
        "pageLength": 100,
        "lengthMenu": [100, 200, 500],
    });
</script>
<script>jQuery.noConflict();</script>


