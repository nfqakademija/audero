var offset = 10;
var category = 'newest';
var content = $('#content');
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

content.on('click', '.like_button', (function(event){
    var type = 'create';
    if($(this).hasClass('liked')) {
        type = 'remove';
    }
    var likeButton = $(this);

    (function() {
        $.ajax({
            type: "POST",
            url: '/app_dev.php/rating/' + type,
            data: {
                request_slug: likeButton.data('request_slug'),
                response_author: likeButton.data('response_author'),
                rate: true
            }
        })
            .success(function(data){
                if(data.status == 'success') {
                    if(type == 'create') {
                        likeButton.addClass('liked');
                        likeButton.parent().children('.dislike_button').removeClass('disliked');
                    }else{
                        likeButton.removeClass('liked');
                    }
                }else{
                    dialog.text(data.message);
                    dialog.dialog('open');
                }
            });
    })();


    event.preventDefault();
}));

content.on('click','.dislike_button', (function(event){
    var type = 'create';
    if($(this).hasClass('disliked')) {
        type = 'remove';
    }

    var dislikeButton = $(this);

    (function() {
        $.ajax({
            type: "POST",
            url: '/app_dev.php/rating/' + type,
            data: {
                request_slug: dislikeButton.data('request_slug'),
                response_author: dislikeButton.data('response_author'),
                rate: false
            }
        })
            .success(function (data) {
                if (data.status == 'success') {
                    if (type == 'create') {
                        dislikeButton.parent().children('.like_button').removeClass('liked');
                        dislikeButton.addClass('disliked');
                    } else {
                        dislikeButton.removeClass('disliked');
                    }
                } else {
                    dialog.text(data.message);
                    dialog.dialog('open');
                }
            });
    })();

    event.preventDefault();
}));

$(window).scroll(function()
{
    if($(window).scrollTop() == $(document).height() - $(window).height())
    {
        $('#small-ajax-loader').show();
        $.ajax({
            url: "/app_dev.php/load/" + category,
            type: 'POST',
            data: {offset: offset},
            success: function(html)
            {
                if(html)
                {
                    $("#content").append(html);
                    $('#small-ajax-loader').hide();
                    offset += 5;
                }else
                {
                    $('#small-ajax-loader').html('No more photos to show.');
                }
            }
        });
    }
});

var conn = new ab.Session('ws://127.0.0.1:8080',
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