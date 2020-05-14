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
            $(function (){
                $('#recherche').validate({ 
                    rules: {
                        search: {
                            remote: {
                                url: 'post/search_available_service',
                                type: 'post',
                                data:  {
                                    search: function() {
                                         return $("#search").val();
                                    }
                                }
                            },
                            required: true
                        }
                    }  
                  });      
                $("input:text:first").focus();    
            })
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
                <form class="recherche" id="recherche" action="post/post_search" method="post" method="get">
                    <input id="search" type="search" name="search"   aria-label="search ">
                    <input id="post" type="submit" value="search">
                </form>
            </div>
            <br><br><br><br>
            <div class="main">                
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
                </table><br>
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
