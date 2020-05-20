$(function (){
    validate_login();
});

function validate_login(){
    $('#loginForm').validate({
        rules: {
            UserName: {
                remote: {
                    url: 'user/UserName_available_service_login',
                    type: 'post',
                    data:  {
                        UserName: function() {
                            return $("#UserName").val();
                        }
                    }
                },
                required: true
            },
            Password: {
                remote: {
                    url: 'user/Password_available_service_login',
                    type: 'post',
                    data:  {
                        UserName: function() {
                            return $("#UserName").val();
                        },
                        Password: function() {
                            return $("#Password").val();
                        }
                    }
                },
                required: true
            }
        },
        messages: {
            UserName: {
                remote: 'cet utilisateur n\'existe pas',
                required: 'required'
            },
            Password: {
                required: 'required',
                remote: 'bad password '
            }
        }
    });
    
    $("input:text:first").focus();
};