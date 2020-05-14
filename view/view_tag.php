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
        <script>
            $.validator.addMethod("regex", function (value, element, pattern) {
                if (pattern instanceof Array) {
                    for(p of pattern) {
                        if (!p.test(value))
                            return false;
                    }
                    return true;
                } else {
                    return pattern.test(value);
                }
            });
            $(function (){
                $('#tagform').validate({ 
                    rules: {
                        TagName: {
                            remote: {
                                url: 'tag/TagName_available_service',
                                type: 'post',
                                data:  {
                                    TagName: function() {
                                         return $("#TagName").val();
                                    }
                                }
                            },
                            required: true,
                            minlength: 3,
                            maxlength: 16,
                            regex: /^[a-zA-Z][a-zA-Z0-9]*$/,
                        }
                    } ,  
                    messages: {
                        TagName: {
                            remote: 'this TagName is already taken',
                            required: 'required TagName',
                            minlength: 'minimum 3 characters',
                            maxlength: 'maximum 16 characters',
                            regex: 'bad format for TagName',
                        }
                  });      
                $("input:text:first").focus();    
            });  
    </script> 
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
                                    <textarea id="TagName" name="TagName" > <?= $values->TagName;?></textarea><div class="errors" id="errTagName"></div>
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
                <form id="tagform" action="tag/add_tag" method="post"  >
                    <textarea id="TagName" name="TagName" rows='1'  value="<?= $TagName ?>" > <?= "new tag name"; ?> </textarea>
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
