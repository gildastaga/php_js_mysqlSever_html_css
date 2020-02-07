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
            <br><br>
        <table id="message_list" class="message_list">
            <?php if ($user): ?>
                <tr>
                    <?php  echo $user->UserName; ?>
                    <a href="post/edit/<?php echo $posts->PostId; ?>"><img src="lib/parsedown-1.7.3/edit.png" width="30" height="20"  alt=""/></a>
                    <a href="post/delete_confirm/<?php echo $posts->PostId; ?>"><img src="lib/parsedown-1.7.3/delete.png" width="30" height="20"  alt=""/></a><br><br>
                </tr> 
             <?php endif; ?>
            <tr>
                <td>
                    <table> 
                        <tr>
                             <td>
                                 <li><?php echo $posts->Body; ?></li> <br>
                             </td>
                         </tr>
                    </table>

                    Answer()
                        <?php foreach ($listanswer as $row): ?>
                            <table> 
                                <tr>
                                    <?php if ($user): ?>
                                    <td>
                                        <a href="vote/add_vote/<?php echo $row->PostId; ?>/<?php echo TRUE; ?>/<?php echo $posts->PostId; ?>"><img src="lib/parsedown-1.7.3/vote1.png" width="30" height="20"  alt=""/></a><br>
                                        <?php echo $row->nbr_vote($row->PostId); ?> score(s)<br>
                                        <a href="vote/add_vote/<?php echo $row->PostId; ?>/<?php echo FALSE; ?>/<?php echo $posts->PostId; ?>"><img src="lib/parsedown-1.7.3/vote2.png" width="30" height="20" alt=""/></a><br>
                                         <a href="post/accept_and_refuse_answer/<?php echo $row->PostId; ?>/<?php echo FALSE; ?>/<?php echo $posts->PostId; ?>"><img src="lib/parsedown-1.7.3/refuser.png" width="30" height="20"  alt=""/></a>
                                         <a href="post/accept_and_refuse_answer/<?php echo $row->PostId; ?>/<?php echo TRUE; ?>/<?php echo $posts->PostId; ?>"><img src="lib/parsedown-1.7.3/accepte.png" width="30" height="20" alt=""/></a>
                                     </td>
                                      <?php endif; ?>
                                     <td>
                                        <li><?php echo $row->Body; ?></li><br>
                                        <?php if ($user): ?>
                                         <a href="post/edit/<?php echo $row->PostId; ?>"><img src="lib/parsedown-1.7.3/edit.png" width="30" height="20"  alt=""/></a>
                                         <a href="post/delete_confirm/<?php echo $row->PostId; ?>"><img src="lib/parsedown-1.7.3/delete.png" width="30" height="20"  alt=""/></a><br>
                                         <?php endif; ?>
                                         <p>asked <span><?php echo $row->Timestamp; ?></span>
                                          &nbsp by <?php echo $row->name($row->AuthorId); ?> &nbsp by &nbsp</p>
                                     </td>
                                 </tr>
                            </table>
                       <?php endforeach;?>
                    <?php // endif; ?><br><br><br>
                    
                    
                    Add your Anwer<br>
                    <form id="post_form" action="post/addanswer/<?php echo $posts->PostId; ?>" method="post">                 
                        <textarea id="Body" name="Body" rows='8'></textarea><br><br>
                        <input id="post" type="submit" value="put your Answer">
                    </form>
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
