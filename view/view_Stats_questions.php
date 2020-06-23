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
        <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
        <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.theme.min.css" rel="stylesheet" type="text/css"/>
        <link href="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.structure.min.css" rel="stylesheet" type="text/css"/>
        <script src="lib/jquery-3.4.1.min.js" type="text/javascript"></script>
        <script src="lib/jquery-ui-1.12.1.ui-lightness/jquery-ui.min.js" type="text/javascript"></script>
        <script >
        var post= document.getElementById('#period');alert("ok");
        post.addEventListener('click', getdetail);
        function getdetail(){       this.alert("ok");
            $.get("post/getdetailUser",{PostId:$("#period").val()},function(data) {
                datas = jQuery.parseJSON(data);
                index(datas);
            }) ;
        }
        </script>


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
        <div>
            <?php foreach ($posts as $rows): ?> 
            <option id="option" onclick="getdetail()"  name=<?php echo $rows->PostId; ?> value=<?php echo $rows->PostId; ?> ><?php echo $rows->Title; ?></option>
         
                <?php endforeach; ?> 
        </div>
        <form action=""  method="post">
            <select  name="TagId" id="period">
                <?php foreach ($posts as $rows): ?> 
                    <option id="option"  name=<?php echo $rows->PostId; ?> value=<?php echo $rows->PostId; ?> ><?php echo $rows->Title; ?></option>
         
                <?php endforeach; ?> 
            </select>        
        </form>
    </body>
</html>
