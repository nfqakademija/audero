var dialog = $( "#notification_dialog" );

$(function() {
    dialog.dialog({
        autoOpen: false
    });
});

function handleNewRating(data) {
    console.log(data);
    var likeBar = $(".progress-bar-success[data-request_slug='" +
    data.requestSlug + "'][data-response_author='" +
    data.responseAuthor + "']");
    var dislikeBar = $(".progress-bar-danger[data-request_slug='" +
    data.requestSlug + "'][data-response_author='" +
    data.responseAuthor + "']");

    likeBar.css('width', data.likesPercent + "%");
    var likeText = data.likes;
    if(data.likes == '0') {
        likeText = '';
    }
    likeBar.text(likeText);

    dislikeBar.css('width', data.dislikesPercent + "%");
    var dislikeText = data.dislikes;
    if(data.dislikes == '0') {
        dislikeText = '';
    }
    dislikeBar.text(dislikeText);
}

$(document).on('click', '.like_button', (function(event){
    var type = 'create';
    if($(this).hasClass('active')) {
        type = 'remove';
    }
    var likeButton = $(this);

    (function() {
        $.ajax({
            type: "POST",
            url: '/rating/' + type,
            data: {request_slug: likeButton.data('request_slug'), response_author: likeButton.data('response_author'), rate: true}
        })
            .success(function(data){
                if(data.status == 'success') {
                    if(type == 'create') {
                        likeButton.addClass('active');
                        likeButton.closest('.panel').find('.dislike_button').removeClass('active');
                    }else{
                        likeButton.removeClass('active');
                    }
                }else{
                    dialog.text(data.message);
                    dialog.dialog('open');
                }
            });
    })();

    event.preventDefault();
}));

$(document).on('click','.dislike_button', (function(event){
    var type = 'create';
    if($(this).hasClass('active')) {
        type = 'remove';
    }

    var dislikeButton = $(this);

    (function() {
        $.ajax({
            type: "POST",
            url: '/rating/' + type,
            data: {
                request_slug: dislikeButton.data('request_slug'),
                response_author: dislikeButton.data('response_author'),
                rate: false
            }
        })
            .success(function (data) {
                if (data.status == 'success') {
                    if (type == 'create') {
                        dislikeButton.closest('.panel').find('.like_button').removeClass('active');
                        dislikeButton.addClass('active');
                    } else {
                        dislikeButton.removeClass('active');
                    }
                } else {
                    dialog.text(data.message);
                    dialog.dialog('open');
                }
            });
    })();

    event.preventDefault();
}));

var conn = new ab.Session('ws://vilnius2.projektai.nfqakademija.lt:12980',
    function() {
        conn.subscribe('rating', function(topic, data) {
            this.handleNewRating(data);
        });
    },
    function() {
        console.log("Connection could not be established");

    },
    {'skipSubprotocolCheck': true}
);