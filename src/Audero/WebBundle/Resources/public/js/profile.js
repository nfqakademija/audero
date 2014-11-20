/*var collectionHolder = $('ul.wishes');*/

var max_fields      = 10; //maximum input boxes allowed
var wrapper         = $("#wishList"); //Fields wrapper
var add_button      = $(".add_field_button"); //Add button ID

var x = 0; //initlal text box count
$(add_button).click(function(e){ //on add input button click
    e.preventDefault();
    if(x < max_fields){ //max input box allowed
    x++; //text box increment
    $(wrapper).append('<li><input type="text" value="" maxlength="255" required="required" name="web_wish_list[wishes][' + x + '][title]" id="web_wish_list_wishes_'+x+'_title"><input type="number" value="" required="required" name="web_wish_list[wishes]['+ x + '][position]" id="web_wish_list_wishes_' + x + '_position"><a href="#" class="remove_field">Remove</a></li>'); //add input box
    }
    });

$(wrapper).on("click",".remove_field", function(e){ //user click on remove text
    e.preventDefault(); $(this).parent('li').remove(); x--;
    });

var conn = new ab.Session('ws://localhost:8080',
function() {
    conn.subscribe('test1Category', function(topic, data) {
        // This is where you would add the new article to the DOM (beyond the scope of this tutorial)
        console.log('naujas request' + data.title);
    });
    conn.subscribe('test2Category', function(topic, data) {
    // This is where you would add the new article to the DOM (beyond the scope of this tutorial)
    console.log('Naujas response' + data.title);
    });
    },
function() {
    console.warn('WebSocket connection closed');
    },
                {'skipSubprotocolCheck': true}
);