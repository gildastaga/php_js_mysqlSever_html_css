<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="title">Stuck Overflow </div>
            <div class="menu">
                <br>
                <a href="post/index">home</a>
            </div>
            <div class="main">
                <form id="loginForm"action="user/login" method="post">
                    <center>
                        <h1>SIGN IN</h1>
                    </center>
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
