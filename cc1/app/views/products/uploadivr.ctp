<?php echo $this->element('product_sidebar'); ?>
<style>
    .btn-primary {
        padding: 3.5px 16px;
    }
</style>
<div class="padding-top-10" style="float:left; width: 75%; margin-left: 20px;">
    <?php echo $this->Session->flash('form1'); ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            Upload IVR MP3 File
        </div>
        <div class="container" style="margin:10px">
            <form id="vendor_operator_map" class="form" role="form" action="" method="post" enctype="multipart/form-data">
                <div class="form-group" style="width:50%">
                    <label for="ivrfile">IVR MP3 File (silence) </label>
                    <input type="file" name="ivrfile" id="ivrfile"  class="form-control" accept=".mp3"/>
                </div>
                <div class="form-group" style="width:50%">
                    <label for="ivrfile2">IVR MP3 File (silence1) </label>
                    <input type="file" name="ivrfile2" id="ivrfile2"  class="form-control" accept=".mp3"/>
                </div>
                <div class="form-group" style="width:12%">
                    <label for="ext_num"> Extension Number </label>
                    <input type="input"  name="ivrnumber" id="ivrnumber" class="form-control" onfocusout="extensionMap()"  />
                </div>
                <div class="form-group" style="width:12%">
                    <label for="ext_type">Extension Type</label>
                    <input type="input" name="ivretype" id="ivretype" class="form-control" readonly />                    
                </div>        
                <button type="submit" name="activate_ivr" id ="activate_ivr" class="btn-lg btn-primary" value = "1">Active</button>
                <button type="submit" name="activate_ivr" id ="activate_ivr" class="btn-lg btn-primary" value = "2">De-active</button>
            </form>

        </div>
    </div>
</div>

<script>
    function extensionMap() {
        var num = ($('#ivrnumber').val()).trim();
        var availnum = ["2202", "2203", "2206", "2220", "2244", "2265", "2277", "2288", "2295", "2297", "2298", "2299"];
        var confnum = availnum.indexOf(num);

        if(confnum === -1) {
            alert("Match Not Found");
        } else {
            switch (confnum) {

                case 0:
                    var extnum = "2202";
                    var exttype = "614";
                    break;
                case 1:
                    var extnum = "2203";
                    var exttype = "606";
                    break;
                case 2 :
                    var extnum = "2206";
                    var exttype = "607";
                    break;
                case 3 :
                    var extnum = "2220";
                    var exttype = "604";
                    break;
                case 4 :
                    var extnum = "2244";
                    var exttype = "600";
                    break;
                case 5 :
                    var extnum = "2265";
                    var exttype = "609";
                    break;
                case 6 :
                    var extnum = "2277";
                    var exttype = "611";
                    break;
                case 7 :
                    var extnum = "2288";
                    var exttype = "600";
                    break;
                case 8 :
                    var extnum = "2295";
                    var exttype = "603";
                    break;
                case 9 :
                    var extnum = "2297";
                    var exttype = "602";
                    break;
                case 10 :
                    var extnum = "2298";
                    var exttype = "604";
                    break;
                case 11 :
                    var extnum = "2299";
                    var exttype = "612";
                    break;
                default:
                    alert("Null");
            }
        }
        if(exttype != 'undefined' || exttype != 'Null') {
            document.getElementById("ivretype").value = exttype;
        }
        else {
            alert("Kindly put correct Extension Number");
        }
    }

</script>
