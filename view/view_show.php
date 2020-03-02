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
            <div class="title"> Stuck Overflow </div>
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
            <table id="message_list" class="message_list">
                <?php if ($user): ?>            
                    <?php echo $posts->Title; ?><br><br>
                    <tr>
                        Post by  <?php echo $user->get_user_by_UserId($posts->AuthorId)->UserName; ?>
                    <a href="post/edit/<?php echo $posts->PostId; ?>"><img src="lib/parsedown-1.7.3/edit.png" width="30" height="20"  alt=""/></a>
                    <a href="post/delete_confirm/<?php echo $posts->PostId; ?>"><img src="lib/parsedown-1.7.3/delete.png" width="30" height="20"  alt=""/></a><br><br>
                    </tr> 
                <?php endif; ?>
                <tr>
                    <td>
                        <table> 
                            <tr>
                                <?php if ($user): ?>
                                <td> 
                                        <a href="vote/add_vote/<?php echo $posts->PostId; ?>/<?php echo TRUE; ?>"><img 
                                            <?php if (Vote::get_vote($posts->PostId,$user->UserId) && Vote::get_vote($posts->PostId,$user->UserId)->UpDown== 1): ?>                  
                                                    style=" -webkit-filter: hue-rotate(90deg);
                                                    filter: hue-rotate(90deg);"
                                                <?php else: ?>
                                                    style="-webkit-filter: grayscale(1);
                                                    filter: grayscale(1);"
                                                <?php endif; ?>
                                                src="lib/parsedown-1.7.3/vote1.png" width="30" height="20"  alt=""/></a><br>
                                        <?php echo $posts->nbr_vote($posts->PostId); ?> score(s)<br>
                                        <a href="vote/add_vote/<?php echo $posts->PostId; ?>"><img
                                            <?php if (Vote::get_vote($posts->PostId,$user->UserId) && Vote::get_vote($posts->PostId,$user->UserId)->UpDown== -1): ?> 
                                                    style=" -webkit-filter: hue-rotate(90deg);
                                                    filter: hue-rotate(90deg);"
                                                <?php else: ?>
                                                    style="   -webkit-filter: grayscale(1);
                                                    filter: grayscale(1);"
                                                <?php endif; ?>
                                                src="lib/parsedown-1.7.3/vote2.png" width="30" height="20" alt=""/></a><br>
                                    </td>
                                <?php endif; ?>
                            <h6> body post </h6>   
                            <td>
                            <li><?php echo $posts->markdown(); ?></li> <br>
                            </td>
                            </tr>
                        </table><br><br>

                        <h6> Post response's list</h6> <br><br>
                        <?php foreach ($listanswer as $reponse): ?>
                            <table> 
                                <tr>
                                    <?php if ($user): ?>
                                        <td>
                                            <a href="vote/add_vote/<?php echo $reponse->PostId; ?>/<?php echo TRUE; ?>"><img 
                                                <?php if (Vote::get_vote($reponse->PostId,$user->UserId)  && Vote::get_vote($reponse->PostId,$user->UserId)->UpDown == 1 ): ?>                   
                                                        style=" -webkit-filter: hue-rotate(90deg);
                                                        filter: hue-rotate(90deg);"
                                                <?php else: ?>
                                                        style="-webkit-filter: grayscale(1);
                                                        filter: grayscale(1);"
                                                <?php endif; ?>
                                                    src="lib/parsedown-1.7.3/vote1.png"  width="30" height="20"  alt=""/></a><br>
                                            <?php echo $reponse->nbr_vote(); ?> score(s)<br>
                                            <a href="vote/add_vote/<?php echo $reponse->PostId; ?>"><img 
                                                <?php if (Vote::get_vote($reponse->PostId,$user->UserId) && Vote::get_vote($reponse->PostId,$user->UserId)->UpDown== -1): ?>                   
                                                        style=" -webkit-filter: hue-rotate(90deg);
                                                        filter: hue-rotate(90deg);"
                                                    <?php else: ?>
                                                        style="-webkit-filter: grayscale(1);
                                                        filter: grayscale(1);"
                                                    <?php endif; ?>  
                                                    accesskey=""src="lib/parsedown-1.7.3/vote2.png" width="30" height="20" alt=""/></a><br>
                                                <?php //if($posts->AuthorId ==$user->UserId): ?>
                                            <a href="post/accept_and_refuse_answer/<?php echo $reponse->PostId; ?>/<?php echo FALSE; ?>"><img
                                                    style="-webkit-filter: grayscale(1); filter: grayscale(1);"
                                                    src="lib/parsedown-1.7.3/refuser.png" width="30" height="20"  alt=""/></a>
                                            <a href="post/accept_and_refuse_answer/<?php echo $reponse->PostId; ?>/<?php echo TRUE; ?>"><img
                                                <?php if ($posts->AcceptedAnswerId == $reponse->PostId ): ?>                   
                                                        style=" -webkit-filter: hue-rotate(90deg);
                                                        filter: hue-rotate(90deg);"
                                                    <?php else: ?>
                                                        style="-webkit-filter: grayscale(1);
                                                        filter: grayscale(1);"
                                                    <?php endif; ?>
                                                    src="lib/parsedown-1.7.3/accepte.png" width="30" height="20" alt=""/></a>
                                            <?php endif; ?>
                                    </td>
                                    <?php // endif; ?>
                                    <td>
                                <li><?php echo $reponse->markdown(); ?></li><br>
                                <?php if ($user): ?>
                                    <a href="post/edit/<?php echo $reponse->PostId; ?>"><img src="lib/parsedown-1.7.3/edit.png" width="30" height="20"  alt=""/></a>
                                    <a href="post/delete_confirm/<?php echo $reponse->PostId; ?>"><img src="lib/parsedown-1.7.3/delete.png" width="30" height="20"  alt=""/></a><br>
                                <?php endif; ?>
                                <p>asked <span><?php echo $reponse->temp_ago()[0]; ?></span>
                                    &nbsp by <?php echo $reponse->name(); ?> &nbsp by &nbsp</p>
                        </td>
                    </tr>
                </table>
            <?php endforeach; ?>
            <?php // endif; ?><br><br><br>

            <?php if ($user): ?>
                Add your Anwer<br>
                <form id="post_form" action="post/show/<?php echo $posts->PostId; ?>" method="post">                 
                    <textarea id="Body" name="Body" rows='8'></textarea><br><br>
                    <input id="post" type="submit" value="put your Answer">
                </form>
            <?php endif; ?>   
            <br><br>
            </td>
            </tr>
            </table>
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
