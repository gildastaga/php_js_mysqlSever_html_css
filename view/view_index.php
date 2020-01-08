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
        <div class="title">Stuck Overflow  
            <form class="menu">
                <?php if (!$user): ?>
                    <?php include('menu.html'); ?>
                <?php else: ?>
                    <?php include('menus.html'); ?>
                <?php endif; ?>
        </div> 
        <br>
        <div class="main"> 
            <div class="menus">
                <form class="menus">
                    <a href="post/newest">Newest</a>
                    <a href="vote/active"> Active</a>
                    <a href="post/unanswered">Unanswered</a>
                    <a href="vote/votes">Vote</a>
                </form> 
                <form class="recherche">
                    <input id="idsearch" type="search" name="" aria-label="search ">
                    <button>search</button>
                </form>
            </div>
            <?php if ($user): ?>
                <form id="post_form" action="post/index?param1=<?= $Title->UserName ?>" method="post">
                    <textarea id="Title" name="Title" rows='1'></textarea><br>              
                    <textarea id="Body" name="Body" rows='8'></textarea><br>
                    <input id="post" type="submit" value="Post">
                </form>
            <?php endif; ?>
            <br><br><br><br>
            <div class="main">
                afficharge des question !!!!!
                <?php var_dump($posts); foreach ($posts as $question): ?>
                    <tr>q
                        <p> question</p>
                        <li><?php $question["Title"] ?></a></li>
                        <td><?php $question["Body"] ?></td>
                       
                    </tr>
                <?php endforeach; ?>  
            </div>            
        </div>
    </body>
</html>
