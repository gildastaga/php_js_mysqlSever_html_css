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
        <script src="lib/jquery-3.4.1.min.js" type="text/javascript"></script>
        <script src="lib/jquery-validation-1.19.1/jquery.validate.min.js" type="text/javascript"></script>
        <script src="js/tag.js" type="text/javascript"></script> 
    </head>
    <body>
        <div class="bloc1">
            <div class="title"> <a href="post/index"><img src="lib/parsedown-1.7.3/back.png" width="30" height="20"  alt=""/></a>Stuck Overflow </div>
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

        <div class="main">                
            <table id="message_list" class="message_list">
                <tr>
                    <th>TagName</th>
                    <?php if ($user && $user->Role =="admin"): ?>
                        <th>action</th>
                    <?php endif; ?>    
                 </tr> 
                 <?php foreach ($tag as $values): ?>
                    <tr>
                        <td>
                            <?php echo $values->TagName; ?>
                            <a href="post/by_tag/<?php echo $values->TagId;?>">(<?php echo Tag::nbr_post_bytag($values->TagId) . ' Posts'; ?>  ) </a>
                        </td>
                        <?php if ($user&&$user->Role =="admin"): ?>
                            <td>
                                <form id="tagform" action="tag/add_tag/<?php echo $values->TagId;?>" method="post">
                                    <textarea id="TagName" name="TagName" ><?= $values->TagName;?></textarea><div class="errors" id="errTagName"></div>
                                    <input id="post" type="image" img src="lib/parsedown-1.7.3/edit.png" width="30" height="20"alt="">            
                                    <a href="tag/delete_tag/<?php echo $values->TagId; ?>">
                                    <img src="lib/parsedown-1.7.3/delete.png" width="30" height="20"  alt=""/></a>
                                </form>
                           </td>
                        <?php endif; ?>   
                    </tr>     
                <?php endforeach; ?>  
            </table><br>
            <?php if ($user&& $user->Role =="admin"): ?>
                <form id="tagformadd" action="tag/add_tag" method="post"  >
                    <textarea id="TagName" name="TagName" rows='1'  value="<?= $TagName ?>" ><?= "new tag name"; ?></textarea>
                    <input  type="image" img src="lib/parsedown-1.7.3/plus.png" width="30" height="20"  alt="">
                    <div class="errors" id="errTagName"></div>
                </form>
            <?php endif; ?>
            <?php if($errors!=NULL ||count($errors) != 0): ?>
                    <div class='errors'>
                        <p>Please correct the following error(s) :</p>
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
