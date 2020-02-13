<?php
require_once "lib/parsedown-1.7.3/Parsedown.php";
?>
<!DOCTYPE html>
<html style="background: url(/lib/fonds.jpg) no-repeat;">
    <head>
        <meta charset="UTF-8">
        <title>Stuck Overflow </title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="bloc1">
            <form class="title">Stuck Overflow </form>
            <form class="menu">
                <?php if (!$user): ?>
                    <?php include('menu.html'); ?>
                <?php else: ?>
                    <?php include('menus.html'); ?>
                <?php endif; ?>
            </form>              
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
            <br><br><br><br>
            <div class="main">
                <?php if ($posts || $questions) : ?>
                    <table id="message_list" class="message_list">
                        <?php foreach ($posts as $values): ?>                         
                        <tr> 
                        <li><a href="post/show/<?php echo $values->PostId; ?>"><?php echo $values->Title; ?></a></li>
                        &nbsp <?php echo "  ".$values->markdown(); ?>
                        &nbsp &nbsp <a>asked <span><?php  echo $values->temp_ago($values->Timestamp)[0];?></span>  ago
                           &nbsp by <?php  echo $values->name($values->AuthorId); ?>( &nbsp <?php echo $values->nbr_vote($values->PostId); ?> vote(s) &nbsp, <?php echo $values->count_Answer($values->PostId); ?> Answer (s)) &nbsp   </a>
                    </tr><br>                         
                        <?php endforeach; ?>  
                    </table><br>
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
