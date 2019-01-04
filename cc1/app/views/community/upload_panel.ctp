    <html>
    <head>
        <title>Community Panel</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">  
        <link rel="stylesheet" href="/boot/css/bootstrap-3.3.7.min.css">
        <link   rel="stylesheet" media="screen" href="/boot/css/bootstrap-datepicker.min.css">
        <link   rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">        
        <script type="text/javascript" src="/boot/js/jquery-3.3.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
        

        <style>
            .row{
                margin-top: 20px;
            }
            #textArea {
                display: block;
                width: 100%;
            }
        </style>
    </head>
    <body>        
        <div class="pull-right">                                                 
            <a href='/community/getFeedReport' class="btn btn-primary search"  id='view_btn' name ='view_btn' >View Panel</a>
        </div>
        <h2>Community Panel</h2>
        <br><br>        
        <form id="uploadForm_comm" name="uploadForm_comm" method="POST"  enctype="multipart/form-data" >
            <div class="row">
            <div class="col-lg-4">
                <?php $messages = $this->Session->flash(); ?>
                <?php if (!empty($messages) && preg_match('/Error/', $messages)): ?>
                    <div class="alert alert-danger">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                        <p><?php echo $messages; ?></p>
                    </div>
                <?php endif; ?>
                <?php if (!empty($messages) && preg_match('/Success/', $messages)): ?>
                    <div class="alert alert-success">
                        <a href="#" class="close" data-dismiss="alert">&times;</a>
                        <p><?php echo $messages; ?></p>
                    </div>
            <?php endif; ?>
                <label for="select_feed">Select Feed Type :</label><br>
                <select required="required" id="selectfeed" name="selectfeed" class="form-control">
                    <option value="">Select</option>
                    <?php foreach ($feedAboutval as $val): ?>
                        <option value="<?php echo $val->id; ?>"><?php echo $val->name; ?></option>
                    <?php endforeach; ?>
                </select>        
            </div>
            </div>
                
            <div class="row">
            <div class="col-lg-7">
                <label for="feed_title">Title : </label><br>
                <input type="text"  id="feed_title" name="feed_title" class="form-control" required></input>
            </div>           
            </div>
            <div class="row">
            <div class="col-lg-4">                               
                <label for="feeds_icon">Small icon :</label>
                <input type="file" id="feed_icon" name="feed_icon" class="form-control" required></input>                
                <input type="text" id="feed_val" name="feed_val" class="form-control" style="display:none"></input>
                <button type="button"  class ="btn btn-primary" id="small_ico" name="small_ico">Upload</button>                
            </div></div>            
            <div class="row">   
            <div class="col-lg-4">
                <label for="feed_type">Select representation Type :</label><br>
                <select required="required" id="feed_type" name="feed_type" class="form-control" onchange="feedRepresentation(this.value)" required>                    
                    <option value="">Select</option>
                    <?php foreach ($feedTypeval as $val): ?>
                        <option value="<?php echo $val->id; ?>"><?php echo $val->name; ?></option>
                    <?php endforeach; ?>
                </select>        
            </div></div>
            <div class="row">    
            <div class="col-lg-6 text">
                <label for="feed`_textcontent">Text URL : </label><br>
                <textarea class="form-control"  id="feed_textcontent" name="feed_textcontent"></textarea>        
           <input type="text" id="feed_blog" name="feed_blog" class="form-control" style="display:none" ></input>
                <button type="button" class="btn btn-primary" id="feed_blog_btn" name="feed_blog_btn" onclick="saveTextAsFile()">Save File</button>
            </div>               
            <div class="col-lg-6 video">
                <label for="feed_video">Video URL : </label><br>
                <input type="text"  id="feed_video" name="feed_video" class="form-control"></input>
            </div></div>                   
            <div class="row">    
            <div class="col-lg-4 image">
                <label for="feed_image">Images : </label>
                <input type="file" id="feed_image" name="feed_image" class="form-control"></input>
                <input type="text" id="feed_imageval" name="feed_imageval" class="form-control" style="display:none"></input>
                <button type="button"  class ="btn btn-primary" id="feed_img" name="feed_img">Upload</button>
            </div></div>
            <div class="row">
            <div class="col-lg-4 slide">
                <label for="feed_image1">Slide :</label>
                <input type="file" id="feed_image1" name="feed_image1" class="form-control" ></input> </br>                        
                <input type="text" id="feed_image1val" name="feed_image1val" class="form-control" style="display:none"></input>
                <button type="button"  class ="btn btn-primary" id="feed_img1" name="feed_img1">Upload</button></br>                
            </div>
                <div class="col-lg-4 slide" style="margin:26px">   
                <input type="file" id="feed_image2" name="feed_image2" class="form-control"></input> <br>                        
                <input type="text" id="feed_image2val" name="feed_image2val" class="form-control" style="display:none"></input>
                <button type="button"  class ="btn btn-primary" id="feed_img2" name="feed_img2">Upload</button></br>                
            </div></div>
            <div class="row">
            <div class="col-lg-4 slide">                
                <input type="file" id="feed_image3" name="feed_image3" class="form-control"></input> <br>
                <input type="text" id="feed_image3val" name="feed_image3val" class="form-control" style="display:none"></input>
                <button type="button"  class ="btn btn-primary" id="feed_img3" name="feed_img3">Upload</button></br>                
            </div>
            <div class="col-lg-4 slide" style="margin-left:20px">   
                <input type="file" id="feed_image4" name="feed_image4" class="form-control"></input> <br>
                <input type="text" id="feed_image4val" name="feed_image4val" class="form-control" style="display:none"></input>
                <button type="button"  class ="btn btn-primary" id="feed_img4" name="feed_img4">Upload</button><br>                
            </div></div>
            <div class="row" style="margin-top:25px">    
                <div class="col-lg-4">
                    <button type="submit" class="btn btn-primary"> Submit </button> 
                </div>
            </div>
        </form>

    </body>

