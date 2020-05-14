$(function (){
    validate_login();
});
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

function_validate_login(){
    $('#loginForm').validate()({
        rules: {
            UserName: {
                remote: {
                    url: 'user/UserName_available_service',
                    type: 'post',
                    data:  {
                    UserName: function() {
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
        },
        messages: {
            pseudo: {
                remote: 'this UserName is already taken',
                required: 'required',
                minlength: 'minimum 3 characters',
                maxlength: 'maximum 16 characters',
                regex: 'bad format for UserName',
            },
            password: {
                required: 'required',
                minlength: 'minimum 8 characters',
                maxlength: 'maximum 16 characters',
                regex: 'bad password format',
            },
        };
    }),
    $("input:text:first").focus();
};