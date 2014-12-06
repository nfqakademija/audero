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

$('#show_more').click(function() {
   $('.additional_wish').toggle();
});