<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Stuck Overflow</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
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
            <form id="signupForm" action="user/signup" method="post"> 
                <center>
                    <table>
                        <tr>
                            <td>Username:</td>
                            <td><input id="UserName" name="UserName" type="text" size="16" value="<?= $UserName ?>"></td>
                        </tr>
                        <tr>
                            <td>Fullname:</td>
                            <td><input id="FullName" name="FullName" type="text" size="16" value="<?= $FullName ?>"></td>
                        </tr>
                        <tr>
                            <td>Password:</td>
                            <td><input id="Password" name="Password" type="Password" size="16" value="<?= $Password ?>"></td>
                        </tr>
                        <tr>
                            <td>Confirm Password:</td>
                            <td><input id="Password_confirm" name="Password_confirm" type="Password" size="16" value="<?= $Password_confirm ?>"></td>
                        </tr>
                        <tr>
                            <td>Email:</td>
                            <td><input id="Email" name="Email" type="email" size="16" value="<?= $Email ?>"></td>
                        </tr>
                    </table>
                </center>    
                <br><br>
                <input type="submit" value="Sign Up"  >
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
