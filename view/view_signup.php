<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Stuck Overflow</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
        
        <script>
            let UserName,Password,PasswordConfirm ,Email,FullName;
            document.onreadystatechange = function () {
                if (document.readyState === 'complete') {
                    UserName = document.getElementById("UserName");
                    FullName = document.getElementById("FullName");
                    Password = document.getElementById("Password");
                    PasswordConfirm = document.getElementById("PasswordConfirm");
                    Email = document.getElementById("Email");
                }
            };
            function checkUserName(){
                let ok = true;
                errUserName.innerHTML = "";
                if(!(/^.{3,16}$/).test(UserName.value)){
                    errUserName.innerHTML += "<p>UserName length must be between 3 and 16.</p>";
                    ok = false;
                }
                if(UserName.value.length > 0 && !(/^[a-zA-Z][a-zA-Z0-9]*$/).test(UserName.value)){
                    errUserName.innerHTML += "<p>UserName must start by a letter and must contain only letters and users.</p>";  
                    ok = false;
                }
                return ok;
            }
            function checkFullName(){
                let ok = true;
                errFullName.innerHTML = "";
                if(!(/^.{3,16}$/).test(FullName.value)){
                    errFullName.innerHTML += "<p>FullName length must be between 3 and 16.</p>";
                    ok = false;
                }
                if(FullName.value.length > 0 && !(/^[a-zA-Z][a-zA-Z0-9]*$/).test(FullName.value)){
                    errFullName.innerHTML += "<p>FullName must start by a letter and must contain only letters and users.</p>";  
                    ok = false;
                }
                return ok;
            }
            
            function checkPassword(){
                let ok = true;
                errPassword.innerHTML = "";
                const hasUpperCase = /[A-Z]/.test(Password.value);
                const hasNumbers = /\d/.test(Password.value);
                const hasPunct = /['";:,.\/?\\-]/.test(Password.value);
                if(!(hasUpperCase && hasNumbers && hasPunct)){
                    errPassword.innerHTML += "<p>Password must contain one uppercase letter, one number and one punctuation mark.</p>";
                    ok = false;
                }
                if(!(/^.{8,16}$/).test(Password.value)){
                    errPassword.innerHTML += "<p>Password length must be between 8 and 16.</p>";
                    ok = false;
                }
                return ok;
            }
            
            function checkPasswords(){
                let ok = true;
                errPasswordConfirm.innerHTML = "";
                if(Password.value !== PasswordConfirm.value){
                    errPasswordConfirm.innerHTML += "<p>You have to enter twice the same password.</p>";
                    ok = false;
                }
                return ok;
            }
            
            function checkEmail(){
                let ok = true;
                errEmail.innerHTML = "";
                const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(Email.value);
                if (!re.test(Email.value)) {
                    errEmail.innerHTML += "<p> Email must start by a letter and must contain @ .</p>";
                    ok = false;
                }
                return ok;
            }
            
            function checkAll(){
                // les 3 lignes ci-dessous permettent d'éviter le shortcut
                // par rapport à checkPseudo()&&checkPassword()&&checkPasswords();
                let ok = checkUserName();
                ok = checkFullName() && ok;
                ok = checkPassword() && ok;
                ok = checkPasswords() && ok;
                ok = checkEmail() && ok;
                return ok;
            }
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
            <form id="signupForm" action="user/signup" method="post" onsubmit="return checkAll();"> 
                <center>
                    <table>
                        <tr>
                            <td>Username:</td>
                            <td><input id="UserName" name="UserName" type="text" size="16" oninput='checkUserName();' value="<?= $UserName ?>"></td>
                            <td class="errors" id="errUserName"></td>
                        </tr>
                        <tr>
                            <td>Fullname:</td>
                            <td><input id="FullName" name="FullName" type="text" size="16" oninput='checkFullName();' value="<?= $FullName ?>"></td>
                            <td class="errors" id="errFullName"></td>
                        </tr>
                        <tr>
                            <td>Password:</td>
                            <td><input id="Password" name="Password" type="Password" size="16" oninput='checkPassword();' value="<?= $Password ?>"></td>
                            <td class="errors" id="errPassword"></td>
                        </tr>
                        <tr>
                            <td>Confirm Password:</td>
                            <td><input id="PasswordConfirm" name="Password_confirm" type="Password" size="16" oninput='checkPasswords();' value="<?= $Password_confirm ?>"></td>
                            <td class="errors" id="errPasswordConfirm"></td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td><input id="Email" name="Email" type="email" size="16" oninput='checkEmail();' value="<?= $Email ?>"></td>
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
