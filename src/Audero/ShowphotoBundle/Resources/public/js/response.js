$(function () {
    var options = {
        beforeSubmit: function () {
            $('#upload_message').text('Uploading...')
        },
        success: function (data) {
            if (data.status == 'success') {
                $("#upload_dialog").dialog("close");
                $('#upload_dialog_opener').hide();
            } else {
                $('#errors').text(data.message);
            }
            $('#upload_message').text('');
        },
        resetForm: true        // reset the form after successful submit
    };

    $('form[name="photo_response_url"]').ajaxForm(options);
    $('form[name="photo_response_file"]').ajaxForm(options);
});

/*Dialog setup*/
$(function () {
    $("#upload_dialog").dialog({
        autoOpen: false
    });
});

/* Dialog events*/
$("#upload_dialog_opener").click(function () {
    $('#errors').text('');
    $('form[name="photo_response_url"]').resetForm();
    $('form[name="photo_response_file"]').resetForm();
    $('#upload_dialog').dialog("open");
});

$("#type_url").click(function () {
    $(this).addClass("active");
    $('#type_file').removeClass("active");
    $('#form_url').show();
    $('#form_file').hide();
});

$("#type_file").click(function () {
    $(this).addClass("active");
    $('#type_url').removeClass("active");
    $('#form_file').show();
    $('#form_url').hide();
});
