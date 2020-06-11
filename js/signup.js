
    $.validator.addMethod("regex", function (value, element, pattern) {
        if (pattern instanceof Array) {
            for (p of pattern) {
                if (!p.test(value))
                    return false;
                    }
                    return true;
                } else {
        return pattern.test(value);
                }
            }, "Please enter a valid input.");
            
    function_validate_signup(){
        $('#signupForm').validate({
            rules: {
            pseudo: {
                remote: {
                    url: 'user/UserName_available_service',
                    type: 'post',
                    data:  {
                        pseudo: function() {
                            return $("#UserName").val();
                                    }
                                }
                            },
                required: true,
                minlength: 3,
                maxlength: 16,
                regex: /^[a-zA-Z][a-zA-Z0-9]*$/,
                },
            password: {
                required: true,
                minlength: 8,
                maxlength: 16,
                regex: [/[A-Z]/, /\d/, /['";:,.\/?\\-]/],
                        },
            password_confirm: {
                required: true,
                minlength: 8,
                maxlength: 16,
                equalTo: "#password",
                regex: [/[A-Z]/, /\d/, /['";:,.\/?\\-]/],
            }
        },
         messages: {
                pseudo: {
                remote: 'this pseudo is already taken',
                        required: 'required',
                        minlength: 'minimum 3 characters',
                        maxlength: 'maximum 16 characters',
                        regex: 'bad format for pseudo',
                        },
                        password: {
                        required: 'required',
                        minlength: 'minimum 8 characters',
                        maxlength: 'maximum 16 characters',
                        regex: 'bad password format',
                        },
                        password_confirm: {
                        required: 'required',
                        minlength: 'minimum 8 characters',
                        maxlength: 'maximum 16 characters',
                        equalTo: 'must be identical to password above',
                        regex: 'bad password format',
                        }
                    }
                });
                $("input:text:first").focus();
            });
            
           

