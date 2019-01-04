<div class="modal fade" id="sendSmsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Send message</h4>
      </div>
        <form name="sendsmsForm" >
                <div class="modal-body">

                    <div class="form-group">
                      <label for="recipient-name" class="control-label">Recipient:</label>
                      <input type="text" class="form-control" id="recipient-name" name="recipient">
                    </div>
                    <div class="form-group">
                      <label for="message-text" class="control-label">Message:</label>
                      <textarea class="form-control" id="message-text" name="message"></textarea>
                    </div>

                    <input type="hidden" name="simid" />
                    <input type="hidden" name="vendorid" />
                    
                </div>
                <div class="modal-footer">
                <input name="submit" type="submit" value="Submit" class="btn btn-default btn-primary btn-sm">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                </div>
         </form>
    </div>
  </div>
</div>



<div class="modal fade" id="sendAtModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Run At Command</h4>
      </div>
        <form name="sendatForm" >
                <div class="modal-body">

                    <div class="form-group">
                      <label for="recipient-name" class="control-label">Time:</label>
                      <input type="text" class="form-control" id="cmd_time" name="cmd_time">
                    </div>
                    <div class="form-group">
                      <label for="message-text" class="control-label">Command:</label>
                      <textarea class="form-control" id="cmd" name="cmd"></textarea>
                    </div>

                    <input type="hidden" name="simid" />
                    <input type="hidden" name="vendorid" />
                    
                </div>
                <div class="modal-footer">
                <input name="submit" type="submit" value="Submit" class="btn btn-default btn-primary btn-sm">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                </div>
         </form>
    </div>
  </div>
</div>


<div class="modal fade" id="sendUssdModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Run USSD Command</h4>
      </div>
        <form name="sendussdForm" >
                <div class="modal-body">
                    <div class="form-group">
                      <label for="recipient-name" class="control-label">Time:</label>
                      <input type="text" class="form-control" id="ussd_time" name="ussd_time">
                    </div>
                    <div class="form-group">
                      <label for="message-text" class="control-label">USSD:</label>
                      <textarea class="form-control" id="ussd" name="ussd"></textarea>
                    </div>

                    <input type="hidden" name="simid" />
                    <input type="hidden" name="vendorid" />
                    
                </div>
                <div class="modal-footer">
                <input name="submit" type="submit" value="Submit" class="btn btn-default btn-primary btn-sm">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                </div>
         </form>
    </div>
  </div>
</div>


<div class="modal fade" id="sendResetModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Run Reset Command</h4>
      </div>
        <form name="sendresetForm" >
                <div class="modal-body">

                    
                    <input type="hidden" name="simid" />
                    <input type="hidden" name="vendorid" />
                    
                </div>
                <div class="modal-footer">
                <input name="submit" type="submit" value="Submit" class="btn btn-default btn-primary btn-sm">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                </div>
         </form>
    </div>
  </div>
</div>

<div class="modal fade" id="downloadTransactionsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Download Last Transactions</h4>
      </div>
        <form name="sendDownloadTransactionsForm" >
                <div class="modal-body">
                    <input type="text" class="form-control" name="transactionDate" style="width: 170px"  value="<?php echo date('Y-m-d'); ?>" />
                        <input type="hidden" name="address" />
                </div>
                <div class="modal-footer">
                <input name="submit" type="submit" value="Download" class="btn btn-default btn-primary btn-sm">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                </div>
         </form>
    </div>
  </div>
</div>

