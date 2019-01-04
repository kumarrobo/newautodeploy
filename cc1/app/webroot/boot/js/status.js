
function checkSimStatus(id,vendor,ele){
                            $("#ele").prop("disabled",true);
                            parentdiv=$(ele).parent();  
                                var loader = "<center><i class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></i></center>";
                                $(ele).remove();
                                $(parentdiv).html(loader);
    
                                var url = '/sims/checkSimStatus';
                                var params = {'device' : id,'vendor': vendor};
                                $.ajax({
                                        type:"POST",
                                        url:url,
                                        data:params,
                                        success:function(data)
                                        {
                                        //data=$.parseJSON(data);
                                        if(data=='success')
                                        {
                                            html = "<center><i class='glyphicon glyphicon-ok text-success'></i></center>"; 
                                        }
                                        else if(data=='failure')
                                        {
                                            html ="<center><i class='glyphicon glyphicon-remove text-danger'></i></center>";
                                        }
                                        else
                                        {
                                            html ="<center><span>"+data+"</span><button class='btn btn-default btn-xs' onclick=\"checkSimStatus('"+id+"','"+vendor+"',this)\">Status</button></center>";
                                        }
                                        $(ele).remove();
                                        $(parentdiv).html(html);
                                        },
                                        error:function()
                                        {
                                            alert("Error");
                                        }
                                });
	}   

function checkNegDiff(id,vendor,ele){
                            $("#ele").prop("disabled",true);
                            parentdiv=$(ele).parent();  
                            var loader = "<center><i class='glyphicon glyphicon-refresh glyphicon-refresh-animate'></i></center>";
                            $(ele).remove();
                            $(parentdiv).html(loader);

                            var url = '/sims/checkNegDiff';
                            var params = {'device' : id,'vendor': vendor};
                            $.ajax({
                                    type:"POST",
                                    url:url,
                                    data:params,
                                    success:function(data)
                                    {

                                    if(data=='success')
                                    {
                                        html ="<center><i class='glyphicon glyphicon-ok text-success'></i></center>"; 
                                    }
                                    else
                                    {
                                        html ="<span style='text-align:center;'>"+data+"</span><button class='btn btn-default btn-xs' onclick=\"checkNegDiff('"+id+"','"+vendor+"',this)\">NegDiff</button>";
                                    }
                                    $(ele).remove();
                                    $(parentdiv).html(html);
                                    },
                                    error:function()
                                    {
                                        alert("Error");
                                    }
                                });
	}
        
        function removeSim(sim_id, vendor_id) {
            
            $.post('/sims/removeSim', {device: sim_id, vendor: vendor_id}, function(e) {
                
                if(e == 'success') {
                    alert('Sim Removed');
                } else {
                    alert('Error');
                }
            });
        }

        function sendBlockSms(id,mob,vend,opr,bal) {
                var url = '/sims/sendBlockSms';
                var params = {'inv_supplier_id' : id,'mobile':mob,'vendor':vend,'operator':opr,'balance':bal};
                $.ajax({
                        type:"POST",
                        url:url,
                        data:params,
                        success:function(data)
                        {
                            data=$.parseJSON(data);
                            if(data.status=='success')
                            {
                            alert("Sms sent successfully");
                            }
                        },
                        error:function()
                        {
                            alert("Error");
                        }
                    });
        }   

        function checkBalance(id,vendor,opr) {
                var url = '/sims/checkBalance';
                var params = {'device' : id,'vendor': vendor,'opr_id':opr};
                $.ajax({
                        type:"POST",
                        url:url,
                        data:params,
                        success:function(data)
                        {
                            //data=$.parseJSON(data);
                            if(data=='success')
                            {
                            alert("Done");
                            }
                        },
                        error:function()
                        {
                            alert("Error");
                        }
                    });
        }