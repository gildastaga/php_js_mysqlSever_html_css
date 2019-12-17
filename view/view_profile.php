<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?= $user->UserName ?>'s Profile!</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="title"><?= $user->UserName ?>'s Profile!</div>
        <?php include('menu.html'); ?>
        <div class="main">
            <?php if (strlen($user->profile) == 0): ?>
                No profile string entered yet!
            <?php else: ?>
                <?= $user->profile; ?>
            <?php endif; ?>
            <br><br>
           
            <br>
            <br>
            <a href="Post/questions?param1=<?= $user->UserName ?>">View <?= $user->UserName ?>'s questions</a>
        </div>
    </body>
</html>
