var count= 30;
var counter= setInterval(timer, 1000); //1000 will run it every 1 second

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
            //$( "#request" ).text(data);
            console.log(data); count = 30;
        });
        conn.subscribe('game_response', function(topic, data) {
            // This is where you would add the new article to the DOM (beyond the scope of this tutorial)
            //$( "#responses" ).append( "<tr><td><img width=\"300px\" src=\""+ data + "\"></td></tr>" );
            console.log('New response');
        });
    },
    function() {
        console.warn('WebSocket connection closed');
    },
    {'skipSubprotocolCheck': true}
);