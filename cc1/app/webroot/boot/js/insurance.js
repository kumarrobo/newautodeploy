$(document).ready(function () {
    $('#insfrmdate, #instodate, #mfdate , #mtdate').datepicker({
        format: "yyyy-mm-dd",
        endDate: "1d",
        multidate: false,
        autoclose: true,
        todayHighlight: true
    });
    $('.tablex').dataTable({
        // "order": [[0, "desc" ]],
        "pageLength": 50,
        "lengthMenu": [[10, 50, 100, 200, 500, -1], [10, 50, 100, 200, 500, 'All']],
    });
    $('#transval').multiselect({
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        includeSelectAllOption: true
    });
        
    });    
