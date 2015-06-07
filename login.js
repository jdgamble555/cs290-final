/* 
 * Jonathan Gamble
 */

var debug = 0;

$(document).ready(function() {

    $('#login').button({
        icons: {
            primary: "ui-icon-extlink"
        },
        text: true
    }).click(function(e){
        
        $('#login_msg').html('');
        
        // submit form
        var data = $('#loginForm').serializeArray();
        data.push({name: 'action', value: 'login'});
        
        $.ajax({
            type: 'POST',
            data: $.param(data),
            url: 'user.php',
            error: ajaxErr,
            success: loginSuccess,
            dataType: 'json'
        });
        
        // prevent regular form submit
        
        e.preventDefault();
    });

});

function loginSuccess(results) {

    // debug
    if (debug) alert(JSON.stringify(results));
    
    if (!results.success) {
        if (results.no_password) {
            $('#login_msg').html("Your username password combination is invalid!");
        }
    }
    else {
        $(location).attr('href',"index.php");
    }  
}

function ajaxErr(xhr, status, error) {
    // debug
    if(debug) alert(xhr.responseText);
}