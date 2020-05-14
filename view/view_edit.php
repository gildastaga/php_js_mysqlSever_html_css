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
                $('#Edit_form').validate({ 
                    rules: {
                        Title: {
                            remote: {
                                url: 'post/Title_available_service',
                                type: 'post',
                                data:  {
                                    Title: function() {
                                         return $("#Title").val();
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
                        Title: {
                            remote: 'this Title is already taken',
                            required: 'required Title',
                            minlength: 'minimum 3 characters',
                            maxlength: 'maximum 16 characters',
                            regex: 'bad format for Title',
                        }
                  });      
                $("input:text:first").focus();    
            });  
        </script>
    </head>
    <body>
        <div class="bloc1">
            <div class="title"><?php if ($posts->Title != NULL): ?><a href="post/show/ <?php echo $posts->PostId; ?>"><img style="color: white;"src="lib/parsedown-1.7.3/back.png" width="30" height="20"  alt=""/></a>
                <?php else: ?> <a href="post/show/<?php echo $posts->ParentId; ?>"><img style="color: white;"src="lib/parsedown-1.7.3/back.png" width="30" height="20"  alt=""/></a> <?php endif; ?>Stuck Overflow </div>
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
            <div>
                <form id="Edit_form" action="post/postupdate/<?php echo $posts->PostId; ?>" method="post">
                    <?php if ($posts->Title): ?>
                        <td>Title</td><br>
                        <textarea id="Title" name="Title" rows='1'><?= $posts->Title; ?></textarea><br>
                    <?php endif; ?>
                    <td>Body</td><br>
                    <textarea id="Body" name="Body" rows='8'><?= $posts->Body; ?></textarea><br><br>

                    <input id="post" type="submit" value="Save">
                </form>
            </div>    
            <br> 
            <?php if (count($errors) != 0): ?>
                <div class='errors'>
                    <br><p>Please correct the following error(s) :</p>
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