</html>

<script>
    $(document).ready(function () {
        $(".slide").hide();
        $(".image").hide();
        $(".text").hide();
        $(".video").hide();
    });
    function feedRepresentation(id) {
        if(id == 1) {
            $(".video").show();                    
            $('.slide').css("display","none");           
            $(".image").css("display","none");            
            $(".text").css("display","none");
        } else if(id == 2) {
            $(".image").show();
            $('.slide').css("display","none");           
            $(".video").css("display","none");            
            $(".text").css("display","none");
        } else if(id == 3) {
            $(".slide").show();
            $('.video').css("display","none");           
            $(".image").css("display","none");            
            $(".text").css("display","none");
        } else if(id == 4) {
            $(".text").show();
            $('.slide').css("display","none");           
            $(".image").css("display","none");            
            $(".video").css("display","none");
        }
    }
           
    $(document).ready(function(){                          
        $('#small_ico').on('click', function(){
           var loader_html = $('#small_ico');
           var btn_html = $('#small_ico').html();
           var loading_gif = "<img src='/img/ajax-loader-2.gif' style='width:10px;height:10px;'></img> loading..";
           var file = $('#feed_icon').prop('files')[0];   
           var form = new FormData();
            form.append('feed_icon', file);              
            if(typeof(file) === 'undefined'){
                alert('Field shold not be empty');
            }
            else {
            $.ajax({
                url : '/community/shortImages',
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,                 
                data : form,
                dataType : 'json',
                beforeSend: function () {
                loader_html.html(loading_gif);
                },
                success: function(data){                                        
                    var data = data[0];
                    if(data.status == 'success' ){
                    $('#feed_val').val(data.url);
                    loader_html.html(btn_html);        
                    alert(data.msg);                        
                    $("#small_ico").html('Uploaded'); }                                    
                else {                    
                    loader_html.html(btn_html);        
                    alert(data.description);
                    
                } 
            },error: function(data){
                alert(data);
            }
            }); }
        });
    });


//Image URL 
    $(document).ready(function(){           
        $('#feed_img').on('click', function(){
            var loader_html = $('#feed_img');
            var btn_html = $('#feed_img').html();
            var loading_gif = "<img src='/img/ajax-loader-2.gif' style='width:10px;height:10px;'></img> loading..";
            var file = $('#feed_image').prop('files')[0];   
            var form = new FormData();
            form.append('feed_image', file); 
            if(typeof(file) === 'undefined'){
                alert('Field shold not be empty');
                }
            else {
            $.ajax({
                url : '/community/typeImages',
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,                 
                data:form,
                dataType : 'json',                
                beforeSend: function () {
                loader_html.html(loading_gif);
                },
                success: function(data){                   
                    var data = data[0];
                    if(data.status == 'success' ){
                    $('#feed_imageval').val(data.url);
                    loader_html.html(btn_html);        
                    alert(data.msg);        
                    $("#feed_img").html('Uploaded'); 
                }
                else {
                    loader_html.html(btn_html);        
                    alert(data.description);
                }
                }                
            });}
        });
    });

//Slider1 URL 
    $(document).ready(function(){           
        $('#feed_img1').on('click', function(){
           var loader_html  = $('#feed_img1');
           var btn_html     = $('#feed_img1').html();
           var loading_gif  = "<img src='/img/ajax-loader-2.gif' style='width:10px;height:10px;'></img> loading..";            
            var file = $('#feed_image1').prop('files')[0];   
            var form = new FormData();
            form.append('feed_image1', file);            
            if(typeof(file) === 'undefined'){
                alert('Field shold not be empty');
            }
            else {            
            $.ajax({
                url : '/community/sliderImages',
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,                 
                data : form,
                dataType : 'json',                
                beforeSend: function () {
                loader_html.html(loading_gif);
                },
                success: function(data){
		   var data = data[0];
                    if(data.status == 'success' ){
                    $('#feed_image1val').val(data.url);
                    loader_html.html(btn_html);        
                    alert(data.msg);        
                    $("#feed_img1").html('Uploaded'); 
                }
                else {
                    loader_html.html(btn_html);        
                    alert(data.description);
                }}
            });}
        });
    });

