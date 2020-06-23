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
        var post= document.getElementById('.option');
       this.post.addEventListener('click', getdetail);
        function getdetail(){       this.alert("ok");
            $.post("post/getdetailUser",{PostId:$("#period").val()},function(data) {
                datas = jQuery.parseJSON(data);
                index(datas);
            }) ;
        }
        
         posts = $("#postList");
        function index(datas){
            $("#postList").html("");
            var table = "";
            table += '<li>'+ datas.user.UserName +',' + datas.user.Email +',nombre de answeer :'+datas.nbr+'</li>';
            posts.append(table);
        }
        
        $(this).nextAll(".period").children(".option").click(function () {
            $.post("post/getdetailUser",{PostId:$("#period").val()},function(data) {
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
        <div>
            <select  name="TagId" class="period" id="period"  >
                <?php foreach ($posts as $rows): ?> 
                    <option class="option" onclick="getdetail()" value=<?php echo $rows->PostId; ?> ><?php echo $rows->Title; ?></option>    
                <?php endforeach; ?> 
            </select> 
        </div>    
        <div id="postList">
            
        </div>
    </body>
</html>
