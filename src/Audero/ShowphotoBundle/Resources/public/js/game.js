/*Time left for request */
var timeLeft = 0;
/*Interval used to decrease time*/
var counter;
/*Notifications dialog*/
var dialog = $("#notification_dialog");
// Chat messages height hack
$('#chat_messages').css('width', '286px').css('max-height', '305px').css('overflow-x', 'visible').css('margin-bottom', '5px').css('overflow-y', 'scroll').scrollTop(1000000000);
//

/*Notification dialog setup*/
$(function() {
    dialog.dialog({
        autoOpen: false
    });
});

/*Initializing timeLeft*/
$(function(){
    timeLeft = parseInt($('#timer').data('timeleft'));
    if(timeLeft > 0) {
        counter = setInterval(timer, 1000);
    }

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
    $("#user").text(" @" + data.username);
    $('#upload_dialog_opener').show();
    $('#winners-panel').hide();
    $('#responses').html('');
    /*clearing previous interval*/
    clearInterval(counter);
    /*Setting time left*/
    timeLeft = parseInt(data.timeLeft);
    counter = setInterval(timer, 1000);
}

function handleResponse(data) {
    $( "#responses" ).prepend(
        "<div class='col-lg-4 col-md-4'><a class='thumbnail' " +
        "href='/game/" + data.requestSlug + "/" + data.author  + "' target='_blank'>" +
        "<img class='img-responsive' " +
        "src='"+ data.photoLink +
        "' alt=''></a></div>"
    );
}

function handlePlayersUpdate(data) {
    var players = data.players;
    var tag = $('#players_content');
    tag.html('');
    for (var key in players) {
        if (players.hasOwnProperty(key)) {
            tag.append("<li><a href='/user/'" + players[key] + "'>" +
            "<i class='fa fa-user'></i>" + players[key] + "</a></li>");
        }
    }
}

function handleWishesUpdate(data) {
    var tag = $('#wishes');
    tag.html('');
    var wishList = data.wishList;
    for (var key in wishList) {
        if (wishList.hasOwnProperty(key)) {
            tag.append("<p><input type='text' class='form-control' " +
            "data-position='"+ key +"'" +
            "value='" + wishList[key] + "'" +
            "placeholder='Place your wish'></p>");
        }
    }
}

function handleWinnersQueue(data) {
    var tag = $('#winners-panel-body');
    tag.html('');
    $('#winners-panel').show();

    timeLeft = parseInt(data.timeToShow);
    counter = setInterval(timer, 1000);
    var entries = data.playersData;
    for (var key in entries) {
        if (entries.hasOwnProperty(key)) {
            tag.append(
            "<tr> " +
                "<td>" + "1" + "</td>" +
                "<td>" + entries[key].username + "</td>" +
                "<td>" + entries[key].responseRate +"</td>" +
            "</tr>");
        }
    }

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
                dialog.text(data.message);
                dialog.dialog('open');
            }
        }
    } );

    $('#showphoto_chat_text').val('');

    event.preventDefault();
});

function handleChatMessage(data) {
    var tag = $('#chat_messages');
    tag.append("<span class='text-muted'>" + data.author + ': ' + '</span>' +
    data.text + "<hr class='no-padding'>");
    tag.css('width', '290px').css('max-height', '305px').css('overflow-x', 'visible').css('margin-bottom', '5px').css('overflow-y', 'scroll').scrollTop(1000000000);
}

var conn = new ab.Session('ws://vilnius2.projektai.nfqakademija.lt:12980',
    function() {
        conn.subscribe('chat', function(topic, data) {
            handleChatMessage(data);
        });
        conn.subscribe('game', function(topic, data) {
            if(data.type == 'request') {
                handleRequest(data);
            }else if(data.type == 'response') {
                handleResponse(data);
            }else if(data.type == 'player') {
                handlePlayersUpdate(data);
            }else if(data.type == 'winnerQueue') {
                handleWinnersQueue(data);
            }else if(data.type == "wish") {
                handleWishesUpdate(data);
            }
        });
    },
    function() {
        /*Notifying user about connection closure*/
        //TODO REDIRECT

    },
    {'skipSubprotocolCheck': true}
);