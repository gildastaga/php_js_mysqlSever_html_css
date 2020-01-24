<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Stuck Overflow </title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="bloc1">
            <div class="title">Stuck Overflow </div>
            <div>
                <form class="menu">
                    <?php if (!$user): ?>
                        <?php include('menu.html'); ?>
                    <?php else: ?>
                        <?php include('menus.html'); ?>
                    <?php endif; ?>
                </form>   
            </div>
        </div>
        <br>
        <div class="main">
        <br><br>
        <table id="message_list" class="message_list">
            <tr>
                <?php echo $user->UserName; ?>
                <a href="post/edit"><img src="lib/parsedown-1.7.3/edit.png" width="30" height="20"  alt=""/></a>
                <a href="post/delete_confirm"><img src="lib/parsedown-1.7.3/delete.png" width="30" height="20"  alt=""/></a><br><br>
            </tr>    
        <tr>
            <td>
            vote<br>
                <a href="vote/addvote"><img src="lib/parsedown-1.7.3/vote1.png" width="30" height="20"  alt=""/></a><br>
                <a href="vote/devote"><img src="lib/parsedown-1.7.3/vote2.png" width="30" height="20" alt=""/></a>
            </td>
            <td>
                <li><?php echo $posts->Body; ?></li> <br>
                <a href="post/edit/<?php echo $values->PostId; ?>"></a><br>
                !!! il faut gere l'update etdelete de ses post
                <li><?php echo $posts->AcceptedAnswerId; ?></li> <br>
                 <form id="post_form" action="post/answer" method="post">
                    Anwer<br>
                    <textarea id="Body" name="AcceptedAnswerId" rows='8'></textarea><br><br>
                    <input id="post" type="submit" value="put your Answer">
                </form>
                <br><br>
            </td>
        </tr>
        </table>
        <br>
        <br><br><br>
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
