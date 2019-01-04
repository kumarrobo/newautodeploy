
<input type='hidden' id='profile_id' />
<input type='text' id='show_data' class='form-control fix-width' onkeyup='showResult(this.value)' />
<div id="livesearch"></div>

<script type="text/javascript" src="/boot/js/jquery-2.0.3.min.js"></script>
<script>

    $(document).click(function() {
        clearOptions();
    });

    function showResult(str) {

        clearOptions();
        
        if (str.length > 1) {
            $('#livesearch').html("Loading ...");
            $('#livesearch').css({'border':'1px solid #A5ACB2','width':'250px'});
                
            $.post('/accounting/test', {'str': str}, function(e) {
                var list = "";

                for (var x in e) {
                    if (e[x].length == undefined) {
                        list = list + "<div style='padding: 5px 0 0 0;'><a href='javascript:void(0)' onmouseover='this.style.textDecoration=\"underline\"' onmouseout='this.style.textDecoration=\"none\"' onclick='selectType("+ e[x].id +",\""+ e[x].name +"\");'>"+ e[x].name +"</a></div>";
                    }
                }

                if (list != '') {
                    $('#livesearch').html(list);
                    $('#livesearch').css({'width':'500px'});
                } else {
                    clearOptions ();
                }
            }, 'json');
        }
            
        return;
    }

    function clearOptions () {
        $('#livesearch').html('');
        $('#livesearch').css({'border':'0px'});
    }

    function selectType (id, name) {
        $('#profile_id').val(id);
        $('#show_data').val(name);
        
        clearOptions();
    }

</script>