//Slider2 URL 
    $(document).ready(function(){           
        $('#feed_img2').on('click', function(){
           var loader_html  = $('#feed_img2');
           var btn_html     = $('#feed_img2').html();
           var loading_gif  = "<img src='/img/ajax-loader-2.gif' style='width:10px;height:10px;'></img> loading..";     
           var file = $('#feed_image2').prop('files')[0];   
           var form = new FormData();
            form.append('feed_image2', file);            
            if(typeof(file) === 'undefined'){
                alert('Field shold not be empty');
            }
            else {            
            $.ajax({
                url : '/community/sliderImages',
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,                 
                data : form,
                dataType : 'json',
                beforeSend: function () {
                loader_html.html(loading_gif);
                },
                success: function(data){
		   var data = data[0];
                    if(data.status == 'success' ){
                    $('#feed_image2val').val(data.url);
                    loader_html.html(btn_html);        
                    alert(data.msg);        
                    $("#feed_img2").html('Uploaded'); 
                }
                else {
                    loader_html.html(btn_html);        
                    alert(data.description);
                }}
        });}
        });
    });
    
//Slider3 URL  
    $(document).ready(function(){           
        $('#feed_img3').on('click', function(){
           var loader_html  = $('#feed_img3');
           var btn_html     = $('#feed_img3').html();
           var loading_gif  = "<img src='/img/ajax-loader-2.gif' style='width:10px;height:10px;'></img> loading..";               
           var file = $('#feed_image3').prop('files')[0];   
           var form = new FormData();
            form.append('feed_image3', file);            
            if(typeof(file) === 'undefined'){
                alert('Field shold not be empty');
            }
            else {            
            $.ajax({
                url : '/community/sliderImages',
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,                 
                data : form,
                dataType : 'json',
                beforeSend: function () {
                loader_html.html(loading_gif);
                },
                success: function(data){
		   var data = data[0];
                    if(data.status == 'success' ){
                    $('#feed_image3val').val(data.url);
                    loader_html.html(btn_html);        
                    alert(data.msg);        
                    $("#feed_img3").html('Uploaded'); 
                }
                else {
                    loader_html.html(btn_html);        
                    alert(data.description);
                }}
            });}
        });
    });
    
//Slider4 URL 
    $(document).ready(function(){           
        $('#feed_img4').on('click', function(){
           var loader_html  = $('#feed_img4');
           var btn_html     = $('#feed_img4').html();
           var loading_gif  = "<img src='/img/ajax-loader-2.gif' style='width:10px;height:10px;'></img> loading..";   
           var file = $('#feed_image4').prop('files')[0];   
           var form = new FormData();
            form.append('feed_image4', file);            
            if(typeof(file) === 'undefined'){
                alert('Field shold not be empty');
            }
            else {
            $.ajax({
                url : '/community/sliderImages',
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,                 
                data : form,
                dataType : 'json',
                beforeSend: function () {
                loader_html.html(loading_gif);
                },
                success: function(data){
		   var data = data[0];
                    if(data.status == 'success' ){
                    $('#feed_image4val').val(data.url);
                    loader_html.html(btn_html);        
                    alert(data.msg);        
                    $("#feed_img4").html('Uploaded'); 
                }
                else {
                    loader_html.html(btn_html);        
                    alert(data.description);
                }},
               error: function (err) {
                alert('Something went wrong');
            }   
            }); }
        });
    });  
    
 function saveTextAsFile() {     
  var textToWrite   = $('#feed_textcontent').val();             
  var loading_gif   = "<img src='/img/ajax-loader-2.gif' style='width:10px;height:10px;'></img> loading..";              
  var loader_html   = $('#feed_blog_btn');
  var btn_html     = $('#feed_blog_btn').html();

            $.ajax({
                url : '/community/bloghtml',
                type: 'POST',                            
                dataType : 'json',
                data : {'feed_blog' : textToWrite},
                beforeSend: function () {
                loader_html.html(loading_gif);
                },                
                success: function(data){                    
                 if(data[0].status == 'success' ){                        
                    $('#feed_blog').val(data[0].url);
                    loader_html.html(btn_html);        
                    alert(data[0].msg);        
                    $("#feed_blog_btn").html('Uploaded'); 
                 }
                else {                    
                    alert(data[0].description);
                    loader_html.html(btn_html);        
                }}
            });             
             };
             
             
    $('#uploadForm_comm').on('submit',function(event){
         event.preventDefault();
         var formData = $('form#uploadForm_comm').serializeArray();
        
        $.ajax({
            url: '/community/uploadPanel',
            type: "POST",
            dataType: "json",
            data: formData,
            
            success: function (data) {                                                              
               if(data.status == 'success'){
                    alert(data.msg);                                             
                    location.reload();
                }else{
                    alert(data.description);
                }    
            },
           error: function (err) {
           console.log(err);
            }
        });       
     });             
</script>