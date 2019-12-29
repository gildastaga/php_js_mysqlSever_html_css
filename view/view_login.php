 <!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="title">Stuck Overflow  
            <form class="menu">
                <a href="post/index">home</a>
           </form>
        <div class="login">
            <form action="user/login" method="post">
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
                <input type="submit" value="Log In">
                 </center>
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
