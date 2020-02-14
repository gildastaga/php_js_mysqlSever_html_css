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
            <div class="title"><?php if($posts->Title!=NULL): ?><a href="post/show/ <?php echo $posts->PostId; ?>">Home</a>
             <?php else: ?> <a href="post/show/<?php echo $posts->ParentId;?>">Home</a> <?php endif; ?>Stuck Overflow </div>
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
                <form id="post_form" action="post/postupdate/<?php echo $posts->PostId; ?>" method="post">
                    <?php  if ($posts->Title): ?>
                    <td>Title</td><br>
                   <textarea id="Title" name="Title" rows='1'><?= $posts->Title; ?></textarea><br>
                   <?php endif; ?>
                        <td>Body</td><br>
                        <textarea id="Body" name="Body" rows='8'><?= $posts->Body; ?></textarea><br><br>
                    
                    <input id="post" type="submit" value="Post">
                </form>
            </div>    
            <br> 
            <?php  if (count($errors) != 0): ?>
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