<div class="modal" id="editclosingmodal">
  <div class="modal-dialog" style="width: 300px;">
    <div class="modal-content">
      <div class="modal-header">
          <h4 class="modal-title">Edit Closing</h4>
      </div>
      <div class="modal-body">
          <p>Old Closing Amount :  <span class="oldclosinglabel"></span></p>
       
        <p>Enter New Closing Amount</p>
        <input type="hidden" name="inp_device_id" id="inp_device_id" value="" />
        <input type="hidden" name="inp_date" id="inp_date" value="" />
        <input type="hidden" name="inp_oldclosing" id="inp_oldclosing" value="" />
        <input type="hidden" name="inp_vendorid" id="inp_vendorid" value="" />
        <input type="hidden" name="inp_mobile" id="inp_mobile" value="" />
        
        <input type="text" id="txt_closing" name="txt_closing" value=""  class="form-control form-inp" style="width: 125px;"/>
       
      </div>
      <div class="modal-footer">
          <div id="updateClosingloadingbar" class="pull-left" style="display: none"><img src="/boot/images/reload.gif"><i> Please  Wait ....</i></div>
          <button class="btn btn-sm btn-default btn-primary" id="updateclosingbtn">Update</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal" id="editbalancemodal">
  <div class="modal-dialog" style="width: 300px;">
    <div class="modal-content">
      <div class="modal-header">
          <h4 class="modal-title">Edit balance</h4>
      </div>
      <div class="modal-body">
          <p>Old Balance :  <span class="oldbalancelabel"></span></p>
       
        <p>Enter New Balance</p>
        <input type="hidden" name="inp_oldbalance" id="inp_oldbalance" value="" />
        <input type="hidden" name="inpbalance_vendorid" id="inpbalance_vendorid" value="" />
        <input type="hidden" name="inpbalance_parbal" id="inpbalance_parbal" value="" />
        <input type="hidden" name="inpbalance_mobile" id="inpbalance_mobile" value="" />
        <input type="hidden" name="inpbalance_simid" id="inpbalance_simid" value="" />
        
        <input autocomplete="off" type="text" id="txt_balance" name="txt_balance" value=""  class="form-control form-inp" style="width: 125px;"/>
       
      </div>
      <div class="modal-footer">
          <div id="updateBalanceloadingbar" class="pull-left" style="display: none"><img src="/boot/images/reload.gif"><i> Please  Wait ....</i></div>
          <button class="btn btn-sm btn-default btn-primary" id="updatebalancebtn">Update</button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal" id="rechargeType">
  <div class="modal-dialog" style="width: 300px;">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Recharge Type</h4>
        </div>
        <form name="rechargeType">
            <div class="modal-body">
                <input type="radio" name="recharge_type" id="rt_1" value="1" /> APP<br />
                <input type="radio" name="recharge_type" id="rt_3" value="3" /> USSD<br />
                <input type="radio" name="recharge_type" id="rt_2" value="2" /> SMS<br />
                <input type="radio" name="recharge_type" id="rt_4" value="4" /> WEB<br />
            </div>
            <input type="hidden" name="simid" />
            <input type="hidden" name="vendorid" />
            <input type="hidden" name="operatorid" />
            <div class="modal-footer">
                <div id="updateBalanceloadingbar" class="pull-left" style="display: none"><img src="/boot/images/reload.gif"><i> Please  Wait ....</i></div>
                <button class="btn btn-sm btn-default btn-primary" id="updatebalance">Submit</button>
                <button type="button" class="btn btn-default btn-sm clo" data-dismiss="modal">Close</button>
            </div>
        </form>
    </div>
  </div>
</div>

<div class="modal" id="addcommentmodal">
    <div class="modal-dialog" style="width: 300px;">
        <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title">Add Comment</h4>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-lg-12" id="loadcomments">

                  </div>
              </div>
              <input type="hidden" name="opr_id" id="opr_id" value="" />
              <input type="hidden" name="vendor_id" id="vendor_id" value="" />
              <input type="hidden" name="scid" id="scid" value="" />
              <input type="hidden" name="commentdate" id="commentdate" value="" />
              <textarea  name="comment" id="comment"></textarea>
          </div>
          <div class="modal-footer">
              <div id="simcommentmsg" style="text-align: left"></div>
              <button class="btn btn-sm btn-default btn-primary" id="addcommentbtn">Update</button>
            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
          </div>
        </div>
    </div>
</div>