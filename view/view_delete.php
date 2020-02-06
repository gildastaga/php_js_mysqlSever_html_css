<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Stuck Overflow </title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body class="bloc">
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
        <br>
                <div class="delete" >
                    <table id="message_list" class="message_list">
                        <tr>
                            <img src="lib/parsedown-1.7.3/delete.png" width="90" height="50"  alt=""/><br><br>
                            <h2> are you sure ?</h2>
                        </tr>
                        <tr>
                            <p> Do you really want to delete this post? <br><br>
                            This process cannot be undone<br>
                            </p>
                            <td><a href="post/index">cancel</a></td> 
                            <td> <a href="post/am_ok_delete/<?php echo $posts->PostId; ?>">DELETE</a></td> 
                        </tr>
                    </table>
                </div>   
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
