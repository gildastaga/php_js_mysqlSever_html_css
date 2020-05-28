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
        <script>
            $(document).ready(function(){
                $("message_list").hide();
                 $('#search').keyup(function(){
                     var search=$(this).val(); 
                     $.post("post/searchJson", {search: search}, function(data) {
                        var data_object = jQuery.parseJSON(data); 
                        getPosts(data_object);
                     });
                 });
            });
            
            function getPosts(data) {
                
                tblMessages = $('#message_list');
                tblMessages.html("<tr><td>Loading...</td></tr>");
                var tab=data;
                var html = "<t> </tr>";
                for (var m of tab) {
                    html += "<tr>";
                    html += "<li><a href='post/show/"+m.PostId+"'>"+m.Title+"</a></li>";
                    html+="&nbsp&nbsp "+m.Body+"<br>";
                    html += "</tr>";
                }
                tblMessages.html(html);
                
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
        <br>
        <div class="main"> 
            <div class="menus">
                <form class="menus">
                    <a href="post/newest">Newest</a>
                    <a href="post/active"> Active</a>
                    <a href="post/unanswered">Unanswered</a>
                    <a href="vote/index">Vote</a>
                    <a href="post/by_tag">by tag</a>
                </form> 
            </div>   
            <div>
                <form class="recherche" id="recherche" action="post/fiter" method="post" method="get">
                    <input id="search" type="search" name="search"   aria-label="search ">
                    <div class="result" id="result" ></div>
                    <input id="post" type="submit" value="search">
                </form>
            </div>
            <br><br><br><br>
            <div class="main">      
                <div  class="message_list" >
                    <table id="message_list" class="message_list">
                    
                    </table>
                   
                    <table id="message_list" class="message_list">
                        <?php foreach ($posts as $values): ?>                         
                            <tr> 
                            <li><a href="post/show/<?php echo $values->PostId; ?>"><?php echo $values->Title; ?></a></li>
                            &nbsp <?= "  " . $values->markdown(); ?>
                            <br>&nbsp &nbsp asked <span><?php echo $values->temp_ago()[0]; ?></span> 
                            &nbsp by <?php echo $values->name(); ?>(<?php echo Post::nbr_vote($values->PostId); ?> vote(s) &nbsp, <?php echo $values->count_Answer(); ?> Answer (s)) &nbsp
                            <?php $taglispost = Tag::get_tag_bypostId($values->PostId); ?>
                            <?php foreach ($taglispost as $row): ?>
                                <a href="post/by_tag/<?= $row->TagId ?>"><?= $row->TagName ?></a>&nbsp
                            <?php endforeach; ?>
                            </tr><br><br>                          
                        <?php endforeach; ?>  
                    </table>
                </div><br>
                <div class="pagination">
                    <li  style="page-item <?= ($currentPage>1) ? " -webkit-filter:blur(90deg);
                                                        filter: blur(90deg);" :
                                                        "none" ?>">
                        <a  style="page-link ml-auto" href="post/<?=$action.'/'.($currentPage-1) ?>">prev &laquo; </a>
                    </li>
                    <?php for($page_i=1;$page_i<=$nbr;$page_i++): ?>
                        <li  style="page-item <?= ($currentPage==$page_i)? " -webkit-filter: blur(90deg);
                                                        filter: blur(90deg);" :
                                                        "-webkit-filter: grayscale(1);
                                                        filter: grayscale(1);" ?>">
                            <a  style="page-link ml-auto" href="post/<?=$action.'/'.$page_i ?>"><?= $page_i; ?> </a>
                        </li>
                    <?php endfor;?>
                        <li style="page-item <?= ($currentPage<$nbr)? " -webkit-filter: blur(90deg);
                                                        filter: blur(90deg);" :
                                                        "-webkit-filter: grayscale(1);
                                                        filter: grayscale(1);" ?>">
                        <a  style="page-link ml-auto" href="post/<?=$action.'/'.($currentPage+1) ?>">&raquo;next </a>
                    </li>
                </div> 
            </div>            
        </div>
    </body>
</html>
