$(function() {
    
        $(document).on('change', '.tags', function() {

                var post_id = $(this).data('id');
                var tag_id  = $(this).find(':selected').data('tag');

                $.post('/c2d/addOrderTag', {post_id: post_id, tag_id: tag_id}, function() { alert('Order Tag Changed !!!'); });
        });

        $(document).on('click', '.addComment', function() {

                var post_id = $(this).data('id');

                var comment = prompt('Enter your comment :');

                $.post('/c2d/addComment', {post_id: post_id, comment: comment}, function() {});
        });

        $(document).on('click', '.viewComment', function() {

                var post_id = $(this).data('id');

                $.post('/c2d/viewComment', {post_id: post_id}, function(e) {

                    var str = '';
                    var i = 1;

                    $.each(e, function(key, value) {

                        str += i++ +"->  "+value['c2d_order_comments']['comment']+"\n\n";
                    });

                    if(str != '') {
                            str = "Comments Below :- \n\n"+str;
                            alert(str);
                    } else {
                            alert('No Comments');
                    }

                }, 'json');
        });
});