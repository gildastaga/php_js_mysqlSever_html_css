<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="bloc1">
            <div class="title"><a href="post/index"><img style="color: white;"src="lib/parsedown-1.7.3/back.png" width="30" height="20"  alt=""/></a>Stuck Overflow </div>
            <div class="menu"><a href="post/index">home</a></div>
        </div>
            <div class="main">
                <form id="loginForm"action="user/login" method="post">
                    <center>
                        <h1>SIGN IN</h1>                    
                        <table>
                            <tr>
                                <td>UserName</td>
                                <td><input id="UserName" name="UserName" type="text" value="<?= $UserName ?>"></td>
                            </tr>
                            <tr>
                                <td>Password:</td>
                                <td><input id="Password" name="Password" type="password" value="<?= $Password ?>"></td>
                            </tr>
                        </table>
                    </center>    
                    <br><br>
                    <input type="submit" value="Log In">

                </form>
                <?php if (count($errors) != 0): ?>
                    <div class='errors'>
                        <p>Please correct the following error(s) :</p>
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
