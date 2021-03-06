$(function (){
    validate_tag();
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
});

function validate_tag(){
    $('#tagform').validate({
        rules: {
            TagName: {
                remote: {
                    url: 'tag/TagName_available_service',
                    type: 'post',
                    data:  {
                        TagName: function() {
                            return $("#TagName").val();
                        }
                    }
                },
                required: true,
                minlength: 3,
                maxlength: 16,
                regex: /^[a-zA-Z][a-zA-Z0-9]*$/
            }
        },
        messages: {
            TagName: {
                remote: 'this TagName is already taken',
                required: 'required TagName',
                minlength: 'minimum 3 characters',
                maxlength: 'maximum 20 characters',
                regex: 'bad format for TagName'
            }
        }
    });
    $('#tagformadd').validate({
        rules: {
            TagName: {
                remote: {
                    url: 'tag/TagName_available_service',
                    type: 'post',
                    data:  {
                        TagName: function() {
                            return $("#TagName").val();
                        }
                    }
                },
                required: true,
                minlength: 3,
                maxlength: 16,
                regex: /^[a-zA-Z][a-zA-Z0-9]*$/
            }
        },
        messages: {
            TagName: {
                remote: 'this TagName is already taken',
                required: 'required TagName',
                minlength: 'minimum 3 characters',
                maxlength: 'maximum 20 characters',
                regex: 'bad format for TagName'
            }
        }
    });
    
};


