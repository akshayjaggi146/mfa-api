$('#registerForm').submit(function(e) {
    e.preventDefault();
    $.ajax({
        url: '/api/register',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            name: $('#name').val(),
            email: $('#email').val(),
            password: $('#password').val()
        }),
        success: function(response) {
            alert(response.message);
        },
        error: function(xhr) {
            alert(xhr.responseJSON.message);
        }
    });
});
$('#loginForm').submit(function(e) {
    e.preventDefault();
    $.ajax({
        url: '/api/login',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            email: $('#email').val(),
            password: $('#password').val()
        }),
        success: function(response) {
            $('#mfaSection').show();
            $('#userId').val(response.user_id);
        },
        error: function(xhr) {
            alert(xhr.responseJSON.message);
        }
    });
});

$('#mfaForm').submit(function(e) {
    e.preventDefault();
    $.ajax({
        url: '/api/verify-mfa',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            user_id: $('#userId').val(),
            mfa_token: $('#mfaToken').val()
        }),
        success: function(response) {
            alert(response.message);
            localStorage.setItem('token', response.token);
        },
        error: function(xhr) {
            alert(xhr.responseJSON.message);
        }
    });
});

