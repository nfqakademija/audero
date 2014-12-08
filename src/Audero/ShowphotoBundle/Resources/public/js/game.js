/*Time left for request */
var timeLeft = 0;
/*Interval used to decrease time*/
var counter;

/*Notification dialog setup*/
$(function() {
    $( "#notification_dialog" ).dialog({
        autoOpen: false
    });
});

/*Initializing timeLeft*/
$(function(){
    timeLeft = parseInt((parseInt($('#timer').data('validuntil'))*1000 - new Date().getTime())/1000);
    counter = setInterval(timer, 1000);
});

function timer()
{
    timeLeft=timeLeft-1;
    if (timeLeft < 0)
    {
        clearInterval(counter);
        //counter ended, do something here
        return;
    }

    $('#timer').text(timeLeft);
}

function handleRequest(data) {
    $("#request").text(data.requestTitle);
    $("#user").text(data.username);
    /*clearing previous interval*/
    clearInterval(counter);
    /*Setting time left*/
    timeLeft = parseInt((parseInt(data.validUntil)*1000 - new Date().getTime())/1000);
    counter = setInterval(timer, 1000);
}

function handleResponse(data) {
    $( "#responses" ).append("<div class='col-md-4'><p class='text-center'><img class='img-responsive' src='" + data.photoLink + "'></p></div>");
}

function handlePlayerUpdate(data) {
    console.log(data.players);
}

/*Chat message submit handler*/
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

var conn = new ab.Session('ws://127.0.0.1:8080',
    function() {
        conn.subscribe('chat', function(topic, data) {
            $('#chat_messages').append("<span class='text-muted'>" + data.author + ': ' + '</span>' +
            data.text + "<hr class='no-padding'>");
        });
        conn.subscribe('game', function(topic, data) {
            if(data.type == 'request') {
                handleRequest(data);
            }else if(data.type == 'response') {
                handleResponse(data);
            }else if(data.type == 'player') {
                handlePlayerUpdate(data);
            }else if(data.type == 'winnerQueue') {
                console.log(data);
            }else if(data.type == "wish") {
                console.log(data);
            }


        });
    },
    function() {
        /*Notifying user about connection closure*/
        setTimeout(function(){
            var dialog = $('#notification_dialog');
            dialog.text('Connection closed');
            dialog.dialog('open');
        }, 5000);

    },
    {'skipSubprotocolCheck': true}
);