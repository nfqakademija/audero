var count= 30;
var counter= setInterval(timer, 1000); //1000 will run it every 1 second

$('form[name="showphoto_chat"]').submit(function( event ) {
    $.ajax( {
        type: "POST",
        url: $(this).attr('action'),
        data: $(this).serialize(),
        success: function(data) {
            data = JSON.parse(data);
            if(data.status == 'failure') {
                alert(data);
            }
        }
    } );

    $('#showphoto_chat_text').val('');

    event.preventDefault();
});

$("#wishes > p > input").blur(function() {

    var type = 'update';
    if($(this).val() == '')
        type = 'delete';

    $.ajax({
        type: "POST",
        url: "/app_dev.php/wish/" + type,
        data: { title: $(this).val() , position: $(this).data('position') }
    })
        .success(function(data) {
            data = JSON.parse(data);
            if(data.status == "failure") {
                $('#wish-list-errors').html(data.message);
            }else{
                $('#wish-list-errors').html('');
            }
        });
});

/*
$('form[name="audero_showphotobundle_photoresponse"]').submit(function( event ) {
    $.ajax( {
        type: "POST",
        url: $(this).attr('action'),
        data: $(this).serialize(),
        success: function(data) {
            if(data.status == 'success') {
                console.log("good");
            }
        }
    } );

    event.preventDefault();
});
*/

function timer()
{
    count=count-1;
    if (count < 0)
    {
        clearInterval(counter);
        //counter ended, do something here
        return;
    }

    document.getElementById("timer").innerHTML=count; // watch for spelling
}

var conn = new ab.Session('ws://127.0.0.1:8080',
    function() {
        conn.subscribe('chat', function(topic, data) {
            $('#chat_messages').append("<span class='text-muted'>" + data.user + ': ' + '</span>' +
            data.text + "<hr class='no-padding'>");
        });
        conn.subscribe('game_request', function(topic, data) {
            // This is where you would add the new article to the DOM (beyond the scope of this tutorial)
            $( "#request" ).text(data.requestTitle);
            $( "#user" ).text(data.username);
            $("#timer").text(data.validUntil);

            console.log(parseInt((parseInt(data.validUntil)*1000 - new Date().getTime())/1000));

        });
        conn.subscribe('game_response', function(topic, data) {
            $( "#responses" ).append("<div class='col-md-4'><p class='text-center'><img class='img-responsive' src='" + data.photoLink + "'></p></div>");
        });
    },
    function() {
        console.warn('WebSocket connection closed');
    },
    {'skipSubprotocolCheck': true}
);