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
            <div class="title"><a href="post/show/ <?php echo $comment->PostId; ?>"><img style="color: white;"src="lib/parsedown-1.7.3/back.png" width="30" height="20"  alt=""/></a>Stuck Overflow </div>
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
            <div>
                <form id="post_form" action="comment/add_comment/<?php echo $comment->CommentId; ?>/<?php echo TRUE; ?>" method="post">
                    <td>Body</td><br>
                    <textarea id="Body" name="Body" rows='3'><?= $comment->Body; ?></textarea><br><br>
                    <input id="post" type="submit" value="Save">
                </form>
            </div>    
            <br> 
            <?php if (count($errors) != 0): ?>
                <div class='errors'>
                    <br><p>Please correct the following error(s) :</p>
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
