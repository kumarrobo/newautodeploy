<a class="btn btn-default btn-xs" data-toggle="collapse" data-target="#togglesearchform">Search &raquo;</a>
<a class="btn btn-default btn-xs" onclick="showAllSims();">Show All</a>
<a class="btn btn-default btn-xs resetbtn" >Hide All</a>

<div id="togglesearchform" class="collapse">
    <fieldset>

        <!-- Form Name -->
        <legend>Search</legend>

        <div class="row">
            
        <div class="col-lg-12">
            
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="col-md-6 control-label" for="modems">Select Modems</label>
                                <div class="col-md-6">
                                    <?php $params = explode(',', $this->params['url']['modem_id']); ?>
                                    <select id="modem_id" name="modem_id[]" class="" >
                                            <?php foreach ($modemDropdownList as $mD): ?>
                                                <option value="<?php echo $mD['vendors']['id']; ?>" <?php if (in_array($mD['vendors']['id'], $params)): echo "selected";
                                            endif; ?> ><?php echo $mD['vendors']['company']; ?></option>
                                            <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>    

                        <div class="col-lg-4 col-lg-offset-2">
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="searchbydate">Date</label>  
                                <div class="col-md-6">
                                    <input id="selectdate" name="searchbydate" type="text" placeholder="Date" class="form-control input-md input-sm"  value="<?php echo !empty($this->params['url']['searchbydate'])?$this->params['url']['searchbydate']:date('Y-m-d'); ?>"  />

                                </div>
                            </div>
                        </div>
            
                        <div class="col-lg-1">
<!--                            <input type="button" class="btn btn-default btn-primary btn-sm" value="Search"  />-->
                        </div>
                     
           
           </div>  
           
           </div>   
           
          </fieldset>


           
    <form class="form-horizontal" method="GET" action="/sims" id="filterform">
    <fieldset>

        <!-- Form Name -->
        <legend>Filter</legend>
        
        <div class="row">
          
                <div class="col-lg-12">

                    <div class="col-lg-4">  
<!--                        <input type="hidden" name="searchbydate" id="searchbydate" value="<?php // if(isset($_GET['searchbydate'])): echo $_GET['searchbydate'];  else: echo date('Y-m-d');  endif;?>" />    -->
                        <input type="hidden" name="mode" value="search" />
                        <!-- Select Basic -->
                        <div class="form-group">
                            <label class="col-md-6 control-label" for="operator">Select Operator</label>
                            <div class="col-md-6">
                                <select id="operators" name="operators" class="form-control input-sm">
                                    <option value="">Select</option>
                                    <?php foreach ($operators as $key => $operator): ?>
                                        <option value="<?php echo $operator['products']['id']; ?>"><?php echo $operator['products']['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>






                        <!-- Select Basic -->
                        <div class="form-group">
                            <label class="col-md-6 control-label" for="color">Color</label>
                            <div class="col-md-6">
                                <select id="color" name="color" class="form-control input-sm">
                                    <option value="">Select</option>
                                    <option value="#c73525">Not detected (More then 3000)</option>
                                    <option value="transparent">Not detected (Less then 3000)</option>
                                    <option value="#8c65e3">Detected & working (Less then 3000)</option>
                                    <option value="#99ff99">Detected & Working (More then 3000)</option>
                                    <option value="#f6ff00">Detected but Not working</option>
                                    <option value="#c0c0c0"> Detected unused Roaming sim</option>
                                    <option value="#ffa500"> Not Detected unused Roaming sim</option>
                                    <option value="#19ffd1">Unused balance sim from last 36 hours</option>
                                    <option value="#99ffcc">Blocked Sims</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-6 control-label" for="color">Diff</label>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control input-sm" placeholder="From" name="diffFrom" id="diffFrom">
                                    <span class="input-group-addon smallheight">to</span>
                                    <input type="text" class="form-control input-sm" placeholder="To" name="diffTo" id="diffTo">
                                </div>
                            </div>
                        </div>
                        
                </div>




                    <!-- Text input-->
                    <div class="col-lg-4">  

                        <div class="form-group">
                            <label class="col-md-6 control-label" for="saleamtFrom">Sale Amt</label>  
                            <div class="col-md-6">
                                <input id="saleamtFrom" name="saleamtFrom" type="text" placeholder="From" class="form-control input-md input-sm">

                            </div>
                        </div>




                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-md-6 control-label" for="saleamtTo"></label>  
                            <div class="col-md-6">
                                <input id="saleamtTo" name="saleamtTo" type="text" placeholder="To" class="form-control input-md input-sm">

                            </div>
                        </div>
                        
                        
                         <div class="form-group">
                            <label class="col-md-6 control-label" for="serverdiffcheckbox">(ServerDiff-Diff)[-100 to +100]</label>
                            <div class="col-md-6">
                                <input type="checkbox" id="serverdiffcheckbox" name="serverdiffcheckbox" />
                            </div>
                        </div>

                    </div>



                    <div class="col-lg-4">  

                        <div class="form-group">
                            <label class="col-md-6 control-label" for="vendorname">Select Vendor</label>
                            <div class="col-md-6">
                                <input id="suppliername" name="suppliername" type="text" placeholder="Supplier Name" class="form-control input-md input-sm">
                            </div>
                        </div>



                        <!-- Text input-->
                        <div class="form-group">
                            <label class="col-md-6 control-label" for="mobile">Mobile</label>  
                            <div class="col-md-6">
                                <input id="mobile" name="searchbymobile" type="text" placeholder="Last 4 digit" class="form-control input-md input-sm">

                            </div>
                        </div>

                    </div>



                </div>
           
        </div>
        
      
        
        <div class="row" style="margin-top: 20px;">
             <div class="col-lg-4">  
<!--                 <input type="button" class="btn btn-default btn-filter btn-sm" value="Filter" id="filterbtn" />-->
                 <input type="submit" class="btn btn-default btn-filter btn-sm" value="Search" />
<!--                 <input type="button" class="btn btn-default  btn-sm resetbtn" value="Reset" />-->
                 <a href="/sims" class="btn btn-default btn-sm">Reset</a>
             </div>    
        </div>
        
      <div class="container" style="width: 400px"> 
        <div class="row" >
            <div class="col-lg-10 col-lg-offset-1" id="progressdiv" style="display: none">
                <img src="/boot/images/loading.gif" />
                <span></span>
            </div>
        </div>
    </div>
          
          </fieldset>
</form>


</div>

  