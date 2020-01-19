<?php
require_once "lib/parsedown-1.7.3/Parsedown.php";
?>
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
                <?php if ($user && $Ak_a): ?>
                    <form id="post_form" action="post/Ak_a_question=<?= $Title->UserName ?>" method="post">
                        <td>Title</td<br>
                        <textarea id="Title" name="Title" rows='1'></textarea><br>  
                        <td>Body</td><br>
                        <textarea id="Body" name="Body" rows='8'></textarea><br>
                        <input id="post" type="submit" value="Post">
                    </form>
                <?php endif; ?>
            </div>
            <br><br><br>
            <div class="main">
                <<<<<<< HEAD
                <?php if ($posts || $questions) : ?>
                    <table id="message_list" class="message_list">
                        <?php foreach ($posts as $values): ?>
                            <?php
                            // $markdown = $question;
//                            $Parsedown = new Parsedown();
//                            $Parsedown->setSafeMode(true);
//                            $html = $Parsedown->text($markdown);
//                            echo $html; 
                            ?>
                            <tr>
                            <a href="post/question"><?php echo $values->Title; ?></a><br>
                            </tr>
                                <?php if ($values->Title): ?>
                                <tr>
                                    <td>
                                        vote<br>
                                        <a href="vote/addvote"><img src="lib/parsedown-1.7.3/haudb.png" width="30" height="20"  alt=""/></a><br>
                                        <a href="vote/devote"><img src="lib/parsedown-1.7.3/basb.png" width="30" height="20" alt=""/></a>
                                    </td>
                                    <td>
                                      <li><?php echo $values->Body; ?></li>
                                    </td>
                                </tr>
                            <?php endif; ?>   
                        <?php endforeach; ?>  
                    </table>
                <?php endif; ?>
                <?php if ($newest): ?>
                    <table>
                        <?php foreach ($newest as $value): ?> 
                            <tr><li><a href="post/newest" > <?php echo $value->Title; ?> </a></li></tr> 
                        <?php endforeach; ?>
                    </table>
                 <?php endif; ?>
                >>>>>>> origin/master
            </div>            
        </div>
    </body>
</html>
