$('form[name="showphoto_chat"]').submit(function( event ) {
    $.ajax( {
        type: "POST",
        url: $(this).attr('action'),
        data: $(this).serialize(),
        success: function(data) {
            data = JSON.parse(data);
            if(data.status == 'failure') {
                alert(data.message);
            }
        }
    } );

    $('#showphoto_chat_text').val('');

    event.preventDefault();
});
