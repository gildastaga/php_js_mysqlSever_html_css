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
            <div class="menus">
                <form class="menus">
                    <a href="post/newest">Newest</a>
                    <a href="vote/active"> Active</a>
                    <a href="post/unanswered">Unanswered</a>
                    <a href="vote/votes">Vote</a>
                </form> 
            </div>   
            <div>
                <form class="recherche">
                    <input id="idsearch" type="search" name="" aria-label="search ">
                    <button>search</button>
                </form>
            </div>
            <br><br><br>
            <div>
                <?php if ($user && strlen($Ak_a_question) == 0): ?>
                    <form id="post_form" action="post/Ak_a_question=<?= $Title->UserName ?>" method="post">
                        <td>Title</td<br>
                        <textarea id="Title" name="Title" rows='1'></textarea><br>  
                        <td>Body</td><br>
                        <textarea id="Body" name="Body" rows='8'></textarea><br>
                        <input id="post" type="submit" value="Post">
                    </form>
                <?php endif; ?>
            </div>
            <br><br><br><br>
            <div class="main">
                afficharge des question !!!!!
                
                <?php var_dump($posts); ?><br>
                <?php foreach ($posts as $erro): ?>
                    <li><?= $erro ?></li>
                    <ul>
                        <?php foreach ($posts as $errod): ?>
                            <li><?= $errod ?></li><br>
                        <?php endforeach; ?>
                    </ul>
                <?php endforeach; ?>
                <?php foreach ($posts as $question): ?>
                    <tr>q
                    <p> question</p>
                    <li><?php $question["Title"] ?></a></li>
                    <td><?php $question["Body"] ?></td>

                    </tr>
                <?php endforeach; ?>
                    
                    
                <?php if ($newest): ?>
                    <table>
                <?php foreach ($newest as $value): ?> 
                        <tr><li><a href="post/newest" > <?php echo $value->Title; ?> </a></li></tr> 
                <?php endforeach; ?>
                    </table>
                <?php  endif ;?>
            </div>            
        </div>
    </body>
</html>
