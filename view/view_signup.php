<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Stuck Overflow</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
        <script src="lib/jquery-3.4.1.min.js" type="text/javascript"></script>
        <script src="lib/jquery-validation-1.19.1/jquery.validate.min.js" type="text/javascript"></script>
        <script>
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
            $(function (){
                $('#signupForm').validate({ 
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
                        FullName: {
                            required: true,
                            minlength: 3,
                            maxlength: 16,
                            regex: /^[a-zA-Z][a-zA-Z0-9]*$/,
                        },
                        password: {
                            required: true,
                            minlength: 8,
                            maxlength: 16,
                            regex: /^[a-zA-Z][a-zA-Z0-9]*$/,
                        },
                        password_confirm: {
                            required: true,
                            minlength: 8,
                            maxlength: 16,
                            equalTo: "#password",
                            regex:/^[a-zA-Z][a-zA-Z0-9]*$/,
                        },
                        Email: {
                            remote: {
                                url: 'user/Email_available_service',
                                type: 'post',
                                data:  {
                                    Email: function() {
                                         return $("#Email").val();
                                    }
                                }
                            },
                            required: true,
                            minlength: 3,
                            maxlength: 16,
                            regex:[/^[_a-z0-9]+(\.[_a-z0-9]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/],
                        }
                    } ,  
                    messages: {
                        UserName: {
                            remote: 'this UserName is already taken',
                            required: 'required UserName',
                            minlength: 'minimum 3 characters',
                            maxlength: 'maximum 16 characters',
                            regex: 'bad format for UserName',
                        },
                        FullName: {
                            required: 'required FullName',
                            minlength: 'minimum 3 characters',
                            maxlength: 'maximum 16 characters',
                            regex: 'bad format for FullName',
                        },
                        password: {
                            required: 'required password',
                            minlength: 'minimum 8 characters',
                            maxlength: 'maximum 16 characters',
                            regex: 'bad password format',
                        },
                        password_confirm: {
                            required: 'required password confirm',
                            minlength: 'minimum 8 characters',
                            maxlength: 'maximum 16 characters',
                            equalTo: 'must be identical to password above',
                            regex: 'bad password format',
                         },
                         Email: {
                            remote: 'this Email is already taken',
                            required: 'required Email',
                            minlength: 'minimum 3 characters',
                            maxlength: 'maximum 16 characters',
                            regex: 'bad format for Email',
                        }   
                    }
                  });      
                $("input:text:first").focus();    
            });  
    </script> 
    </head>
    <body>
        <div class="bloc1">
            <div class="title"><a href="post/index"><img style="color: white;"src="lib/parsedown-1.7.3/back.png" width="30" height="20"  alt=""/></a>Stuck Overflow</div>
            <div class="menu">
                <a href="post/index">Home</a>
            </div>
        </div>
        <br><br>
        <div class="main">
            <center>
                <h1>SIGNUP</h1>            
                <br>
                <p> Please enter your details to sign up :</p>
                <br><br>
            </center>    
            <form id="signupForm" action="user/signup" method="post" > 
                <center>
                    <table>
                        <tr>
                            <td>Username:</td>
                            <td><input id="UserName" name="UserName" type="text" size="16"  value="<?= $UserName ?>"></td>
                            <td class="errors" id="errUserName"></td>
                        </tr>
                        <tr>
                            <td>Fullname:</td>
                            <td><input id="FullName" name="FullName" type="text" size="16"  value="<?= $FullName ?>"></td>
                            <td class="errors" id="errFullName"></td>
                        </tr>
                        <tr>
                            <td>Password:</td>
                            <td><input id="Password" name="Password" type="Password" size="16"  value="<?= $Password ?>"></td>
                            <td class="errors" id="errPassword"></td>
                        </tr>
                        <tr>
                            <td>Confirm Password:</td>
                            <td><input id="PasswordConfirm" name="Password_confirm" type="Password" size="16"  value="<?= $Password_confirm ?>"></td>
                            <td class="errors" id="errPasswordConfirm"></td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td><input id="Email" name="Email" type="email" size="16"  value="<?= $Email ?>"></td>
                            <td class="errors" id="errEmail"></td>
                        </tr>
                    </table>
                </center>    
                <br><br>
                <input id="btn" type="submit" value="Sign Up"  >
            </form>
            <?php if (count($errors) != 0): ?>
                <div class='errors'>
                    <br><br><p>Please correct the following error(s) :</p>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

        </div>
    </body>
</html>    