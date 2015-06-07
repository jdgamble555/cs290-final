/* 
 * Jonathan Gamble
 */

var debug = 0;

$(document).ready(function() {
    
    // check password is > 6 characters
    
    $('#password').keyup(function() {
        var password = $(this).val();
        if (!password) {
            $('#pass_msg').html('You must enter a password!');
        }
        else {
            $('#pass_msg').html(password.length > 5
                ? ''
                : 'Password must be at least 6 characters.'
            );
        }
    });
    
    // check that passwords match
    
    $('#confirm_password').keyup(function() {
        if (!$(this).val()) {
            $('#confirm_pass_msg').html('You must confirm your password!');
        }
        else {
            var password = $('#password').val();
            $('#confirm_pass_msg').html(password == $(this).val()
                ? ''
                : 'Passwords do not match!'
            );
        }
    });
    
    // check for valid username / email address
    
    $('#email').on('keyup keypress change', function() {
        
        if (!$(this).val()) {
            $('#email_msg').html('You must enter an email address!');
        }
        else {
            var re = /^\S+@\S+\.\S+$/;

            if (!re.exec($(this).val())) {
                $('#email_msg').html('Invalid Email Address!');
            }
            else {
                $.ajax({
                    type: 'POST',
                    data: {
                        action: 'valid_email',
                        email: $(this).val()
                    },
                    url: 'user.php',
                    error: ajaxErr,
                    success: function(results) {

                        if (debug) alert(JSON.stringify(results));

                        $('#email_msg').html(results.taken
                            ? 'Email Address is already taken!'
                            : ''
                        );

                    },
                    dataType: 'json'
                });
            }
        }
    });
    
    // set button properties
    
    $('#join').button({
        icons: {
            primary: "ui-icon-contact"
        },
        text: true
    }).click(function(e){
        
        $('#email_msg').html('');
        $('#pass_msg').html('');
        $('#confirm_pass_msg').html('');
        
        // submit form
        var data = $('#joinForm').serializeArray();
        data.push({name: 'action', value: 'join'});
        
        $.ajax({
            type: 'POST',
            data: $.param(data),
            url: 'user.php',
            error: ajaxErr,
            success: joinSuccess,
            dataType: 'json'
        });
        
        // prevent regular form submit
        
        e.preventDefault();
    
    });
    
});

function joinSuccess(results) {
    // debug
    if (debug) alert(JSON.stringify(results));
    
    if (!results.success) {
        if (results.no_password) {
            $('#pass_msg').html("You must enter a password!");
        }
        if (results.short_pass) {
            $('#pass_msg').html("Password must be six characters!");
        }
        if (results.no_confirm_password) {
            $('#confirm_pass_msg').html("You must confirm your password!");
        }
        if (results.no_match) {
            $('#confirm_pass_msg').html("Passwords do not match!");
        }
        if (results.no_email) {
            $('#email_msg').html("You must enter an email address!");
        }
        if (results.invalid_email) {
            $('#email_msg').html("You must enter a valid email address!");
        }
        if (results.taken) {
            $('#email_msg').html('Email address is already taken!');
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