/*Wish list update handler */
$(document).on('blur','#wishes > p > input',function() {

    var type = 'update';
    if($(this).val() == '')
        type = 'delete';

    $.ajax({
        type: "POST",
        url: "/wish/" + type,
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