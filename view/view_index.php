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
                    <a> Active</a>
                    <a href="post/unanswered">Unanswered</a>
                    <a href="vote/index">Vote</a>
                </form> 
            </div>   
            <div>
                <form class="recherche" action="post/post_search" method="post">
                    <input id="idsearch" type="search" name="search" aria-label="search ">
                    <input id="post" type="submit" value="search">
                </form>
            </div>
            <br><br>
            <div class="main">
                <?php if ($posts || $questions) : ?>
                    <table id="message_list" class="message_list">
                        <?php foreach ($posts as $values): ?>                         
                        <tr>
                        <li><a href="post/show/<?php echo $values->PostId; ?>"><?php echo $values->Title; ?></a><br></li>
                        &nbsp &nbsp <?php echo "  ".$values->Body; ?><br><br>
                        &nbsp &nbsp <a>asked <span><?php echo $values->Timestamp; ?></span> day ago
                           &nbsp by <?php  echo $values->name($values->AuthorId); ?>( &nbsp <?php echo $values->nbr_vote($values->PostId); ?> vote(s) &nbsp, <?php echo $values->count_Answer($values->PostId); ?> Answer (s)) &nbsp   </a>
                    </tr><br>                         
                        <?php endforeach; ?>  
                    </table>
                <?php endif; ?>

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
        </div>
    </body>
</html>
