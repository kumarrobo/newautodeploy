<script type="text/javascript" src="/boot/js/jquery.multiple.select.js"></script>
<script type="text/javascript" src="/min/b=js&f=lib/jquery.autocomplete.min.js"></script> 
<link rel="stylesheet" media="screen" href="/boot/css/multiple-select.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<style>
    .autocomplete-suggestions {
	border: 1px solid #999;
	background: #fff;
	cursor: default;
	overflow: auto;
}

.autocomplete-suggestion {
	padding: 10px 5px;
	font-size: 1.0em;
	white-space: nowrap;
	overflow: hidden;
}

.autocomplete-selected {
	background: #f0f0f0;
}

.autocomplete-suggestions strong {
	font-weight: normal;
	color: #3399ff;
}
</style>

<style>div#listusers_filter{float: right;font-size: 12px;}div#listusers_length,div#listusers_info{font-size: 12px;}select.input-sm{height:22px;padding:0px;}</style>
<div class="col-lg-12">
        <div class="panel panel-pay1">
            <div class="panel panel-heading">Module Access</div>
            <div class="panel panel-body">
            <form id="reportform" class="form-inline" >
                    <div class=" col-md-2"><label> Search Module : </label></div>
                    <div class="col-md-4">
                        <input type="text" class="form-control autocomplete" id="module" name="module_name" placeholder="Enter Module">
                        <input type="hidden" class="form-control autocomplete" id="module-id" name="module_id">
                    </div>

            </form>

                    <table class="table table-condensed table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Group Name</th>
                            </tr>
                        </thead>
                        <tbody id="groupsData">
                        
                        </tbody>
                    </table>
            </div>
        </div>
</div>

<script>
    $(document).ready(function(){        
        var moduleid = '';
        $(function(){
            var data = <?php echo $moduleList;?>;
            $('.autocomplete').autocomplete({
                      lookup: data,
                      onSelect: function (suggestion) {
                            $('#module-id').val(suggestion.data);
                            moduleid = $('#module-id').val();
                            moduleGroup(moduleid);
                    }
             });
        });
        
        function moduleGroup(moduleid){
            jQuery.ajax({
                            type : 'POST',
                            url : '/acl/moduleGroup',
                            dataType : "json",
                            data : {
                                search : 1,
                                module : moduleid
                            },
                            success: function(data){
                                
                                var tabledata = '';
                                jQuery.each(data, function (key, value)
                                {
                                    tabledata += '<tr>';
                                    tabledata += '<td>'+value.groups.id+'</td>';
                                    tabledata += '<td>'+value.groups.name+'</td>';
                                    tabledata += '<tr>';
                                });
                                $('#groupsData').html(tabledata);
                            }
                });
        }
    });
</script>
