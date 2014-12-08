$(function() {
    $( "#notification_dialog" ).dialog({
        autoOpen: false
    });
});

$('#like_button').click(function(){
    var type = 'create';
    if($(this).hasClass('liked')) {
        type = 'remove';
    }
    $.ajax({
        type: "POST",
        url: '/app_dev.php/rating/' + type,
        data: {request_slug: $(this).data('request_slug'), response_author: $(this).data('response_author'), rate: true}
    })
        .success(function(data){
            var button = $('#like_button');
            console.log(data);
            if(data.status == 'success') {
                if(type == 'create') {
                    button.addClass('liked');
                }else{
                    button.removeClass('liked');
                }
            }
        })
});

$('#dislike_button').click(function(){
    var type = 'create';
    if($(this).hasClass('disliked')) {
        type = 'remove';
    }
    $.ajax({
        type: "POST",
        url: '/app_dev.php/rating/' + type,
        data: {request_slug: $(this).data('request_slug'), response_author: $(this).data('response_author'), rate: false}
    })
        .success(function(data){
            var button = $('#dislike_button');
            console.log(data);
            if(data.status == 'success') {
                if(type == 'create') {
                    button.addClass('disliked');
                }else{
                    button.removeClass('disliked');
                }
            }
        })
});

var conn = new ab.Session('ws://127.0.0.1:8080',
    function() {
        conn.subscribe('rating', function(topic, data) {
            console.log(data);
        });
    },
    function() {
        console.warn('WebSocket connection closed');
    },
    {'skipSubprotocolCheck': true}
);