var offset = 10;
var category = 'newest';
var content = $('#content');

$(function() {
    $( "#notification_dialog" ).dialog({
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
    /*TODO*/
    var dislikeButton = $('.dislike_button');

    (function() {
        $.ajax({
            type: "POST",
            url: '/app_dev.php/rating/' + type,
            data: {request_slug: likeButton.data('request_slug'), response_author: likeButton.data('response_author'), rate: true}
        })
            .success(function(data){
                if(data.status == 'success') {
                    if(type == 'create') {
                        likeButton.addClass('liked');
                        dislikeButton.removeClass('disliked');
                    }else{
                        likeButton.removeClass('liked');
                    }
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

    $.ajax({
        type: "POST",
        url: '/app_dev.php/rating/' + type,
        data: {request_slug: $(this).data('request_slug'), response_author: $(this).data('response_author'), rate: false}
    })
        .success(function(data){
            var dislikeButton = $('.dislike_button');
            var likeButton = $('.like_button');
            if(data.status == 'success') {
                if(type == 'create') {
                    likeButton.removeClass('liked');
                    dislikeButton.addClass('disliked');
                }else{
                    dislikeButton.removeClass('disliked');
                }
            }
        });

    event.preventDefault();
}));

$(window).scroll(function()
{
    if($(window).scrollTop() == $(document).height() - $(window).height())
    {
        $('div#loadmoreajaxloader').show();
        $.ajax({
            url: "/app_dev.php/load/newest",
            type: 'POST',
            data: {offset: offset},
            success: function(html)
            {
                if(html)
                {
                    $("#content").append(html);
                    $('div#loadmoreajaxloader').hide();
                    offset += 5;
                }else
                {
                    $('div#loadmoreajaxloader').html('No more posts to show.');
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